<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MaterialModel;
use App\Models\EnrollmentModel;

class Material extends BaseController
{
    protected $session;
    protected $materialModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        
        $this->materialModel = new MaterialModel();
        $this->enrollmentModel = new EnrollmentModel();
        
        helper(['filesystem', 'form']);
    }
    
    /**
     * Create notification for user
     * 
     * @param int $userId User ID to receive notification
     * @param string $message Notification message
     * @return bool Success status
     */
    private function createNotification($userId, $message)
    {
        try {
            $notificationModel = new \App\Models\NotificationsModel();
            $philippineTimezone = new \DateTimeZone('Asia/Manila');
            $currentDateTime = new \DateTime('now', $philippineTimezone);
            
            $notificationData = [
                'user_id' => $userId,
                'message' => $message,
                'is_read' => 0,
                'created_at' => $currentDateTime->format('Y-m-d H:i:s')
            ];
            
            return $notificationModel->insert($notificationData);
        } catch (\Exception $e) {
            log_message('error', 'Notification creation error: ' . $e->getMessage());
            return false;
        }
    }

    public function upload($course_id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        $userRole = $this->session->get('role');
        if ($userRole !== 'teacher' && $userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Only teachers can upload materials.');
        }
        
        if (!$course_id || !is_numeric($course_id) || $course_id <= 0) {
            return redirect()->to('/dashboard')->with('error', 'Invalid course ID.');
        }
        
        $course_id = (int)$course_id;
        
        $db = \Config\Database::connect();
        $course = $db->table('courses')->where('id', $course_id)->get()->getRowArray();
        if (!$course) {
            return redirect()->to('/dashboard')->with('error', 'Course not found.');
        }
        
        if ($this->request->is('post')) {
            
            $validation = \Config\Services::validation();
            
            $uploadPath = WRITEPATH . 'uploads/materials/course_' . $course_id . '/';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $validationRules = [
                'material_file' => [
                    'label' => 'Material File',
                    'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,jpg,jpeg,png,gif,mp4,avi,mov]'
                ]
            ];
            
            $validationMessages = [
                'material_file' => [
                    'uploaded' => 'Please select a file to upload.',
                    'max_size' => 'File size cannot exceed 10MB.',
                    'ext_in' => 'Only PDF, Word, Excel, PowerPoint, text, image, and video files are allowed.'
                ]
            ];
            
            if ($validation->setRules($validationRules, $validationMessages)->withRequest($this->request)->run()) {
                
                $uploadedFile = $this->request->getFile('material_file');
                
                if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
                    
                    $originalName = $uploadedFile->getClientName();
                    $extension = $uploadedFile->getClientExtension();
                    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                    $timestamp = date('YmdHis');
                    $uniqueName = $baseName . '_' . $timestamp . '.' . $extension;
                    
                    try {
                        $uploadedFile->move($uploadPath, $uniqueName);
                        
                        $materialData = [
                            'course_id' => $course_id,
                            'file_name' => $originalName,
                            'file_path' => 'uploads/materials/course_' . $course_id . '/' . $uniqueName
                        ];
                        
                        $materialId = $this->materialModel->insertMaterial($materialData);
                          if ($materialId) {
                            // Step 7: Notify all enrolled students about new material
                            $db = \Config\Database::connect();
                            $enrolledStudents = $db->table('enrollments')
                                ->select('user_id')
                                ->where('course_id', $course_id)
                                ->get()
                                ->getResultArray();
                            
                            foreach ($enrolledStudents as $student) {
                                $this->createNotification(
                                    $student['user_id'],
                                    "New material '{$originalName}' has been uploaded to '{$course['title']}'"
                                );
                            }
                            
                            $this->session->setFlashdata('success', 'Material "' . $originalName . '" uploaded successfully!');
                            
                            if ($userRole === 'admin') {
                                return redirect()->to('/admin/manage_courses');
                            } elseif ($userRole === 'teacher') {
                                return redirect()->to('/teacher/courses');
                            } else {
                                return redirect()->to('/material/upload/' . $course_id);
                            }
                        } else {
                            if (file_exists($uploadPath . $uniqueName)) {
                                unlink($uploadPath . $uniqueName);
                            }
                            $this->session->setFlashdata('error', 'Failed to save material information. Please try again.');
                        }
                        
                    } catch (\Exception $e) {
                        log_message('error', 'Material upload error: ' . $e->getMessage());
                        $this->session->setFlashdata('error', 'Upload failed due to server error. Please try again.');
                    }
                    
                } else {
                    $this->session->setFlashdata('error', 'Invalid file or file has already been processed.');
                }
                
            } else {
                $this->session->setFlashdata('errors', $validation->getErrors());
            }
        }
        
        $existingMaterials = $this->materialModel->getMaterialsByCourse($course_id);        $data = [
            'title' => 'Upload Materials - ' . $course['title'],
            'course' => $course,
            'materials' => $existingMaterials,
            'course_id' => $course_id,
            'user' => [
                'userID' => $this->session->get('userID'),
                'name'   => $this->session->get('name'),
                'email'  => $this->session->get('email'),
                'role'   => $this->session->get('role')
            ]
        ];
        
        return view('material/upload', $data);
    }

    public function delete($material_id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }
        
        $userRole = $this->session->get('role');
        if ($userRole !== 'teacher' && $userRole !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied. Only teachers can delete materials.');
        }
        
        if (!$material_id || !is_numeric($material_id) || $material_id <= 0) {
            return redirect()->to('/dashboard')->with('error', 'Invalid material ID.');
        }
        
        $material_id = (int)$material_id;
        
        try {
            $material = $this->materialModel->find($material_id);
            if (!$material) {
                return redirect()->to('/dashboard')->with('error', 'Material not found.');
            }
            
            if (is_object($material)) {
                $material = (array) $material;
            }
            
            $filePath = WRITEPATH . $material['file_path'];
            $fileDeleted = true;
            
            if (file_exists($filePath)) {
                if (!unlink($filePath)) {
                    $fileDeleted = false;
                    log_message('warning', 'Failed to delete file: ' . $filePath);
                }
            }
            
            $recordDeleted = $this->materialModel->delete($material_id);
            
            if ($recordDeleted) {
                $message = $fileDeleted ? 
                    'Material "' . $material['file_name'] . '" deleted successfully!' :
                    'Material record deleted, but file could not be removed from server.';
                    
                $this->session->setFlashdata('success', $message);
            } else {
                $this->session->setFlashdata('error', 'Failed to delete material record.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Material deletion error: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Delete failed due to server error.');
        }
        
        $course_id = $material['course_id'] ?? null;
        if ($course_id) {
            return redirect()->to('/material/upload/' . $course_id);
        }
        return redirect()->to('/dashboard');
    }
    public function download($material_id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to download materials.');
        }
        
        if (!$material_id || !is_numeric($material_id) || $material_id <= 0) {
            return redirect()->to('/dashboard')->with('error', 'Invalid material ID.');
        }
        
        $material_id = (int)$material_id;
        $userID = $this->session->get('userID');
        $userRole = $this->session->get('role');
        
        try {
            $material = $this->materialModel->find($material_id);
            if (!$material) {
                return redirect()->to('/dashboard')->with('error', 'Material not found.');
            }
            
            if (is_object($material)) {
                $material = (array) $material;
            }
            
            $course_id = $material['course_id'];
            
            $canDownload = false;
            
            if ($userRole === 'admin' || $userRole === 'teacher') {
                $canDownload = true;
            }
            elseif ($userRole === 'student') {
                $isEnrolled = $this->enrollmentModel->isAlreadyEnrolled($userID, $course_id);
                if ($isEnrolled) {
                    $canDownload = true;
                }
            }
            
            if (!$canDownload) {
                return redirect()->to('/dashboard')->with('error', 'Access denied. You must be enrolled in this course to download materials.');
            }
            
            $filePath = WRITEPATH . $material['file_path'];
            if (!file_exists($filePath)) {
                log_message('error', 'Material file not found: ' . $filePath);
                return redirect()->to('/dashboard')->with('error', 'File not found on server.');
            }
            
            $fileName = $material['file_name'];
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
            
            $this->response->setHeader('Content-Type', $mimeType);
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
            $this->response->setHeader('Content-Length', (string)filesize($filePath));
            $this->response->setHeader('Cache-Control', 'no-cache, must-revalidate');
            $this->response->setHeader('Pragma', 'no-cache');
            
            return $this->response->setBody(file_get_contents($filePath));
            
        } catch (\Exception $e) {
            log_message('error', 'Material download error: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Download failed due to server error.');
        }
    }
    public function view($material_id)
    {
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login to view materials.');
        }

        if (!$material_id || !is_numeric($material_id) || $material_id <= 0) {
            return redirect()->to('/dashboard')->with('error', 'Invalid material ID.');
        }

        $material_id = (int)$material_id;
        $userID = $this->session->get('userID');
        $userRole = $this->session->get('role');

        try {
            $material = $this->materialModel->find($material_id);
            if (!$material) {
                return redirect()->to('/dashboard')->with('error', 'Material not found.');
            }

            if (is_object($material)) {
                $material = (array) $material;
            }

            $course_id = $material['course_id'];

            $canView = false;
            
            if ($userRole === 'admin' || $userRole === 'teacher') {
                $canView = true;
            }
            elseif ($userRole === 'student') {
                $isEnrolled = $this->enrollmentModel->isAlreadyEnrolled($userID, $course_id);
                if ($isEnrolled) {
                    $canView = true;
                }
            }

            if (!$canView) {
                return redirect()->to('/dashboard')->with('error', 'Access denied. You must be enrolled in this course to view materials.');
            }

            $filePath = WRITEPATH . $material['file_path'];
            if (!file_exists($filePath)) {
                log_message('error', 'Material file not found: ' . $filePath);
                return redirect()->to('/dashboard')->with('error', 'File not found on server.');
            }

            $fileName = $material['file_name'];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
            
            $inlineViewable = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            
            if (in_array($extension, $inlineViewable)) {
                $this->response->setHeader('Content-Type', $mimeType);
                $this->response->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"');
                $this->response->setHeader('Content-Length', (string)filesize($filePath));
                $this->response->setHeader('Cache-Control', 'private, max-age=3600');
                
                return $this->response->setBody(file_get_contents($filePath));
            } 
            else {
                $this->response->setHeader('Content-Type', $mimeType);
                $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"');
                $this->response->setHeader('Content-Length', (string)filesize($filePath));
                $this->response->setHeader('Cache-Control', 'no-cache, must-revalidate');
                $this->response->setHeader('Pragma', 'no-cache');

                return $this->response->setBody(file_get_contents($filePath));
            }

        } catch (\Exception $e) {
            log_message('error', 'Material view error: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'View failed due to server error.');
        }
    }
}
