<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $session;
    protected $validation;
    protected $db;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        $this->db = \Config\Database::connect();
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

    public function register()
    {
        if ($this->session->get('isLoggedIn') === true) {
            return redirect()->to(uri: base_url(relativePath: 'dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            
            $rules = [
                'name'             => 'required|min_length[3]|max_length[100]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'password'         => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]'
            ];

            $messages = [
                'name' => [
                    'required'   => 'Name is required.',
                    'min_length' => 'Name must be at least 3 characters long.',
                    'max_length' => 'Name cannot exceed 100 characters.'
                ],
                'email' => [
                    'required'    => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                    'is_unique'   => 'This email is already registered.'
                ],
                'password' => [
                    'required'   => 'Password is required.',
                    'min_length' => 'Password must be at least 6 characters long.'
                ],
                'password_confirm' => [
                    'required' => 'Password confirmation is required.',
                    'matches'  => 'Password confirmation does not match.'
                ]
            ];

            if ($this->validate(rules: $rules, messages: $messages)) {
                
                $hashedPassword = password_hash(password: $this->request->getPost(index: 'password'), algo: PASSWORD_DEFAULT);
                
                $userData = [
                    'name'       => $this->request->getPost(index: 'name'),
                    'email'      => $this->request->getPost(index: 'email'),
                    'password'   => $hashedPassword,
                    'role'       => 'student',
                    'created_at' => date(format: 'Y-m-d H:i:s'),
                    'updated_at' => date(format: 'Y-m-d H:i:s')
                ];

                $builder = $this->db->table(tableName: 'users');
                
                if ($builder->insert(set: $userData)) {
                    $this->session->setFlashdata(data: 'success', value: 'Registration successful! Please login with your credentials.');
                    return redirect()->to(uri: base_url(relativePath: 'login'));
                } else {
                    $this->session->setFlashdata(data: 'error', value: 'Registration failed. Please try again.');
                }
            } else {
                $this->session->setFlashdata(data: 'errors', value: $this->validation->getErrors());
            }
        }

        return view(name: 'auth/register');
    }

    public function login()
    {
        if ($this->session->get('isLoggedIn') === true) {
            return redirect()->to(uri: base_url(relativePath: 'dashboard'));
        }

        if ($this->request->getMethod() === 'POST') {
            
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required'
            ];

            $messages = [
                'email' => [
                    'required'    => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.'
                ],
                'password' => [
                    'required' => 'Password is required.'
                ]
            ];

            if ($this->validate(rules: $rules, messages: $messages)) {
                $email = $this->request->getPost(index: 'email');
                $password = $this->request->getPost(index: 'password');

                $builder = $this->db->table(tableName: 'users');
                $user = $builder->where(key: 'email', value: $email)->get()->getRowArray();

                if ($user && password_verify(password: $password, hash: $user['password'])) {
                    
                    $sessionData = [
                        'userID'     => $user['id'],
                        'name'       => $user['name'],
                        'email'      => $user['email'],
                        'role'       => $user['role'],
                        'isLoggedIn' => true
                    ];

                    $this->session->set(data: $sessionData);
                    
                    $this->session->setFlashdata(data: 'success', value: 'Welcome back, ' . $user['name'] . '!');
                    return redirect()->to(uri: base_url(relativePath: 'dashboard'));
                    
                } else {
                    $this->session->setFlashdata(data: 'error', value: 'Invalid email or password.');
                }
            } else {
                $this->session->setFlashdata(data: 'errors', value: $this->validation->getErrors());
            }
        }

        return view(name: 'auth/login');
    }

    public function logout()
    {
        $this->session->setFlashdata(data: 'success', value: 'You have been logged out successfully.');
        $this->session->destroy();
        return redirect()->to(uri: base_url(relativePath: 'login'));
    }

    public function dashboard()
    {
        if ($this->session->get('isLoggedIn') !== true) {
            $this->session->setFlashdata(data: 'error', value: 'Please login to access the dashboard.');
            return redirect()->to(uri: base_url(relativePath: 'login'));
        }

        $userRole = $this->session->get(key: 'role');
        
        $baseData = [
            'user' => [
                'userID' => $this->session->get(key: 'userID'),
                'name'   => $this->session->get(key: 'name'),
                'email'  => $this->session->get(key: 'email'),
                'role'   => $this->session->get(key: 'role')
            ]
        ];

        switch ($userRole) {
            case 'admin':
                $totalUsers = $this->db->table('users')->countAll();
                $totalAdmins = $this->db->table('users')->where('role', 'admin')->countAllResults();
                $totalTeachers = $this->db->table('users')->where('role', 'teacher')->countAllResults();
                $totalStudents = $this->db->table('users')->where('role', 'student')->countAllResults();
                $recentUsers = $this->db->table('users')->orderBy('created_at', 'DESC')->limit(5)->get()->getResultArray();

                $dashboardData = array_merge($baseData, [
                    'title' => 'Admin Dashboard - MARUHOM LMS',
                    'totalUsers' => $totalUsers,
                    'totalAdmins' => $totalAdmins,
                    'totalTeachers' => $totalTeachers,
                    'totalStudents' => $totalStudents,
                    'recentUsers' => $recentUsers
                ]);
                return view('auth/dashboard', $dashboardData);
                
            case 'teacher':
                $teacherID = $this->session->get('userID');
                
                // Get all courses assigned to this teacher
                $assignedCoursesBuilder = $this->db->table('courses');
                $assignedCourses = $assignedCoursesBuilder
                    ->where("JSON_CONTAINS(instructor_ids, '\"$teacherID\"')", null, false)
                    ->get()
                    ->getResultArray();
                
                $totalCourses = count($assignedCourses);
                
                // Get total students across all assigned courses
                $totalStudents = 0;
                $courseIds = [];
                foreach ($assignedCourses as $course) {
                    $courseIds[] = $course['id'];
                }
                
                if (!empty($courseIds)) {
                    $totalStudents = $this->db->table('enrollments')
                        ->whereIn('course_id', $courseIds)
                        ->countAllResults();
                }
                
                // Get courses by status
                $activeCourses = 0;
                $draftCourses = 0;
                $completedCourses = 0;
                foreach ($assignedCourses as $course) {
                    if ($course['status'] === 'active') $activeCourses++;
                    elseif ($course['status'] === 'draft') $draftCourses++;
                    elseif ($course['status'] === 'completed') $completedCourses++;
                }
                
                // Get recent enrollments for teacher's courses
                $recentEnrollments = [];
                if (!empty($courseIds)) {
                    $recentEnrollments = $this->db->table('enrollments')
                        ->select('enrollments.*, users.name as student_name, users.email as student_email, courses.title as course_title, courses.course_code')
                        ->join('users', 'enrollments.user_id = users.id')
                        ->join('courses', 'enrollments.course_id = courses.id')
                        ->whereIn('enrollments.course_id', $courseIds)
                        ->orderBy('enrollments.enrollment_date', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
                }
                
                // Get total materials uploaded
                $totalMaterials = 0;
                if (!empty($courseIds)) {
                    $totalMaterials = $this->db->table('materials')
                        ->whereIn('course_id', $courseIds)
                        ->countAllResults();
                }
                
                $dashboardData = array_merge($baseData, [
                    'title' => 'Teacher Dashboard - MARUHOM LMS',
                    'totalCourses' => $totalCourses,
                    'totalStudents' => $totalStudents,
                    'activeCourses' => $activeCourses,
                    'draftCourses' => $draftCourses,
                    'completedCourses' => $completedCourses,
                    'totalMaterials' => $totalMaterials,
                    'recentEnrollments' => $recentEnrollments,
                    'assignedCourses' => $assignedCourses
                ]);
                return view('auth/dashboard', $dashboardData);
                
            case 'student':
                $enrollmentModel = new \App\Models\EnrollmentModel();
                $materialModel = new \App\Models\MaterialModel();
                
                $userId = $this->session->get('userID');
                
                // Get enrolled courses with details
                $enrolledCoursesData = $enrollmentModel->getUserEnrollments($userId);
                $enrolledCoursesCount = count($enrolledCoursesData);
                
                // Get all active courses
                $allActiveCourses = $this->db->table('courses')
                    ->where('status', 'active')
                    ->orderBy('created_at', 'DESC')
                    ->get()
                    ->getResultArray();
                
                // Filter available courses (not enrolled)
                $enrolledCourseIds = array_column($enrolledCoursesData, 'course_id');
                $availableCourses = array_filter($allActiveCourses, function($course) use ($enrolledCourseIds) {
                    return !in_array($course['id'], $enrolledCourseIds);
                });
                
                // Add enrollment stats and instructor names to available courses
                foreach ($availableCourses as &$course) {
                    $course['current_enrollments'] = $this->db->table('enrollments')
                        ->where('course_id', $course['id'])
                        ->countAllResults();
                    $course['spots_remaining'] = $course['max_students'] - $course['current_enrollments'];
                    $course['is_full'] = $course['max_students'] > 0 && $course['current_enrollments'] >= $course['max_students'];
                    
                    // Get instructor names from instructor_ids JSON field
                    $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
                    if (!empty($instructorIds)) {
                        $instructors = $this->db->table('users')
                            ->select('name')
                            ->whereIn('id', $instructorIds)
                            ->get()
                            ->getResultArray();
                        $course['instructor_name'] = !empty($instructors) ? implode(', ', array_column($instructors, 'name')) : 'No instructor assigned';
                    } else {
                        $course['instructor_name'] = 'No instructor assigned';
                    }
                }
                
                // Get total available materials for enrolled courses
                $totalMaterials = 0;
                if (!empty($enrolledCourseIds)) {
                    $totalMaterials = $this->db->table('materials')
                        ->whereIn('course_id', $enrolledCourseIds)
                        ->countAllResults();
                }
                
                // Get recent materials uploaded for enrolled courses
                $recentMaterials = [];
                if (!empty($enrolledCourseIds)) {
                    $recentMaterials = $this->db->table('materials')
                        ->select('materials.*, courses.title as course_title, courses.course_code')
                        ->join('courses', 'materials.course_id = courses.id')
                        ->whereIn('materials.course_id', $enrolledCourseIds)
                        ->orderBy('materials.created_at', 'DESC')
                        ->limit(5)
                        ->get()
                        ->getResultArray();
                }
                
                // Count courses by status for enrolled courses
                $activeCourses = 0;
                $completedCourses = 0;
                foreach ($enrolledCoursesData as $enrollment) {
                    if ($enrollment['course_status'] === 'active') $activeCourses++;
                    elseif ($enrollment['course_status'] === 'completed') $completedCourses++;
                }
                
                // Calculate average progress (placeholder for future implementation)
                $averageProgress = $enrolledCoursesCount > 0 ? 0 : 0;
                
                $dashboardData = array_merge($baseData, [
                    'title' => 'Student Dashboard - MARUHOM LMS',
                    'enrolledCourses' => $enrolledCoursesCount,
                    'enrolledCoursesData' => $enrolledCoursesData,
                    'availableCourses' => array_values($availableCourses),
                    'totalAvailableCourses' => count($availableCourses),
                    'activeCourses' => $activeCourses,
                    'completedCourses' => $completedCourses,
                    'totalMaterials' => $totalMaterials,
                    'recentMaterials' => $recentMaterials,
                    'averageProgress' => $averageProgress,
                    'completedAssignments' => 0, // Placeholder for future assignment feature
                    'pendingAssignments' => 0    // Placeholder for future assignment feature
                ]);
                return view('auth/dashboard', $dashboardData);
                
            default:
                return view('auth/dashboard', $baseData);
        }
    }

    public function manageUsers()
    {
        if ($this->session->get('isLoggedIn') !== true) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }        
        
        if ($this->session->get('role') !== 'admin') {
            $this->session->setFlashdata('error', 'Access denied. You do not have permission to access this page.');
            $userRole = $this->session->get('role');
            if ($userRole === 'teacher') {
                return redirect()->to(base_url('teacher/courses'));
            } elseif ($userRole === 'student') {
                return redirect()->to(base_url('student/courses'));
            }
            return redirect()->to(base_url('dashboard'));
        }

        $action = $this->request->getGet('action');
        $userID = $this->request->getGet('id');
        $currentAdminID = $this->session->get('userID');
        
        if ($action === 'create' && $this->request->getMethod() === 'POST') {
            $rules = [
                'name'     => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-ZñÑ\s]+$/]',
                'email'    => 'required|valid_email|is_unique[users.email]|regex_match[/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]',
                'password' => 'required|min_length[6]',
                'role'     => 'required|in_list[admin,teacher,student]'
            ];

            $messages = [
                'name' => [
                    'required'    => 'Name is required.',
                    'min_length'  => 'Name must be at least 3 characters long.',
                    'max_length'  => 'Name cannot exceed 100 characters.',
                    'regex_match' => 'Name can only contain letters (including ñ/Ñ) and spaces.'
                ],
                'email' => [
                    'required'    => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                    'is_unique'   => 'This email is already registered.',
                    'regex_match'  => 'Invalid email! Email should be like "aslainie@gmail.com"'
                ],
                'password' => [
                    'required'   => 'Password is required.',
                    'min_length' => 'Password must be at least 6 characters long.'
                ],
                'role' => [
                    'required' => 'Role is required.',
                    'in_list'  => 'Invalid role selected.'
                ]
            ];

            if ($this->validate($rules, $messages)) {
                $userData = [
                    'name'       => $this->request->getPost('name'),
                    'email'      => $this->request->getPost('email'),
                    'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'role'       => $this->request->getPost('role'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $builder = $this->db->table('users');
                if ($builder->insert($userData)) {
                    $creationActivity = [
                        'type' => 'user_creation',
                        'icon' => '➕',
                        'title' => 'New User Created',
                        'description' => esc($userData['name']) . ' (' . ucfirst($userData['role']) . ') account was created by admin',
                        'time' => date('Y-m-d H:i:s'),
                        'user_name' => esc($userData['name']),
                        'user_role' => $userData['role'],
                        'created_by' => $this->session->get('name')
                    ];

                    $creationActivities = $this->session->get('creation_activities') ?? [];
                    array_unshift($creationActivities, $creationActivity);
                    $creationActivities = array_slice($creationActivities, 0, 10);
                    $this->session->set('creation_activities', $creationActivities);

                    $this->session->setFlashdata('success', 'User created successfully!');
                    return redirect()->to(base_url('admin/manage_users'));
                } else {
                    $this->session->setFlashdata('error', 'Failed to create user. Please try again.');
                }
            } else {
                $this->session->setFlashdata('errors', $this->validation->getErrors());
            }
        }
        
        if ($action === 'edit' && $userID) {
            $builder = $this->db->table('users');
            $userToEdit = $builder->where('id', $userID)->get()->getRowArray();

            if (!$userToEdit) {
                $this->session->setFlashdata('error', 'User not found.');
                return redirect()->to(base_url('admin/manage_users'));
            }

            if ($userToEdit['id'] == $currentAdminID) {
                $this->session->setFlashdata('error', 'You cannot edit your own account.');
                return redirect()->to(base_url('admin/manage_users'));
            }

            if ($this->request->getMethod() === 'POST') {
                $rules = [
                    'name' => 'required|min_length[3]|max_length[100]|regex_match[/^[a-zA-ZñÑ\s]+$/]',
                    'email' => "required|valid_email|is_unique[users.email,id,{$userID}]|regex_match[/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/]",
                    'role' => 'required|in_list[admin,teacher,student]'
                ];

                if ($this->request->getPost('password')) {
                    $rules['password'] = 'min_length[6]';
                }

                $messages = [
                    'name' => [
                        'required'    => 'Name is required.',
                        'min_length'  => 'Name must be at least 3 characters long.',
                        'max_length'  => 'Name cannot exceed 100 characters.',
                        'regex_match' => 'Name can only contain letters (including ñ/Ñ) and spaces.'
                    ],
                    'email' => [
                        'required'    => 'Email is required.',
                        'valid_email' => 'Please enter a valid email address.',
                        'is_unique'   => 'This email is already registered.',
                        'regex_match'  => 'Invalid email! Email should be like "aslainie@gmail.com"'
                    ],
                    'role' => [
                        'required' => 'Role is required.',
                        'in_list'  => 'Invalid role selected.'
                    ],
                    'password' => [
                        'min_length' => 'Password must be at least 6 characters long.'
                    ]
                ];

                if ($this->validate($rules, $messages)) {
                    $updateData = [
                        'name'       => $this->request->getPost('name'),
                        'email'      => $this->request->getPost('email'),
                        'role'       => $this->request->getPost('role'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    if ($this->request->getPost('password')) {
                        $updateData['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
                    }

                    if ($builder->where('id', $userID)->update($updateData)) {
                        $updateActivity = [
                            'type' => 'user_update',
                            'icon' => '✏️',
                            'title' => 'User Account Updated',
                            'description' => esc($updateData['name']) . ' (' . ucfirst($updateData['role']) . ') account was updated by admin',
                            'time' => date('Y-m-d H:i:s'),
                            'user_name' => esc($updateData['name']),
                            'user_role' => $updateData['role'],
                            'updated_by' => $this->session->get('name')
                        ];

                        $updateActivities = $this->session->get('update_activities') ?? [];
                        array_unshift($updateActivities, $updateActivity);
                        $updateActivities = array_slice($updateActivities, 0, 10);
                        $this->session->set('update_activities', $updateActivities);

                        $this->session->setFlashdata('success', 'User updated successfully!');
                        return redirect()->to(base_url('admin/manage_users'));
                    } else {
                        $this->session->setFlashdata('error', 'Failed to update user. Please try again.');
                    }
                } else {
                    $this->session->setFlashdata('errors', $this->validation->getErrors());
                }
            }

            $users = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();
            $data = [
                'user' => [
                    'userID' => $this->session->get('userID'),
                    'name'   => $this->session->get('name'),
                    'email'  => $this->session->get('email'),
                    'role'   => $this->session->get('role')
                ],
                'title' => 'Edit User - Admin Dashboard',
                'users' => $users,
                'currentAdminID' => $currentAdminID,
                'editUser' => $userToEdit,
                'showCreateForm' => false,
                'showEditForm' => true
            ];
            return view('admin/manage_users', $data);
        }
        
        if ($action === 'delete' && $userID) {
            $builder = $this->db->table('users');
            $userToDelete = $builder->where('id', $userID)->get()->getRowArray();

            if (!$userToDelete) {
                $this->session->setFlashdata('error', 'User not found.');
                return redirect()->to(base_url('admin/manage_users'));
            }

            if ($userToDelete['id'] == $currentAdminID) {
                $this->session->setFlashdata('error', 'You cannot delete your own account.');
                return redirect()->to(base_url('admin/manage_users'));
            }

            if ($userToDelete['role'] === 'admin') {
                $this->session->setFlashdata('error', 'You cannot delete another admin account.');
                return redirect()->to(base_url('admin/manage_users'));
            }

            $deletionActivity = [
                'type' => 'user_deletion',
                'icon' => '🗑️',
                'title' => 'User Account Deleted',
                'description' => esc($userToDelete['name']) . ' (' . ucfirst($userToDelete['role']) . ') account was removed from the system',
                'time' => date('Y-m-d H:i:s'),
                'user_name' => esc($userToDelete['name']),
                'user_role' => $userToDelete['role'],
                'deleted_by' => $this->session->get('name')
            ];

            $deletionActivities = $this->session->get('deletion_activities') ?? [];
            array_unshift($deletionActivities, $deletionActivity);
            $deletionActivities = array_slice($deletionActivities, 0, 10);
            $this->session->set('deletion_activities', $deletionActivities);

            $deleteBuilder = $this->db->table('users');
            if ($deleteBuilder->where('id', $userID)->delete()) {
                $this->session->setFlashdata('success', 'User deleted successfully!');
            } else {
                $this->session->setFlashdata('error', 'Failed to delete user. Please try again.');
            }

            return redirect()->to(base_url('admin/manage_users'));
        }
        
        $builder = $this->db->table('users');
        $users = $builder->orderBy('created_at', 'DESC')->get()->getResultArray();

        $data = [
            'user' => [
                'userID' => $this->session->get('userID'),
                'name'   => $this->session->get('name'),
                'email'  => $this->session->get('email'),
                'role'   => $this->session->get('role')
            ],
            'title' => 'Manage Users - Admin Dashboard',
            'users' => $users,
            'currentAdminID' => $currentAdminID,
            'editUser' => null,
            'showCreateForm' => $this->request->getGet('create') === 'true' || ($action === 'create' && $this->request->getMethod() !== 'POST'),
            'showEditForm' => false
        ];
          
        return view('admin/manage_users', $data);
    }

    public function manageCourses()
    {
        if ($this->session->get('isLoggedIn') !== true) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));        
        }
        
        if ($this->session->get('role') !== 'admin') {
            $this->session->setFlashdata('error', 'Access denied. You do not have permission to access this page.');
            $userRole = $this->session->get('role');
            if ($userRole === 'teacher') {
                return redirect()->to(base_url('teacher/courses'));
            } elseif ($userRole === 'student') {
                return redirect()->to(base_url('student/courses'));
            }
            return redirect()->to(base_url('dashboard'));
        }

        $action = $this->request->getGet('action');
        $courseID = $this->request->getGet('id');
        
        if ($action === 'create' && $this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[3]|max_length[200]|regex_match[/^[a-zA-Z\s\-\.]+$/]',
                'course_code' => 'required|min_length[3]|max_length[20]|regex_match[/^[A-Z]+\-?[0-9]+$/]|is_unique[courses.course_code]',
                'instructor_ids' => 'permit_empty',
                'category' => 'permit_empty|max_length[100]|regex_match[/^[a-zA-Z\s\-\.]+$/]',
                'credits' => 'permit_empty|integer|greater_than[0]|less_than[10]',
                'duration_weeks' => 'permit_empty|integer|greater_than[0]|less_than[100]',
                'max_students' => 'permit_empty|integer|greater_than[0]|less_than[1000]',
                'start_date' => 'permit_empty|valid_date[Y-m-d]',
                'end_date' => 'permit_empty|valid_date[Y-m-d]',
                'status' => 'required|in_list[draft,active,completed,cancelled]',
                'description' => 'permit_empty|regex_match[/^[a-zA-Z0-9\s\.\,\:\;\!\?\n\r•\-]+$/]'
            ];

            $messages = [
                'title' => [
                    'required' => 'Course title is required.',
                    'min_length' => 'Course title must be at least 3 characters long.',
                    'max_length' => 'Course title cannot exceed 200 characters.',
                    'regex_match' => 'Course title can only contain letters, spaces, hyphens, and periods.'
                ],
                'course_code' => [
                    'required' => 'Course code is required.',
                    'min_length' => 'Course code must be at least 3 characters long.',
                    'max_length' => 'Course code cannot exceed 20 characters.',
                    'regex_match' => 'Course code must start with letters followed by numbers (e.g., CS101, MATH201).',
                    'is_unique' => 'This course code is already in use.'
                ],
                'instructor_id' => [
                    'integer' => 'Invalid instructor selected.',
                    'greater_than' => 'Please select a valid instructor if choosing one.'
                ],
                'category' => [
                    'max_length' => 'Category cannot exceed 100 characters.',
                    'regex_match' => 'Category can only contain letters, spaces, hyphens, and periods.'
                ],
                'credits' => [
                    'integer' => 'Credits must be a valid number.',
                    'greater_than' => 'Credits must be greater than 0.',
                    'less_than' => 'Credits cannot exceed 9.'
                ],
                'duration_weeks' => [
                    'integer' => 'Duration must be a valid number.',
                    'greater_than' => 'Duration must be greater than 0 weeks.',
                    'less_than' => 'Duration cannot exceed 99 weeks.'
                ],
                'max_students' => [
                    'integer' => 'Max students must be a valid number.',
                    'greater_than' => 'Max students must be greater than 0.',
                    'less_than' => 'Max students cannot exceed 999.'
                ],
                'start_date' => [
                    'valid_date' => 'Please enter a valid start date.'
                ],
                'end_date' => [
                    'valid_date' => 'Please enter a valid end date.'
                ],
                'status' => [
                    'required' => 'Course status is required.',
                    'in_list' => 'Invalid course status selected.'
                ],
                'description' => [
                    'regex_match' => 'Description can only contain letters, numbers, spaces, hyphens, and basic punctuation (periods, commas, colons, semicolons, exclamation marks, question marks, and bullet points •).'
                ]
            ];
            
            if ($this->validate($rules, $messages)) {
                $instructorIds = $this->request->getPost('instructor_ids');
                $finalInstructorIds = [];
                
                if ($instructorIds && is_array($instructorIds)) {
                    foreach ($instructorIds as $instructorId) {
                        if (is_numeric($instructorId) && $instructorId > 0) {
                            $finalInstructorIds[] = (int)$instructorId;
                        }
                    }
                }

                $courseData = [
                    'title' => $this->request->getPost('title'),
                    'course_code' => $this->request->getPost('course_code'),
                    'instructor_ids' => json_encode($finalInstructorIds),
                    'category' => $this->request->getPost('category') ?: null,
                    'credits' => $this->request->getPost('credits') ?: 3,
                    'duration_weeks' => $this->request->getPost('duration_weeks') ?: 16,
                    'max_students' => $this->request->getPost('max_students') ?: 30,
                    'start_date' => $this->request->getPost('start_date') ?: null,
                    'end_date' => $this->request->getPost('end_date') ?: null,
                    'status' => $this->request->getPost('status'),
                    'description' => $this->request->getPost('description'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $coursesBuilder = $this->db->table('courses');
                if ($coursesBuilder->insert($courseData)) {
                    $creationActivity = [
                        'type' => 'course_creation',
                        'icon' => '📚',
                        'title' => 'New Course Created',
                        'description' => 'Course "' . esc($courseData['title']) . '" (' . esc($courseData['course_code']) . ') was created by admin',
                        'time' => date('Y-m-d H:i:s'),
                        'course_title' => esc($courseData['title']),
                        'course_code' => esc($courseData['course_code']),
                        'created_by' => $this->session->get('name')
                    ];

                    $creationActivities = $this->session->get('creation_activities') ?? [];
                    array_unshift($creationActivities, $creationActivity);
                    $creationActivities = array_slice($creationActivities, 0, 10);
                    $this->session->set('creation_activities', $creationActivities);

                    $this->session->setFlashdata('success', 'Course created successfully!');
                    return redirect()->to(base_url('admin/manage_courses'));
                } else {
                    $this->session->setFlashdata('error', 'Failed to create course. Please try again.');
                }
            } else {
                $this->session->setFlashdata('errors', $this->validation->getErrors());
            }
        }
        
        if ($action === 'edit' && $courseID) {
            $coursesBuilder = $this->db->table('courses');
            $courseToEdit = $coursesBuilder->where('id', $courseID)->get()->getRowArray();

            if (!$courseToEdit) {
                $this->session->setFlashdata('error', 'Course not found.');
                return redirect()->to(base_url('admin/manage_courses'));
            }
            
            if ($this->request->getMethod() === 'POST') {
                $rules = [
                    'title' => 'required|min_length[3]|max_length[200]|regex_match[/^[a-zA-Z\s\-\.]+$/]',
                    'course_code' => "required|min_length[3]|max_length[20]|regex_match[/^[A-Z]+\-?[0-9]+$/]|is_unique[courses.course_code,id,{$courseID}]",
                    'instructor_ids' => 'permit_empty',
                    'category' => 'permit_empty|max_length[100]|regex_match[/^[a-zA-Z\s\-\.]+$/]',
                    'credits' => 'permit_empty|integer|greater_than[0]|less_than[10]',
                    'duration_weeks' => 'permit_empty|integer|greater_than[0]|less_than[100]',
                    'max_students' => 'permit_empty|integer|greater_than[0]|less_than[1000]',
                    'start_date' => 'permit_empty|valid_date[Y-m-d]',
                    'end_date' => 'permit_empty|valid_date[Y-m-d]',
                    'status' => 'required|in_list[draft,active,completed,cancelled]',
                    'description' => 'permit_empty|regex_match[/^[a-zA-Z0-9\s\.\,\:\;\!\?\n\r•\-]+$/]'
                ];

                $messages = [
                    'title' => [
                        'required' => 'Course title is required.',
                        'min_length' => 'Course title must be at least 3 characters long.',
                        'max_length' => 'Course title cannot exceed 200 characters.',
                        'regex_match' => 'Course title can only contain letters, spaces, hyphens, and periods.'
                    ],
                    'course_code' => [
                        'required' => 'Course code is required.',
                        'min_length' => 'Course code must be at least 3 characters long.',
                        'max_length' => 'Course code cannot exceed 20 characters.',
                        'regex_match' => 'Course code must start with letters followed by numbers (e.g., CS101, MATH201).',
                        'is_unique' => 'This course code is already in use.'
                    ],
                    'instructor_id' => [
                        'integer' => 'Invalid instructor selected.',
                        'greater_than' => 'Please select a valid instructor if choosing one.'
                    ],
                    'category' => [
                        'max_length' => 'Category cannot exceed 100 characters.',
                        'regex_match' => 'Category can only contain letters, spaces, hyphens, and periods.'
                    ],
                    'status' => [
                        'required' => 'Course status is required.',
                        'in_list' => 'Invalid course status selected.'
                    ],
                    'description' => [
                        'regex_match' => 'Description can only contain letters, numbers, spaces, hyphens, and basic punctuation (periods, commas, colons, semicolons, exclamation marks, question marks, and bullet points •).'
                    ]
                ];
                
                if ($this->validate($rules, $messages)) {
                    $instructorIds = $this->request->getPost('instructor_ids');
                    $finalInstructorIds = [];
                    
                    if ($instructorIds && is_array($instructorIds)) {
                        foreach ($instructorIds as $instructorId) {
                            if (is_numeric($instructorId) && $instructorId > 0) {
                                $finalInstructorIds[] = (int)$instructorId;
                            }
                        }
                    }

                    $updateData = [
                        'title' => $this->request->getPost('title'),
                        'course_code' => $this->request->getPost('course_code'),
                        'instructor_ids' => json_encode($finalInstructorIds),
                        'category' => $this->request->getPost('category') ?: null,
                        'credits' => $this->request->getPost('credits') ?: 3,
                        'duration_weeks' => $this->request->getPost('duration_weeks') ?: 16,
                        'max_students' => $this->request->getPost('max_students') ?: 30,
                        'start_date' => $this->request->getPost('start_date') ?: null,
                        'end_date' => $this->request->getPost('end_date') ?: null,
                        'status' => $this->request->getPost('status'),
                        'description' => $this->request->getPost('description'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    if ($coursesBuilder->where('id', $courseID)->update($updateData)) {
                        $updateActivity = [
                            'type' => 'course_update',
                            'icon' => '✏️',
                            'title' => 'Course Updated',
                            'description' => 'Course "' . esc($updateData['title']) . '" (' . esc($updateData['course_code']) . ') was updated by admin',
                            'time' => date('Y-m-d H:i:s'),
                            'course_title' => esc($updateData['title']),
                            'course_code' => esc($updateData['course_code']),
                            'updated_by' => $this->session->get('name')
                        ];

                        $updateActivities = $this->session->get('update_activities') ?? [];
                        array_unshift($updateActivities, $updateActivity);
                        $updateActivities = array_slice($updateActivities, 0, 10);
                        $this->session->set('update_activities', $updateActivities);

                        $this->session->setFlashdata('success', 'Course updated successfully!');
                        return redirect()->to(base_url('admin/manage_courses'));
                    } else {
                        $this->session->setFlashdata('error', 'Failed to update course. Please try again.');
                    }
                } else {
                    $this->session->setFlashdata('errors', $this->validation->getErrors());
                }
            }
            
            $courses = $coursesBuilder
                ->select('courses.*')
                ->orderBy('courses.created_at', 'DESC')
                ->get()
                ->getResultArray();

            foreach ($courses as &$course) {
                $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
                if (!empty($instructorIds)) {
                    $instructorNames = $this->db->table('users')
                        ->select('name')
                        ->whereIn('id', $instructorIds)
                        ->where('role', 'teacher')
                        ->get()
                        ->getResultArray();
                    $course['instructor_name'] = implode(', ', array_column($instructorNames, 'name'));
                } else {
                    $course['instructor_name'] = 'No instructor assigned';
                }
            }

            $teachersBuilder = $this->db->table('users');
            $teachers = $teachersBuilder->where('role', 'teacher')->orderBy('name', 'ASC')->get()->getResultArray();

            $data = [
                'user' => [
                    'userID' => $this->session->get('userID'),
                    'name'   => $this->session->get('name'),
                    'email'  => $this->session->get('email'),
                    'role'   => $this->session->get('role')
                ],
                'title' => 'Edit Course - Admin Dashboard',
                'courses' => $courses,
                'teachers' => $teachers,
                'editCourse' => $courseToEdit,
                'showCreateForm' => false,
                'showEditForm' => true
            ];
            return view('admin/manage_courses', $data);
        }
        
        if ($action === 'delete' && $courseID) {
            $coursesBuilder = $this->db->table('courses');
            $courseToDelete = $coursesBuilder->where('id', $courseID)->get()->getRowArray();

            if (!$courseToDelete) {
                $this->session->setFlashdata('error', 'Course not found.');
                return redirect()->to(base_url('admin/manage_courses'));
            }

            $deletionActivity = [
                'type' => 'course_deletion',
                'icon' => '🗑️',
                'title' => 'Course Deleted',
                'description' => 'Course "' . esc($courseToDelete['title']) . '" (' . esc($courseToDelete['course_code']) . ') was removed from the system',
                'time' => date('Y-m-d H:i:s'),
                'course_title' => esc($courseToDelete['title']),
                'course_code' => esc($courseToDelete['course_code']),
                'deleted_by' => $this->session->get('name')
            ];

            $deletionActivities = $this->session->get('deletion_activities') ?? [];
            array_unshift($deletionActivities, $deletionActivity);
            $deletionActivities = array_slice($deletionActivities, 0, 10);
            $this->session->set('deletion_activities', $deletionActivities);

            $deleteBuilder = $this->db->table('courses');
            if ($deleteBuilder->where('id', $courseID)->delete()) {
                $this->session->setFlashdata('success', 'Course deleted successfully!');
            } else {
                $this->session->setFlashdata('error', 'Failed to delete course. Please try again.');
            }

            return redirect()->to(base_url('admin/manage_courses'));
        }
          
        $coursesBuilder = $this->db->table('courses');
        $courses = $coursesBuilder
            ->select('courses.*')
            ->orderBy('courses.created_at', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($courses as &$course) {
            $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
            if (!empty($instructorIds)) {
                $instructorNames = $this->db->table('users')
                    ->select('name')
                    ->whereIn('id', $instructorIds)
                    ->where('role', 'teacher')
                    ->get()
                    ->getResultArray();
                $course['instructor_name'] = implode(', ', array_column($instructorNames, 'name'));
            } else {
                $course['instructor_name'] = 'No instructor assigned';
            }
        }

        $teachersBuilder = $this->db->table('users');
        $teachers = $teachersBuilder->where('role', 'teacher')->orderBy('name', 'ASC')->get()->getResultArray();

        $data = [
            'user' => [
                'userID' => $this->session->get('userID'),
                'name'   => $this->session->get('name'),
                'email'  => $this->session->get('email'),
                'role'   => $this->session->get('role')
            ],
            'title' => 'Manage Courses - Admin Dashboard',
            'courses' => $courses,
            'teachers' => $teachers,
            'editCourse' => null,
            'showCreateForm' => $this->request->getGet('create') === 'true' || ($action === 'create' && $this->request->getMethod() !== 'POST'),
            'showEditForm' => false
        ];
        
        return view('admin/manage_courses', $data);
    }

    public function teacherCourses()
    {
        if ($this->session->get('isLoggedIn') !== true) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }
        
        if ($this->session->get('role') !== 'teacher') {
            $this->session->setFlashdata('error', 'Access denied. Only teachers can access this page.');
            $userRole = $this->session->get('role');
            if ($userRole === 'admin') {
                return redirect()->to(base_url('admin/manage_courses'));
            } elseif ($userRole === 'student') {
                return redirect()->to(base_url('student/courses'));
            }
            return redirect()->to(base_url('dashboard'));
        }

        $teacherID = $this->session->get('userID');
        
        if ($this->request->getMethod() === 'POST' && $this->request->getPost('action') === 'assign_course') {
            return $this->handleCourseAssignmentRequest($teacherID);
        }
        
        if ($this->request->getMethod() === 'POST' && $this->request->getPost('action') === 'unassign_course') {
            return $this->handleCourseUnassignmentRequest($teacherID);
        }

        $assignedCoursesBuilder = $this->db->table('courses');
        $assignedCourses = $assignedCoursesBuilder
            ->select('courses.*, 
                      COUNT(enrollments.id) as enrolled_students')
            ->join('enrollments', 'courses.id = enrollments.course_id', 'left')
            ->where("JSON_CONTAINS(courses.instructor_ids, '\"$teacherID\"')", null, false)
            ->groupBy('courses.id')
            ->orderBy('courses.created_at', 'DESC')            ->get()
            ->getResultArray();

        foreach ($assignedCourses as &$course) {
            $studentsBuilder = $this->db->table('enrollments');
            $course['students'] = $studentsBuilder
                ->select('users.id as user_id, users.name, users.email, enrollments.enrollment_date')
                ->join('users', 'enrollments.user_id = users.id')
                ->where('enrollments.course_id', $course['id'])
                ->where('users.role', 'student')
                ->orderBy('users.name', 'ASC')
                ->get()
                ->getResultArray();
                
            $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
            if (is_array($instructorIds) && count($instructorIds) > 1) {
                $otherInstructorIds = array_filter($instructorIds, function($id) use ($teacherID) {
                    return $id != $teacherID;
                });
                
                if (!empty($otherInstructorIds)) {
                    $coInstructorsBuilder = $this->db->table('users');
                    $course['co_instructors'] = $coInstructorsBuilder
                        ->select('id, name, email')
                        ->whereIn('id', $otherInstructorIds)
                        ->where('role', 'teacher')
                        ->orderBy('name', 'ASC')
                        ->get()
                        ->getResultArray();
                } else {
                    $course['co_instructors'] = [];
                }
            } else {
                $course['co_instructors'] = [];
            }
        }
        $availableCoursesBuilder = $this->db->table('courses');
        $allActiveCourses = $availableCoursesBuilder
            ->select('courses.*, COUNT(enrollments.id) as enrolled_students')
            ->join('enrollments', 'courses.id = enrollments.course_id', 'left')
            ->where('courses.status', 'active')
            ->groupBy('courses.id')
            ->orderBy('courses.created_at', 'DESC')
            ->get()
            ->getResultArray();
        
        $availableCourses = [];
        foreach ($allActiveCourses as $course) {
            $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
            if (!is_array($instructorIds)) {
                $instructorIds = [];
            }
            
            if (!in_array($teacherID, $instructorIds)) {
                $availableCourses[] = $course;
            }
        }
        
        foreach ($availableCourses as &$course) {
            if (isset($course['start_date']) && $course['start_date']) {
                $course['start_date_formatted'] = date('M j, Y', strtotime($course['start_date']));
            } else {
                $course['start_date_formatted'] = 'TBA';
            }
            
            if (isset($course['end_date']) && $course['end_date']) {
                $course['end_date_formatted'] = date('M j, Y', strtotime($course['end_date']));
            } else {
                $course['end_date_formatted'] = 'TBA';
            }
            
            $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
            if (is_array($instructorIds) && !empty($instructorIds)) {
                $existingInstructorsBuilder = $this->db->table('users');
                $course['existing_instructors'] = $existingInstructorsBuilder
                    ->select('id, name, email')
                    ->whereIn('id', $instructorIds)
                    ->where('role', 'teacher')
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->getResultArray();
            } else {
                $course['existing_instructors'] = [];
            }
        }

        $data = [
            'user' => [
                'userID' => $this->session->get('userID'),
                'name'   => $this->session->get('name'),
                'email'  => $this->session->get('email'),
                'role'   => $this->session->get('role')
            ],
            'title' => 'My Courses - Teacher Dashboard',
            'assignedCourses' => $assignedCourses,
            'availableCourses' => $availableCourses
        ];
        
        return view('teacher/courses', $data);
    }
    
    public function studentCourses()
    {
        if ($this->session->get('isLoggedIn') !== true) {
            $this->session->setFlashdata('error', 'Please login to access this page.');
            return redirect()->to(base_url('login'));
        }
        
        if ($this->session->get('role') !== 'student') {
            $this->session->setFlashdata('error', 'Access denied. Only students can access this page.');
            $userRole = $this->session->get('role');
            if ($userRole === 'admin') {
                return redirect()->to(base_url('admin/manage_courses'));
            } elseif ($userRole === 'teacher') {
                return redirect()->to(base_url('teacher/courses'));
            }
            return redirect()->to(base_url('dashboard'));
        }

        $studentID = $this->session->get('userID');
        $enrollmentModel = new \App\Models\EnrollmentModel();
        
        $materialModel = new \App\Models\MaterialModel();
        
        $enrolledCourses = $enrollmentModel->getUserEnrollments($studentID);
        
        foreach ($enrolledCourses as &$course) {
            $course['materials'] = $materialModel->getMaterialsByCourse($course['course_id']);
        }
        $coursesBuilder = $this->db->table('courses');
        $availableCourses = $coursesBuilder
            ->select('courses.*')
            ->where('courses.status', 'active')
            ->orderBy('courses.created_at', 'DESC')
            ->get()
            ->getResultArray();
        
        foreach ($availableCourses as &$course) {
            $instructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
            if (!empty($instructorIds)) {
                $instructorNames = $this->db->table('users')
                    ->select('name')
                    ->whereIn('id', $instructorIds)
                    ->where('role', 'teacher')
                    ->get()
                    ->getResultArray();
                $course['instructor_name'] = implode(', ', array_column($instructorNames, 'name'));
            } else {
                $course['instructor_name'] = 'No instructor assigned';
            }
        }
        
        $enrolledCourseIds = array_column($enrolledCourses, 'course_id');
        $availableCoursesFiltered = array_filter($availableCourses, function($course) use ($enrolledCourseIds) {
            return !in_array($course['id'], $enrolledCourseIds);
        });
        foreach ($enrolledCourses as &$course) {
            $course['progress'] = 0;
            $course['status_badge'] = $this->getCourseStatusBadge($course['course_status']);
            
            if (isset($course['start_date']) && $course['start_date']) {
                $course['start_date_formatted'] = date('M j, Y', strtotime($course['start_date']));
            } else {
                $course['start_date_formatted'] = 'TBA';
            }
            
            if (isset($course['end_date']) && $course['end_date']) {
                $course['end_date_formatted'] = date('M j, Y', strtotime($course['end_date']));
            } else {
                $course['end_date_formatted'] = 'TBA';
            }
        }
        
        foreach ($availableCoursesFiltered as &$course) {
            if (isset($course['start_date']) && $course['start_date']) {
                $course['start_date_formatted'] = date('M j, Y', strtotime($course['start_date']));
            } else {
                $course['start_date_formatted'] = 'TBA';
            }
            
            if (isset($course['end_date']) && $course['end_date']) {
                $course['end_date_formatted'] = date('M j, Y', strtotime($course['end_date']));
            } else {
                $course['end_date_formatted'] = 'TBA';
            }
        }

        $data = [
            'user' => [
                'userID' => $this->session->get('userID'),
                'name'   => $this->session->get('name'),
                'email'  => $this->session->get('email'),
                'role'   => $this->session->get('role')
            ],
            'title' => 'My Courses - Student Dashboard',
            'enrolledCourses' => $enrolledCourses,
            'availableCourses' => array_values($availableCoursesFiltered),
            'totalEnrolled' => count($enrolledCourses),
            'totalAvailable' => count($availableCoursesFiltered)
        ];
        
        return view('student/courses', $data);
    }
    
    private function getCourseStatusBadge($status)
    {
        switch (strtolower($status)) {
            case 'active':
                return '<span class="badge bg-success">Active</span>';
            case 'draft':
                return '<span class="badge bg-warning">Draft</span>';
            case 'completed':
                return '<span class="badge bg-info">Completed</span>';
            case 'archived':
                return '<span class="badge bg-secondary">Archived</span>';
            default:
                return '<span class="badge bg-light text-dark">Unknown</span>';
        }
    }

    private function handleCourseAssignmentRequest($teacherID)
    {
        $courseID = $this->request->getPost('course_id');
        
        if (!$courseID || !is_numeric($courseID)) {
            $this->session->setFlashdata('error', 'Invalid course ID.');
            return redirect()->to(base_url('teacher/courses'));
        }
        $courseBuilder = $this->db->table('courses');
        $course = $courseBuilder
            ->where('id', $courseID)
            ->where('status', 'active')
            ->get()
            ->getRowArray();
            
        if (!$course) {
            $this->session->setFlashdata('error', 'Course not found or not available.');
            return redirect()->to(base_url('teacher/courses'));
        }
        
        $currentInstructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
        if (!is_array($currentInstructorIds)) {
            $currentInstructorIds = [];
        }
        
        if (in_array($teacherID, $currentInstructorIds)) {
            $this->session->setFlashdata('error', 'You are already assigned to this course.');
            return redirect()->to(base_url('teacher/courses'));
        }
        
        $currentInstructorIds[] = $teacherID;
        
        $updateData = [
            'instructor_ids' => json_encode(array_unique($currentInstructorIds)),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($courseBuilder->where('id', $courseID)->update($updateData)) {
            // Step 7: Notify admin(s) and the teacher about course assignment
            $teacherName = $this->session->get('name');
            $courseName = $course['title'];
            
            // Notify all admins
            $admins = $this->db->table('users')->where('role', 'admin')->get()->getResultArray();
            foreach ($admins as $admin) {
                $this->createNotification(
                    $admin['id'],
                    "Teacher '{$teacherName}' has self-assigned to course '{$courseName}'"
                );
            }
            
            // Notify the teacher themselves
            $this->createNotification(
                $teacherID,
                "You have been successfully assigned to teach '{$courseName}'"
            );
            
            $assignmentActivity = [
                'type' => 'course_assignment',
                'icon' => '👨‍🏫',
                'title' => 'Course Assignment',
                'description' => 'Teacher ' . esc($this->session->get('name')) . ' was assigned to teach "' . esc($course['title']) . '" (' . esc($course['course_code']) . ')',
                'time' => date('Y-m-d H:i:s'),
                'course_title' => esc($course['title']),
                'course_code' => esc($course['course_code']),
                'teacher_name' => esc($this->session->get('name')),
                'assigned_by' => 'Self-Assignment'
            ];

            $assignmentActivities = $this->session->get('assignment_activities') ?? [];
            
            array_unshift($assignmentActivities, $assignmentActivity);
            
            $assignmentActivities = array_slice($assignmentActivities, 0, 10);
            
            $this->session->set('assignment_activities', $assignmentActivities);

            $this->session->setFlashdata('success', 'You have been successfully assigned to teach "' . esc($course['title']) . '"!');
        } else {
            $this->session->setFlashdata('error', 'Failed to assign course. Please try again.');
        }
          return redirect()->to(base_url('teacher/courses'));
    }

    private function handleCourseUnassignmentRequest($teacherID)
    {
        $courseID = $this->request->getPost('course_id');
        
        if (!$courseID || !is_numeric($courseID)) {
            $this->session->setFlashdata('error', 'Invalid course ID.');
            return redirect()->to(base_url('teacher/courses'));
        }
        $courseBuilder = $this->db->table('courses');
        $course = $courseBuilder
            ->where('id', $courseID)
            ->where("JSON_CONTAINS(instructor_ids, '\"$teacherID\"')", null, false)
            ->get()
            ->getRowArray();
            
        if (!$course) {
            $this->session->setFlashdata('error', 'Course not found or you are not assigned to it.');
            return redirect()->to(base_url('teacher/courses'));
        }
        
        $enrollmentCount = $this->db->table('enrollments')
            ->where('course_id', $courseID)
            ->countAllResults();
        
        $currentInstructorIds = json_decode($course['instructor_ids'] ?? '[]', true);
        if (!is_array($currentInstructorIds)) {
            $currentInstructorIds = [];
        }
        $currentInstructorIds = array_values(array_filter($currentInstructorIds, function($id) use ($teacherID) {
            return $id != $teacherID;
        }));
        
        $updateData = [
            'instructor_ids' => json_encode($currentInstructorIds),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($courseBuilder->where('id', $courseID)->update($updateData)) {
            $unassignmentActivity = [
                'type' => 'course_unassignment',
                'icon' => '🔄',
                'title' => 'Course Unassignment',
                'description' => 'Teacher ' . esc($this->session->get('name')) . ' unassigned themselves from "' . esc($course['title']) . '" (' . esc($course['course_code']) . ')',
                'time' => date('Y-m-d H:i:s'),
                'course_title' => esc($course['title']),
                'course_code' => esc($course['course_code']),
                'teacher_name' => esc($this->session->get('name')),
                'student_count' => $enrollmentCount,
                'unassigned_by' => 'Self-Unassignment'
            ];

            $assignmentActivities = $this->session->get('assignment_activities') ?? [];
            
            array_unshift($assignmentActivities, $unassignmentActivity);
            
            $assignmentActivities = array_slice($assignmentActivities, 0, 10);
            
            $this->session->set('assignment_activities', $assignmentActivities);

            if ($enrollmentCount > 0) {
                $this->session->setFlashdata('success', 'You have been unassigned from "' . esc($course['title']) . '"! Note: ' . $enrollmentCount . ' student(s) are still enrolled in this course.');
            } else {
                $this->session->setFlashdata('success', 'You have been successfully unassigned from "' . esc($course['title']) . '"!');
            }
        } else {
            $this->session->setFlashdata('error', 'Failed to unassign course. Please try again.');
        }
        
        return redirect()->to(base_url('teacher/courses'));
    }
}