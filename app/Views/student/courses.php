<?= view('templates/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold text-primary">
                    <i class="bi bi-mortarboard-fill"></i> My Courses
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Enrolled Courses</h6>
                                    <h2 class="mb-0 fw-bold"><?= $totalEnrolled ?></h2>
                                </div>
                                <i class="bi bi-book-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card bg-info text-white shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase mb-1">Available Courses</h6>
                                    <h2 class="mb-0 fw-bold"><?= $totalAvailable ?></h2>
                                </div>
                                <i class="bi bi-grid-fill" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Courses Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle-fill"></i> My Enrolled Courses 
                        <span class="badge bg-light text-dark"><?= count($enrolledCourses) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($enrolledCourses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No enrolled courses yet</h5>
                            <p class="text-muted">Browse available courses below and enroll to start learning.</p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($enrolledCourses as $course): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-success">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title text-success fw-bold mb-0">
                                                    <?= esc($course['course_title']) ?>
                                                </h5>
                                                <?= $course['status_badge'] ?>
                                            </div>
                                            
                                            <p class="card-text text-muted mb-3">
                                                <small>
                                                    <strong>Code:</strong> <?= esc($course['course_code']) ?><br>
                                                    <?php if (!empty($course['category'])): ?>
                                                        <strong>Category:</strong> <?= esc($course['category']) ?><br>
                                                    <?php endif; ?>
                                                    <strong>Credits:</strong> <?= esc($course['credits']) ?>
                                                </small>
                                            </p>

                                            <?php if (!empty($course['description'])): ?>
                                                <p class="card-text mb-3">
                                                    <?= esc(substr($course['description'], 0, 120)) ?>
                                                    <?= strlen($course['description']) > 120 ? '...' : '' ?>
                                                </p>
                                            <?php endif; ?>

                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event"></i> 
                                                    <strong>Enrolled:</strong> <?= date('M j, Y', strtotime($course['enrollment_date'])) ?>
                                                </small>
                                            </div>

                                            <?php if (!empty($course['start_date_formatted']) || !empty($course['end_date_formatted'])): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <?php if (!empty($course['start_date_formatted'])): ?>
                                                            <i class="bi bi-play-circle"></i> 
                                                            <strong>Start:</strong> <?= esc($course['start_date_formatted']) ?>
                                                            <br>
                                                        <?php endif; ?>
                                                        <?php if (!empty($course['end_date_formatted'])): ?>
                                                            <i class="bi bi-stop-circle"></i> 
                                                            <strong>End:</strong> <?= esc($course['end_date_formatted']) ?>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Course Materials Section -->
                                            <?php if (!empty($course['materials']) && count($course['materials']) > 0): ?>
                                                <div class="mb-3">
                                                    <button class="btn btn-sm btn-outline-primary w-100" 
                                                            type="button" 
                                                            data-bs-toggle="collapse" 
                                                            data-bs-target="#materials-<?= $course['course_id'] ?>" 
                                                            aria-expanded="false">
                                                        <i class="bi bi-folder-fill"></i> Course Materials (<?= count($course['materials']) ?>)
                                                    </button>
                                                    <div class="collapse mt-2" id="materials-<?= $course['course_id'] ?>">
                                                        <div class="card card-body">
                                                            <div class="list-group list-group-flush">
                                                                <?php foreach ($course['materials'] as $material): ?>
                                                                    <div class="list-group-item px-0">
                                                                        <div class="d-flex justify-content-between align-items-start">
                                                                            <div class="flex-grow-1">
                                                                                <?php
                                                                                $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                                                                $iconClass = 'bi-file-earmark';
                                                                                $badgeClass = 'bg-secondary';
                                                                                
                                                                                if (in_array($extension, ['pdf'])) {
                                                                                    $iconClass = 'bi-file-earmark-pdf-fill text-danger';
                                                                                    $badgeClass = 'bg-danger';
                                                                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                                                                    $iconClass = 'bi-file-earmark-word-fill text-primary';
                                                                                    $badgeClass = 'bg-primary';
                                                                                } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                                                                    $iconClass = 'bi-file-earmark-ppt-fill text-warning';
                                                                                    $badgeClass = 'bg-warning';
                                                                                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                                                    $iconClass = 'bi-file-earmark-excel-fill text-success';
                                                                                    $badgeClass = 'bg-success';
                                                                                } elseif (in_array($extension, ['zip', 'rar'])) {
                                                                                    $iconClass = 'bi-file-earmark-zip-fill text-info';
                                                                                    $badgeClass = 'bg-info';
                                                                                }
                                                                                ?>
                                                                                <i class="<?= $iconClass ?> me-2"></i>
                                                                                <strong><?= esc($material['file_name']) ?></strong>
                                                                                <br>
                                                                                <small class="text-muted ms-4">
                                                                                    <span class="badge <?= $badgeClass ?> text-uppercase">
                                                                                        <?= esc($extension) ?>
                                                                                    </span>
                                                                                    <i class="bi bi-calendar-event ms-2"></i>
                                                                                    <?= date('M j, Y', strtotime($material['created_at'])) ?>
                                                                                </small>
                                                                            </div>
                                                                            <div class="btn-group-vertical btn-group-sm ms-2" role="group">
                                                                                <button type="button"
                                                                                   class="btn btn-info btn-sm view-material-btn" 
                                                                                   title="View Material Details"
                                                                                   data-material-id="<?= $material['id'] ?>"
                                                                                   data-material-name="<?= esc($material['file_name']) ?>"
                                                                                   data-material-extension="<?= esc($extension) ?>"
                                                                                   data-material-date="<?= date('M j, Y', strtotime($material['created_at'])) ?>"
                                                                                   data-material-course="<?= esc($course['course_title']) ?>"
                                                                                   data-material-path="<?= esc($material['file_path']) ?>"
                                                                                   data-view-url="<?= base_url('material/view/' . $material['id']) ?>"
                                                                                   data-download-url="<?= base_url('material/download/' . $material['id']) ?>">
                                                                                    <i class="bi bi-eye-fill"></i> View
                                                                                </button>
                                                                                <a href="<?= base_url('material/download/' . $material['id']) ?>" 
                                                                                   class="btn btn-success btn-sm" 
                                                                                   title="Download Material">
                                                                                    <i class="bi bi-download"></i> Download
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-info mb-3" role="alert">
                                                    <i class="bi bi-info-circle"></i> No materials uploaded yet
                                                </div>
                                            <?php endif; ?>

                                            <div class="d-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm flex-fill"
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#materials-<?= $course['course_id'] ?>">
                                                    <i class="bi bi-folder2-open"></i> Materials
                                                </button>
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
                        <i class="bi bi-search"></i> Available Courses to Enroll 
                        <span class="badge bg-light text-dark"><?= count($availableCourses) ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Client-side Search Input -->
                    <?php if (!empty($availableCourses)): ?>
                        <div class="mb-4">
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       id="searchInput" 
                                       class="form-control border-start-0" 
                                       placeholder="Search courses by name or description...">
                                <span class="input-group-text bg-white border-start-0 text-muted">
                                    <small>AJAX</small>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle"></i> Search results are fetched from the server in real-time
                            </small>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($availableCourses)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No available courses</h5>
                            <p class="text-muted">You are enrolled in all available courses or there are no active courses at the moment.</p>
                        </div>
                    <?php else: ?>
                        <div class="row" id="coursesContainer">
                            <?php foreach ($availableCourses as $course): ?>
                                <div class="col-md-6 col-lg-4 mb-4 course-card">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title text-primary fw-bold course-name">
                                                    <?= esc($course['title']) ?>
                                                </h5>
                                                <span class="badge bg-success">Active</span>
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
                                                <p class="card-text mb-3 course-description">
                                                    <?= esc(substr($course['description'], 0, 100)) ?>
                                                    <?= strlen($course['description']) > 100 ? '...' : '' ?>
                                                </p>
                                            <?php endif; ?>

                                            <?php if (!empty($course['instructor_name'])): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-person-badge"></i> 
                                                        <strong>Instructor:</strong> <?= esc($course['instructor_name']) ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($course['start_date_formatted']) || !empty($course['end_date_formatted'])): ?>
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <?php if (!empty($course['start_date_formatted'])): ?>
                                                            <i class="bi bi-calendar-check"></i> <?= esc($course['start_date_formatted']) ?>
                                                        <?php endif; ?>
                                                        <?php if (!empty($course['end_date_formatted'])): ?>
                                                            - <?= esc($course['end_date_formatted']) ?>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>

                                            <button type="button" 
                                                    class="btn btn-success w-100 enroll-btn"
                                                    data-course-id="<?= $course['id'] ?>"
                                                    data-course-title="<?= esc($course['title']) ?>"
                                                    onclick="enrollCourse(<?= $course['id'] ?>, '<?= esc($course['title']) ?>')">
                                                <i class="bi bi-plus-circle-fill"></i> Enroll Now
                                            </button>
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

<!-- Material Details Modal -->
<div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="materialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="materialModalLabel">
                    <i class="bi bi-file-earmark-text-fill"></i> Material Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-4 bg-light rounded">
                            <i id="modal-icon" class="bi bi-file-earmark-text-fill" style="font-size: 5rem;"></i>
                            <div class="mt-3">
                                <span id="modal-extension" class="badge bg-secondary text-uppercase fs-6">PDF</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-3" id="modal-filename">Document Name</h5>
                        
                        <div class="mb-3">
                            <label class="text-muted small">COURSE</label>
                            <div class="fw-semibold" id="modal-course">Course Name</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted small">FILE TYPE</label>
                            <div class="fw-semibold" id="modal-filetype">PDF Document</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted small">UPLOADED DATE</label>
                            <div class="fw-semibold" id="modal-date">January 1, 2024</div>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Section for PDFs and Images -->
                <div id="preview-section" class="mt-4" style="display: none;">
                    <hr>
                    <h6 class="fw-bold mb-3">File Preview</h6>
                    <div id="preview-container" class="border rounded p-3 bg-light" style="max-height: 400px; overflow: auto;">
                        <iframe id="preview-iframe" style="width: 100%; height: 400px; border: none;"></iframe>
                        <img id="preview-image" style="max-width: 100%; display: none;" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Close
                </button>
                <a id="modal-download-btn" href="#" class="btn btn-success">
                    <i class="bi bi-download"></i> Download
                </a>
                <a id="modal-view-btn" href="#" class="btn btn-primary" target="_blank">
                    <i class="bi bi-box-arrow-up-right"></i> Open in New Tab
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function enrollCourse(courseId, courseTitle) {
    if (!confirm('Do you want to enroll in "' + courseTitle + '"?')) {
        return;
    }
    
    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enrolling...';
    
    // Get CSRF token from meta tag
    const csrfMeta = document.querySelector('meta[name^="csrf"]');
    const csrfName = csrfMeta ? csrfMeta.getAttribute('name') : '<?= csrf_token() ?>';
    const csrfHash = csrfMeta ? csrfMeta.getAttribute('content') : '<?= csrf_hash() ?>';
    
    const formData = new FormData();
    formData.append('course_id', courseId);
    formData.append(csrfName, csrfHash);
    
    fetch('<?= base_url('course/enroll') ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Update CSRF token in meta tag if new token is provided
        if (data.csrf_hash && csrfMeta) {
            csrfMeta.setAttribute('content', data.csrf_hash);
        }
        
        if (data.success) {
            alert(data.message || 'Successfully enrolled in the course!');
            window.location.reload();
        } else {
            // Show detailed error message for debugging
            let errorMsg = data.message || 'Failed to enroll in the course.';
            if (data.error_details) {
                errorMsg += '\n\nError details: ' + data.error_details;
            }
            if (data.error_code) {
                errorMsg += '\nError code: ' + data.error_code;
            }
            alert(errorMsg);
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    })
    .catch(error => {
        console.error('Enrollment error:', error);
        alert('An error occurred while enrolling. Please try again.\n\nError: ' + error.message);
        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
}

// Server-side AJAX Search with jQuery
$(document).ready(function() {
    let searchTimeout;
    
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        
        const searchTerm = $(this).val().trim();
        
        // Add debouncing - wait 500ms after user stops typing
        searchTimeout = setTimeout(function() {
            // Show loading state
            $('#coursesContainer').html(`
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Searching...</span>
                    </div>
                    <p class="mt-3 text-muted">Searching courses...</p>
                </div>
            `);
            
            // Make AJAX request to server
            $.ajax({
                url: '<?= base_url('course/search') ?>',
                method: 'GET',
                data: { 
                    search_term: searchTerm 
                },
                dataType: 'json',
                success: function(response) {
                    displaySearchResults(response.courses);
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', error);
                    $('#coursesContainer').html(`
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle-fill"></i> 
                                Error searching courses. Please try again.
                            </div>
                        </div>
                    `);
                }
            });
        }, 500); // 500ms delay for debouncing
    });
    
    function displaySearchResults(courses) {
        if (courses.length === 0) {
            $('#coursesContainer').html(`
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No courses found</h5>
                    <p class="text-muted">Try adjusting your search terms.</p>
                </div>
            `);
            return;
        }
        
        let html = '';
        courses.forEach(function(course) {
            // Skip already enrolled courses (check if they exist in enrolled section)
            const isEnrolled = <?= json_encode(array_column($enrolledCourses, 'course_id')) ?>.includes(course.id);
            if (isEnrolled) {
                return; // Skip this iteration
            }
            
            html += `
                <div class="col-md-6 col-lg-4 mb-4 course-card">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title text-primary fw-bold course-name">
                                    ${escapeHtml(course.title)}
                                </h5>
                                <span class="badge bg-success">Active</span>
                            </div>
                            
                            <p class="card-text text-muted mb-3">
                                <small>
                                    <strong>Code:</strong> ${escapeHtml(course.course_code)}<br>
                                    ${course.category ? '<strong>Category:</strong> ' + escapeHtml(course.category) + '<br>' : ''}
                                    <strong>Credits:</strong> ${course.credits} | 
                                    <strong>Duration:</strong> ${course.duration_weeks} weeks
                                </small>
                            </p>

                            ${course.description ? `
                                <p class="card-text mb-3 course-description">
                                    ${escapeHtml(course.description.substring(0, 100))}${course.description.length > 100 ? '...' : ''}
                                </p>
                            ` : ''}

                            ${course.start_date ? `
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-check"></i> ${formatDate(course.start_date)}
                                        ${course.end_date ? ' - ' + formatDate(course.end_date) : ''}
                                    </small>
                                </div>
                            ` : ''}

                            <button type="button" 
                                    class="btn btn-success w-100 enroll-btn"
                                    data-course-id="${course.id}"
                                    data-course-title="${escapeHtml(course.title)}"
                                    onclick="enrollCourse(${course.id}, '${escapeHtml(course.title).replace(/'/g, "\\'")}')">
                                <i class="bi bi-plus-circle-fill"></i> Enroll Now
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#coursesContainer').html(html);
    }
    
    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
    
    // Helper function to format dates
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }
});

// jQuery for Material View Modal
$(document).ready(function() {
    $('.view-material-btn').on('click', function() {
        // Get data from button attributes
        const materialId = $(this).data('material-id');
        const materialName = $(this).data('material-name');
        const materialExtension = $(this).data('material-extension');
        const materialDate = $(this).data('material-date');
        const materialCourse = $(this).data('material-course');
        const materialPath = $(this).data('material-path');
        const viewUrl = $(this).data('view-url');
        const downloadUrl = $(this).data('download-url');
        
        // Get file type description
        let fileTypeDesc = materialExtension.toUpperCase() + ' File';
        let iconClass = 'bi-file-earmark-text-fill';
        let iconColor = 'text-secondary';
        let badgeClass = 'bg-secondary';
        
        if (materialExtension === 'pdf') {
            fileTypeDesc = 'PDF Document';
            iconClass = 'bi-file-earmark-pdf-fill';
            iconColor = 'text-danger';
            badgeClass = 'bg-danger';
        } else if (['doc', 'docx'].includes(materialExtension)) {
            fileTypeDesc = 'Word Document';
            iconClass = 'bi-file-earmark-word-fill';
            iconColor = 'text-primary';
            badgeClass = 'bg-primary';
        } else if (['ppt', 'pptx'].includes(materialExtension)) {
            fileTypeDesc = 'PowerPoint Presentation';
            iconClass = 'bi-file-earmark-ppt-fill';
            iconColor = 'text-warning';
            badgeClass = 'bg-warning';
        } else if (['xls', 'xlsx'].includes(materialExtension)) {
            fileTypeDesc = 'Excel Spreadsheet';
            iconClass = 'bi-file-earmark-excel-fill';
            iconColor = 'text-success';
            badgeClass = 'bg-success';
        } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(materialExtension)) {
            fileTypeDesc = 'Image File';
            iconClass = 'bi-file-earmark-image-fill';
            iconColor = 'text-info';
            badgeClass = 'bg-info';
        } else if (['zip', 'rar'].includes(materialExtension)) {
            fileTypeDesc = 'Compressed Archive';
            iconClass = 'bi-file-earmark-zip-fill';
            iconColor = 'text-info';
            badgeClass = 'bg-info';
        }
        
        // Update modal content
        $('#modal-filename').text(materialName);
        $('#modal-course').text(materialCourse);
        $('#modal-filetype').text(fileTypeDesc);
        $('#modal-date').text(materialDate);
        $('#modal-path').text(materialPath);
        $('#modal-extension').text(materialExtension.toUpperCase()).removeClass().addClass('badge text-uppercase fs-6 ' + badgeClass);
        $('#modal-icon').removeClass().addClass('bi ' + iconClass + ' ' + iconColor).css('font-size', '5rem');
        
        // Update action buttons
        $('#modal-download-btn').attr('href', downloadUrl);
        $('#modal-view-btn').attr('href', viewUrl);
        
        // Show preview for PDF and images
        if (materialExtension === 'pdf') {
            $('#preview-section').show();
            $('#preview-iframe').show().attr('src', viewUrl);
            $('#preview-image').hide();
        } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'].includes(materialExtension)) {
            $('#preview-section').show();
            $('#preview-iframe').hide();
            $('#preview-image').show().attr('src', viewUrl);
        } else {
            $('#preview-section').hide();
            $('#preview-iframe').attr('src', '');
            $('#preview-image').attr('src', '');
        }
        
        // Show the modal
        var materialModal = new bootstrap.Modal(document.getElementById('materialModal'));
        materialModal.show();
    });
    
    // Clear iframe when modal is closed to stop loading
    $('#materialModal').on('hidden.bs.modal', function () {
        $('#preview-iframe').attr('src', '');
        $('#preview-image').attr('src', '');
    });
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</body>
</html>
