<?= view('templates/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold text-primary">
                    <i class="bi bi-book-fill"></i> Manage Courses
                </h1>
                <a href="<?= base_url('admin/manage_courses?create=true') ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle-fill"></i> Add New Course
                </a>
            </div>

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

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($showCreateForm): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-plus-circle-fill"></i> Create New Course</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/manage_courses?action=create') ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label fw-bold">Course Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?= old('title') ?>" required 
                                           placeholder="e.g., Introduction to Programming">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="course_code" class="form-label fw-bold">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="course_code" name="course_code" 
                                           value="<?= old('course_code') ?>" required 
                                           placeholder="e.g., CS101">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          required placeholder="Enter course description..."><?= old('description') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-bold">Category</label>
                                    <input type="text" class="form-control" id="category" name="category" 
                                           value="<?= old('category') ?>" 
                                           placeholder="e.g., Computer Science, Mathematics">
                                    <small class="text-muted">Optional: Enter course category</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="credits" class="form-label fw-bold">Credits</label>
                                    <input type="number" class="form-control" id="credits" name="credits" 
                                           value="<?= old('credits', 3) ?>" min="1" max="9" 
                                           placeholder="Default: 3">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="duration_weeks" class="form-label fw-bold">Duration (Weeks)</label>
                                    <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" 
                                           value="<?= old('duration_weeks', 16) ?>" min="1" max="99" 
                                           placeholder="Default: 16">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="max_students" class="form-label fw-bold">Max Students</label>
                                    <input type="number" class="form-control" id="max_students" name="max_students" 
                                           value="<?= old('max_students', 30) ?>" min="1" max="999" 
                                           placeholder="Default: 30">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" <?= old('status', 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="completed" <?= old('status') === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= old('status') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-bold">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?= old('start_date') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-bold">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?= old('end_date') ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        <option value="archived" <?= old('status') === 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="instructor_ids" class="form-label fw-bold">Assign Teachers</label>
                                <?php 
                                $oldInstructors = old('instructor_ids');
                                $oldInstructors = is_array($oldInstructors) ? $oldInstructors : [];
                                ?>
                                <select class="form-select" id="instructor_ids" name="instructor_ids[]" multiple size="5">
                                    <?php if (!empty($teachers)): ?>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>" 
                                                <?= in_array($teacher['id'], $oldInstructors) ? 'selected' : '' ?>>
                                                <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option disabled>No teachers available</option>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple teachers (optional)</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Create Course
                                </button>
                                <a href="<?= base_url('admin/manage_courses') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($showEditForm && isset($editCourse)): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Course</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/manage_courses?action=edit&id=' . $editCourse['id']) ?>" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label fw-bold">Course Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="<?= old('title', $editCourse['title']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="course_code" class="form-label fw-bold">Course Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="course_code" name="course_code" 
                                           value="<?= old('course_code', $editCourse['course_code']) ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          required><?= old('description', $editCourse['description']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-bold">Category</label>
                                    <input type="text" class="form-control" id="category" name="category" 
                                           value="<?= old('category', $editCourse['category']) ?>" 
                                           placeholder="e.g., Computer Science, Mathematics">
                                    <small class="text-muted">Optional: Enter course category</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="credits" class="form-label fw-bold">Credits</label>
                                    <input type="number" class="form-control" id="credits" name="credits" 
                                           value="<?= old('credits', $editCourse['credits']) ?>" min="1" max="9">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="duration_weeks" class="form-label fw-bold">Duration (Weeks)</label>
                                    <input type="number" class="form-control" id="duration_weeks" name="duration_weeks" 
                                           value="<?= old('duration_weeks', $editCourse['duration_weeks']) ?>" min="1" max="99">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="max_students" class="form-label fw-bold">Max Students</label>
                                    <input type="number" class="form-control" id="max_students" name="max_students" 
                                           value="<?= old('max_students', $editCourse['max_students']) ?>" min="1" max="999">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" <?= old('status', $editCourse['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="active" <?= old('status', $editCourse['status']) === 'active' ? 'selected' : '' ?>>Active</option>
                                        <option value="completed" <?= old('status', $editCourse['status']) === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= old('status', $editCourse['status']) === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-bold">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?= old('start_date', $editCourse['start_date']) ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-bold">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?= old('end_date', $editCourse['end_date']) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="instructor_ids" class="form-label fw-bold">Assign Teachers</label>
                                <?php 
                                $currentInstructorIds = json_decode($editCourse['instructor_ids'] ?? '[]', true);
                                if (!is_array($currentInstructorIds)) {
                                    $currentInstructorIds = [];
                                }
                                $oldInstructorsEdit = old('instructor_ids');
                                $selectedInstructors = is_array($oldInstructorsEdit) ? $oldInstructorsEdit : $currentInstructorIds;
                                ?>
                                <select class="form-select" id="instructor_ids" name="instructor_ids[]" multiple size="5">
                                    <?php if (!empty($teachers)): ?>
                                        <?php foreach ($teachers as $teacher): ?>
                                            <option value="<?= $teacher['id'] ?>" 
                                                <?= in_array($teacher['id'], $selectedInstructors) ? 'selected' : '' ?>>
                                                <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option disabled>No teachers available</option>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple teachers (optional)</small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-lg"></i> Update Course
                                </button>
                                <a href="<?= base_url('admin/manage_courses') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Courses List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Course Info</th>
                                    <th>Category</th>
                                    <th>Credits</th>
                                    <th>Duration</th>
                                    <th>Max Students</th>
                                    <th>Instructor(s)</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($courses)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            No courses found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td class="align-middle"><?= esc($course['id']) ?></td>
                                            <td class="align-middle">
                                                <strong><?= esc($course['title']) ?></strong><br>
                                                <small class="text-muted"><?= esc($course['course_code']) ?></small>
                                            </td>
                                            <td class="align-middle">
                                                <?php if (!empty($course['category'])): ?>
                                                    <span class="badge bg-info text-dark">
                                                        <?= esc($course['category']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong><?= esc($course['credits']) ?></strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong><?= esc($course['duration_weeks']) ?></strong> weeks
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong><?= esc($course['max_students']) ?></strong>
                                            </td>
                                            <td class="align-middle">
                                                <small>
                                                    <?php if (!empty($course['instructor_name'])): ?>
                                                        <?= esc($course['instructor_name']) ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not assigned</span>
                                                    <?php endif; ?>
                                                </small>
                                            </td>
                                            <td class="align-middle">
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
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('material/upload/' . $course['id']) ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Upload Materials">
                                                        <i class="bi bi-cloud-upload-fill"></i> Upload
                                                    </a>
                                                    <a href="<?= base_url('admin/manage_courses?action=edit&id=' . $course['id']) ?>" 
                                                       class="btn btn-sm btn-warning" 
                                                       title="Edit Course">
                                                        <i class="bi bi-pencil-fill"></i> Edit
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?= $course['id'] ?>, '<?= esc($course['title']) ?>')"
                                                            title="Delete Course">
                                                        <i class="bi bi-trash-fill"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(courseId, courseTitle) {
    if (confirm('Are you sure you want to delete course: ' + courseTitle + '?\n\nThis action cannot be undone and will remove all enrollments and materials associated with this course.')) {
        window.location.href = '<?= base_url('admin/manage_courses') ?>?action=delete&id=' + courseId;
    }
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
</body>
</html>
