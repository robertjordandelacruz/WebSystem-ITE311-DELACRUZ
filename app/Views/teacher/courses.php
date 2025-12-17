<?= view('templates/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold text-primary">
                    <i class="bi bi-journal-bookmark-fill"></i> My Courses
                </h1>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dism $dashboardData = array_merge($baseData, [
                    'title' => 'Teacher Dashboard - MARUHOM LMS',
                    'totalCourses' => 0,
                    'totalStudents' => 0
                ]);
                return view('auth/dashboard', $dashboardData);iss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Assigned Courses Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle-fill"></i> My Assigned Courses 
                        <span class="badge bg-light text-dark"><?= count($assignedCourses) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($assignedCourses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No courses assigned yet</h5>
                            <p class="text-muted">Browse available courses below and assign yourself to start teaching.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($assignedCourses as $course): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title text-success fw-bold mb-0">
                                                    <?= esc($course['title']) ?>
                                                </h5>
                                                <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($course['status'] === 'active') $badgeClass = 'bg-success';
                                                elseif ($course['status'] === 'draft') $badgeClass = 'bg-warning text-dark';
                                                elseif ($course['status'] === 'completed') $badgeClass = 'bg-info';
                                                elseif ($course['status'] === 'cancelled') $badgeClass = 'bg-danger';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= ucfirst(esc($course['status'])) ?>
                                                </span>
                                            </div>
                                            
                                            <p class="card-text text-muted mb-3">
                                                <small>
                                                    <strong>Code:</strong> <?= esc($course['course_code']) ?><br>
                                                    <?php if (!empty($course['category'])): ?>
                                                        <strong>Category:</strong> <?= esc($course['category']) ?><br>
                                                    <?php endif; ?>
                                                    <strong>Credits:</strong> <?= esc($course['credits']) ?> | 
                                                    <strong>Duration:</strong> <?= esc($course['duration_weeks']) ?> weeks
                                                </small>
                                            </p>

                                            <?php if (!empty($course['description'])): ?>
                                                <p class="card-text mb-3">
                                                    <?= esc(substr($course['description'], 0, 150)) ?>
                                                    <?= strlen($course['description']) > 150 ? '...' : '' ?>
                                                </p>
                                            <?php endif; ?>

                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-muted">
                                                        <i class="bi bi-people-fill"></i> 
                                                        <strong><?= esc($course['enrolled_students']) ?></strong> / <?= esc($course['max_students']) ?> students
                                                    </span>
                                                    <?php if (isset($course['co_instructors'])): ?>
                                                        <span class="badge bg-info" title="Co-Instructors">
                                                            <i class="bi bi-person-badge"></i> <?= esc($course['co_instructors']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="progress mt-2" style="height: 8px;">
                                                    <?php 
                                                    $percentage = $course['max_students'] > 0 
                                                        ? ($course['enrolled_students'] / $course['max_students']) * 100 
                                                        : 0;
                                                    $progressClass = $percentage >= 80 ? 'bg-danger' : ($percentage >= 50 ? 'bg-warning' : 'bg-success');
                                                    ?>
                                                    <div class="progress-bar <?= $progressClass ?>" 
                                                         role="progressbar" 
                                                         style="width: <?= $percentage ?>%"
                                                         aria-valuenow="<?= $percentage ?>" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>

                                            <?php if (!empty($course['students'])): ?>
                                                <div class="mb-3">
                                                    <button class="btn btn-sm btn-outline-primary w-100" 
                                                            type="button" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#students-<?= $course['id'] ?>" 
                                                            aria-expanded="false">
                                                        <i class="bi bi-list-ul"></i> View Enrolled Students (<?= count($course['students']) ?>)
                                                    </button>
                                                    <div class="collapse mt-2" id="students-<?= $course['id'] ?>">
                                                        <div class="card card-body">
                                                            <div class="list-group list-group-flush">
                                                                <?php foreach ($course['students'] as $student): ?>
                                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                                        <div>
                                                                            <strong><?= esc($student['name']) ?></strong><br>
                                                                            <small class="text-muted"><?= esc($student['email']) ?></small>
                                                                        </div>
                                                                        <button type="button" 
                                                                                class="btn btn-sm btn-danger"
                                                                                onclick="removeStudent(<?= $course['id'] ?>, <?= $student['user_id'] ?>, '<?= esc($student['name']) ?>', '<?= esc($course['title']) ?>')">
                                                                            <i class="bi bi-person-dash"></i> Remove
                                                                        </button>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm flex-fill"
                                                        onclick="showAddStudentModal(<?= $course['id'] ?>, '<?= esc($course['title']) ?>')">
                                                    <i class="bi bi-person-plus-fill"></i> Add Student
                                                </button>
                                                <a href="<?= base_url('material/upload/' . $course['id']) ?>" 
                                                   class="btn btn-info btn-sm flex-fill">
                                                    <i class="bi bi-cloud-upload-fill"></i> Materials
                                                </a>
                                                <form method="POST" action="<?= base_url('teacher/courses') ?>" class="flex-fill" onsubmit="return confirm('Are you sure you want to unassign yourself from this course?');">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="unassign_course">
                                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm w-100">
                                                        <i class="bi bi-box-arrow-right"></i> Unassign
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Available Courses Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-search"></i> Available Courses to Teach 
                        <span class="badge bg-light text-dark"><?= count($availableCourses) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($availableCourses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No available courses</h5>
                            <p class="text-muted">All active courses have been assigned or there are no courses available at the moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($availableCourses as $course): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary fw-bold">
                                                <?= esc($course['title']) ?>
                                            </h5>
                                            
                                            <p class="card-text text-muted mb-3">
                                                <small>
                                                    <strong>Code:</strong> <?= esc($course['course_code']) ?><br>
                                                    <?php if (!empty($course['category'])): ?>
                                                        <strong>Category:</strong> <?= esc($course['category']) ?><br>
                                                    <?php endif; ?>
                                                    <strong>Credits:</strong> <?= esc($course['credits']) ?> | 
                                                    <strong>Duration:</strong> <?= esc($course['duration_weeks']) ?> weeks<br>
                                                    <strong>Max Students:</strong> <?= esc($course['max_students']) ?>
                                                </small>
                                            </p>

                                            <?php if (!empty($course['description'])): ?>
                                                <p class="card-text mb-3">
                                                    <?= esc(substr($course['description'], 0, 100)) ?>
                                                    <?= strlen($course['description']) > 100 ? '...' : '' ?>
                                                </p>
                                            <?php endif; ?>

                                            <div class="mb-3">
                                                <span class="badge bg-info">
                                                    <i class="bi bi-people-fill"></i> 
                                                    <?= esc($course['enrolled_students']) ?> students enrolled
                                                </span>
                                            </div>

                                            <form method="POST" action="<?= base_url('teacher/courses') ?>" onsubmit="return confirm('Do you want to assign yourself to teach this course?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="assign_course">
                                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="bi bi-plus-circle-fill"></i> Assign Myself
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addStudentModalLabel">
                    <i class="bi bi-person-plus-fill"></i> Add Student to Course
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading available students...</p>
                </div>
                <div id="modalContent" style="display: none;">
                    <p class="text-muted mb-3">
                        <strong>Course:</strong> <span id="modalCourseName"></span>
                    </p>
                    <div class="mb-3">
                        <label for="studentSelect" class="form-label fw-bold">Select Student:</label>
                        <select class="form-select" id="studentSelect" size="8">
                            <option value="">-- Select a student --</option>
                        </select>
                        <small class="text-muted">Students who are not enrolled in this course</small>
                    </div>
                    <div id="modalError" class="alert alert-danger d-none" role="alert"></div>
                    <div id="modalSuccess" class="alert alert-success d-none" role="alert"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addStudentBtn" onclick="addStudent()">
                    <i class="bi bi-plus-circle"></i> Add Student
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentCourseId = null;
const addStudentModal = new bootstrap.Modal(document.getElementById('addStudentModal'));

function showAddStudentModal(courseId, courseName) {
    currentCourseId = courseId;
    document.getElementById('modalCourseName').textContent = courseName;
    document.getElementById('modalLoading').style.display = 'block';
    document.getElementById('modalContent').style.display = 'none';
    document.getElementById('modalError').classList.add('d-none');
    document.getElementById('modalSuccess').classList.add('d-none');
    document.getElementById('studentSelect').innerHTML = '<option value="">-- Select a student --</option>';
    
    addStudentModal.show();
    
    fetch(`<?= base_url('course/getAvailableStudents') ?>?course_id=${courseId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
        
        if (data.success) {
            const select = document.getElementById('studentSelect');
            if (data.data.students.length === 0) {
                select.innerHTML = '<option value="">No available students</option>';
                document.getElementById('addStudentBtn').disabled = true;
            } else {
                data.data.students.forEach(student => {
                    const option = document.createElement('option');
                    option.value = student.id;
                    option.textContent = `${student.name} (${student.email})`;
                    select.appendChild(option);
                });
                document.getElementById('addStudentBtn').disabled = false;
            }
        } else {
            showModalError(data.message || 'Failed to load students');
        }
    })
    .catch(error => {
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalContent').style.display = 'block';
        showModalError('An error occurred while loading students');
        console.error('Error:', error);
    });
}

function addStudent() {
    const studentId = document.getElementById('studentSelect').value;
    
    if (!studentId) {
        showModalError('Please select a student');
        return;
    }
    
    document.getElementById('addStudentBtn').disabled = true;
    document.getElementById('modalError').classList.add('d-none');
    document.getElementById('modalSuccess').classList.add('d-none');
    
    const formData = new FormData();
    formData.append('student_id', studentId);
    formData.append('course_id', currentCourseId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= base_url('course/addStudent') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showModalSuccess(data.message || 'Student added successfully!');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showModalError(data.message || 'Failed to add student');
            document.getElementById('addStudentBtn').disabled = false;
        }
    })
    .catch(error => {
        showModalError('An error occurred while adding student');
        document.getElementById('addStudentBtn').disabled = false;
        console.error('Error:', error);
    });
}

function removeStudent(courseId, studentId, studentName, courseTitle) {
    if (!confirm(`Are you sure you want to remove ${studentName} from ${courseTitle}?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('student_id', studentId);
    formData.append('course_id', courseId);
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= base_url('course/removeStudent') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Student removed successfully!');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to remove student');
        }
    })
    .catch(error => {
        alert('An error occurred while removing student');
        console.error('Error:', error);
    });
}

function showModalError(message) {
    const errorDiv = document.getElementById('modalError');
    errorDiv.textContent = message;
    errorDiv.classList.remove('d-none');
}

function showModalSuccess(message) {
    const successDiv = document.getElementById('modalSuccess');
    successDiv.textContent = message;
    successDiv.classList.remove('d-none');
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</body>
</html>
