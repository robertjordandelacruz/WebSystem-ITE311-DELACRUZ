<?= view('templates/header', ['title' => $rtr ?? 'Dashboard - DELACRUZ LMS']) ?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <?php if ($user['role'] === 'admin'): ?>
                    <div class="card-body bg-primary text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-light text-primary rounded-circle p-3 fs-4">👨‍💼</span>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">Welcome, <?= esc($user['name']) ?>!</h2>
                                <p class="mb-0 opacity-75">Admin Dashboard - Manage your learning management system</p>
                            </div>
                        </div>
                    </div>
                <?php elseif ($user['role'] === 'teacher'): ?>
                    <div class="card-body bg-success text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-light text-success rounded-circle p-3 fs-4">👨‍🏫</span>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">Welcome, <?= esc($user['name']) ?>!</h2>
                                <p class="mb-0 opacity-75">Teacher Dashboard - Manage your courses and students</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card-body bg-info text-white rounded">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-light text-info rounded-circle p-3 fs-4">👨‍🎓</span>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">Welcome, <?= esc($user['name']) ?>!</h2>
                                <p class="mb-0 opacity-75">Student Dashboard - Continue your learning journey</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <?php if ($user['role'] === 'admin'): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-primary rounded-circle p-3 fs-4">👥</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary mb-1"><?= number_format($totalUsers) ?></h5>
                                <p class="text-muted mb-0 small">Total Users</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-3 fs-4">👨‍🏫</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-success mb-1"><?= number_format($totalTeachers) ?></h5>
                                <p class="text-muted mb-0 small">Total Teachers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-info rounded-circle p-3 fs-4">👨‍🎓</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-info mb-1"><?= number_format($totalStudents) ?></h5>
                                <p class="text-muted mb-0 small">Total Students</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-warning rounded-circle p-3 fs-4">📚</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-warning mb-1">0</h5>
                                <p class="text-muted mb-0 small">Total Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($user['role'] === 'teacher'): ?>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-primary rounded-circle p-3 fs-4">📚</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary mb-1"><?= number_format($totalCourses) ?></h5>
                                <p class="text-muted mb-0 small">Total Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-3 fs-4">✅</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-success mb-1"><?= number_format($activeCourses) ?></h5>
                                <p class="text-muted mb-0 small">Active Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-warning rounded-circle p-3 fs-4">📝</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-warning mb-1"><?= number_format($draftCourses) ?></h5>
                                <p class="text-muted mb-0 small">Draft Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-info rounded-circle p-3 fs-4">�</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-info mb-1"><?= number_format($totalMaterials) ?></h5>
                                <p class="text-muted mb-0 small">Course Materials</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-primary rounded-circle p-3 fs-4">📚</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-primary mb-1"><?= number_format($enrolledCourses) ?></h5>
                                <p class="text-muted mb-0 small">Enrolled Courses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-3 fs-4">✅</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-success mb-1"><?= number_format($completedAssignments) ?></h5>
                                <p class="text-muted mb-0 small">Completed Assignments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-warning rounded-circle p-3 fs-4">⏰</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-warning mb-1"><?= number_format($pendingAssignments) ?></h5>
                                <p class="text-muted mb-0 small">Pending Assignments</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-info rounded-circle p-3 fs-4">�</span>
                            </div>
                            <div>
                                <h5 class="fw-bold text-info mb-1"><?= number_format($totalMaterials) ?></h5>
                                <p class="text-muted mb-0 small">Total Materials</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php if ($user['role'] === 'admin'): ?>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">🔧 Quick Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="#" class="btn btn-outline-primary d-flex align-items-center justify-content-start p-3">
                                <span class="me-3">👥</span>
                                <div class="text-start">
                                    <div class="fw-bold">Manage Users</div>
                                    <small class="text-muted">Add, edit, or remove users</small>
                                </div>
                            </a>
                            <a href="#" class="btn btn-outline-success d-flex align-items-center justify-content-start p-3">
                                <span class="me-3">📚</span>
                                <div class="text-start">
                                    <div class="fw-bold">Manage Courses</div>
                                    <small class="text-muted">Create and manage courses</small>
                                </div>
                            </a>
                            <a href="#" class="btn btn-outline-info d-flex align-items-center justify-content-start p-3">
                                <span class="me-3">📊</span>
                                <div class="text-start">
                                    <div class="fw-bold">View Reports</div>
                                    <small class="text-muted">Analytics and reports</small>
                                </div>
                            </a>
                            <a href="#" class="btn btn-outline-secondary d-flex align-items-center justify-content-start p-3">
                                <span class="me-3">⚙️</span>
                                <div class="text-start">
                                    <div class="fw-bold">System Settings</div>
                                    <small class="text-muted">Configure system settings</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">📋 Recent User Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentUsers)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentUsers as $recentUser): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= esc($recentUser['name']) ?></td>
                                                <td class="text-muted"><?= esc($recentUser['email']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $recentUser['role'] === 'admin' ? 'danger' : ($recentUser['role'] === 'teacher' ? 'success' : 'primary') ?> rounded-pill">
                                                        <?= ucfirst($recentUser['role']) ?>
                                                    </span>
                                                </td>
                                                <td class="text-muted small"><?= date('M j, Y', strtotime($recentUser['created_at'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <span class="text-muted">No recent activity found.</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php elseif ($user['role'] === 'teacher'): ?>
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">📚 Course Status Overview</h5>
                            <a href="<?= base_url('teacher/courses') ?>" class="btn btn-primary btn-sm">
                                <span class="me-1">📚</span> View All Courses
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <h3 class="fw-bold text-primary mb-1"><?= $activeCourses ?></h3>
                                    <small class="text-muted">Active</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <h3 class="fw-bold text-warning mb-1"><?= $draftCourses ?></h3>
                                    <small class="text-muted">Draft</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <h3 class="fw-bold text-success mb-1"><?= $completedCourses ?></h3>
                                    <small class="text-muted">Completed</small>
                                </div>
                            </div>
                        </div>
                        <?php if ($totalStudents > 0): ?>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small">Total Enrolled Students</span>
                                    <span class="badge bg-success"><?= number_format($totalStudents) ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small">Total Course Materials</span>
                                    <span class="badge bg-info"><?= number_format($totalMaterials) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">�‍🎓 Recent Enrollments</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentEnrollments)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentEnrollments as $enrollment): ?>
                                    <div class="list-group-item px-0 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1 fw-bold"><?= esc($enrollment['student_name']) ?></h6>
                                                <p class="mb-1 small text-muted"><?= esc($enrollment['course_title']) ?></p>
                                                <small class="text-muted"><?= date('M j, Y', strtotime($enrollment['enrollment_date'])) ?></small>
                                            </div>
                                            <span class="badge bg-success">New</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <span class="text-muted">No recent enrollments</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Enrolled Courses Section -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">📚 My Enrolled Courses</h5>
                            <span class="badge bg-primary"><?= count($enrolledCoursesData) ?> Enrolled</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($enrolledCoursesData)): ?>
                            <div class="row g-3">
                                <?php foreach ($enrolledCoursesData as $course): ?>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="fw-bold mb-1"><?= esc($course['course_title']) ?></h6>
                                                    <span class="badge bg-<?= $course['enrollment_status'] === 'active' ? 'success' : ($course['enrollment_status'] === 'upcoming' ? 'warning' : 'secondary') ?> rounded-pill small">
                                                        <?= ucfirst($course['enrollment_status']) ?>
                                                    </span>
                                                </div>
                                                <p class="text-muted small mb-2"><?= esc($course['course_code']) ?> • <?= esc($course['credits']) ?> Credits</p>
                                                <?php if (!empty($course['course_description'])): ?>
                                                    <p class="small text-muted mb-2"><?= esc(substr($course['course_description'], 0, 100)) ?>...</p>
                                                <?php endif; ?>
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <small class="text-muted">
                                                        Instructor: <?= esc($course['instructor_name']) ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        Enrolled: <?= $course['enrollment_date_formatted'] ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <span class="text-muted">You haven't enrolled in any courses yet. Browse available courses below to get started!</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Recent Materials Section -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">� Recent Materials</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <?php if (!empty($recentMaterials)): ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentMaterials as $material): ?>
                                    <div class="list-group-item px-0 border-0 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold small"><?= esc($material['file_name']) ?></h6>
                                                <p class="mb-1 small text-muted"><?= esc($material['course_title'] ?? 'Unknown Course') ?></p>
                                                <small class="text-muted"><?= date('M j, Y', strtotime($material['created_at'])) ?></small>
                                            </div>
                                            <a href="<?= base_url('material/view/' . $material['id']) ?>" 
                                               class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <span class="text-muted">No recent materials</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Alert Container for notifications -->
    <div id="alert-container" class="mt-3"></div>

    <?php if ($user['role'] === 'student' && !empty($availableCourses)): ?>
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">🌟 Explore More Courses</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php foreach (array_slice($availableCourses, 0, 6) as $course): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="card border h-100 course-card" data-course-id="<?= $course['id'] ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="fw-bold"><?= esc($course['title']) ?></h6>
                                                <span class="badge bg-light text-dark"><?= esc($course['category'] ?? 'General') ?></span>
                                            </div>
                                            <p class="text-muted small mb-2"><?= esc($course['course_code']) ?></p>
                                            <p class="small mb-3"><?= esc(substr($course['description'] ?? '', 0, 120)) ?>...</p>
                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Credits</small>
                                                    <span class="fw-bold"><?= $course['credits'] ?></span>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Duration</small>
                                                    <span class="fw-bold"><?= $course['duration_weeks'] ?>w</span>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted d-block">Spots</small>
                                                    <span class="fw-bold"><?= $course['spots_remaining'] ?></span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">Instructor: <?= esc($course['instructor_name']) ?></small>
                                            </div>
                                            <?php if ($course['is_full']): ?>
                                                <button class="btn btn-outline-secondary w-100" disabled>
                                                    <span class="me-1">🔒</span> Course Full
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-primary w-100 enroll-btn" 
                                                        data-course-id="<?= $course['id'] ?>"
                                                        data-course-title="<?= esc($course['title']) ?>">
                                                    <span class="me-1">➕</span> Enroll in Course
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($user['role'] === 'admin'): ?>
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="fw-bold mb-0">📈 System Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="fw-bold text-muted mb-3">User Distribution</h6>
                                    <div class="mb-2">
                                        <span class="badge bg-danger me-2">Admin</span>
                                        <span class="fw-bold"><?= $totalAdmins ?></span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-success me-2">Teachers</span>
                                        <span class="fw-bold"><?= $totalTeachers ?></span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-primary me-2">Students</span>
                                        <span class="fw-bold"><?= $totalStudents ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="fw-bold text-muted mb-3">System Status</h6>
                                    <div class="mb-2">
                                        <span class="badge bg-success me-2">●</span>
                                        <span>System Online</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-info me-2">●</span>
                                        <span>Database Connected</span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="badge bg-primary me-2">●</span>
                                        <span>All Services Running</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h6 class="fw-bold text-muted mb-3">Quick Stats</h6>
                                    <div class="mb-2">
                                        <small class="text-muted">Last Login:</small>
                                        <div class="fw-bold"><?= date('F j, Y g:i A') ?></div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Server Status:</small>
                                        <div class="fw-bold text-success">Online</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- jQuery for AJAX enrollment functionality -->
<?php if ($user['role'] === 'student'): ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle enrollment button clicks
    $('.enroll-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var courseId = button.data('course-id');
        var courseTitle = button.data('course-title');
        var courseCard = button.closest('.course-card');
        
        // Disable button and show loading
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Enrolling...');
        
        // Make AJAX request
        $.post('<?= base_url('course/enroll') ?>', {
            course_id: courseId
        }, function(response) {
            if (response.success) {
                // Show success message
                showAlert('success', response.message);
                
                // Remove course from available courses or disable button
                courseCard.fadeOut(400, function() {
                    $(this).remove();
                });
                
                // Update enrolled courses count
                var currentCount = parseInt($('.badge.bg-primary').text().split(' ')[0]);
                $('.badge.bg-primary').text((currentCount + 1) + ' Enrolled');
                
                // Add to enrolled courses section if it's empty
                if ($('#enrolled-courses-empty').length) {
                    location.reload(); // Reload to show newly enrolled course
                }
                
                // Update stats card
                var totalCoursesElement = $('.fw-bold.text-primary');
                if (totalCoursesElement.length) {
                    var currentTotal = parseInt(totalCoursesElement.text());
                    totalCoursesElement.text(currentTotal + 1);
                }
                
            } else {
                // Show error message
                showAlert('danger', response.message);
                
                // Re-enable button
                button.prop('disabled', false).html('<span class="me-1">➕</span> Enroll Now');
            }
        }, 'json').fail(function(xhr) {
            var errorMessage = 'An error occurred while enrolling. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showAlert('danger', errorMessage);
            
            // Re-enable button
            button.prop('disabled', false).html('<span class="me-1">➕</span> Enroll Now');
        });
    });
    
    // Function to show Bootstrap alerts
    function showAlert(type, message) {
        var alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('#alert-container .alert').remove();
        
        // Add new alert
        $('#alert-container').append(alertHtml);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('#alert-container .alert').fadeOut();
        }, 5000);
        
        // Scroll to alert
        $('html, body').animate({
            scrollTop: $('#alert-container').offset().top - 100
        }, 500);
    }
    
    // Handle unenroll functionality (if needed in the future)
    $('.unenroll-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var courseId = button.data('course-id');
        var courseTitle = button.data('course-title');
        
        if (!confirm('Are you sure you want to unenroll from "' + courseTitle + '"?')) {
            return;
        }
        
        // Disable button and show loading
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Unenrolling...');
        
        // Make AJAX request
        $.post('<?= base_url('course/unenroll') ?>', {
            course_id: courseId
        }, function(response) {
            if (response.success) {
                showAlert('success', response.message);
                
                // Reload page to reflect changes
                setTimeout(function() {
                    location.reload();
                }, 1500);
                
            } else {
                showAlert('danger', response.message);
                button.prop('disabled', false).html('<span class="me-1">❌</span> Unenroll');
            }
        }, 'json').fail(function(xhr) {
            var errorMessage = 'An error occurred while unenrolling. Please try again.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showAlert('danger', errorMessage);
            button.prop('disabled', false).html('<span class="me-1">❌</span> Unenroll');
        });
    });
});
</script>
<?php endif; ?>

</body>
</html>