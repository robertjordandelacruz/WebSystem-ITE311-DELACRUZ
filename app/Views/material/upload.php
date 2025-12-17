<?= view('templates/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/manage_courses') ?>">Manage Courses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Upload Materials</li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text-primary mb-2">
                        <i class="bi bi-cloud-upload-fill"></i> Upload Course Materials
                    </h1>
                    <h5 class="text-muted">
                        <i class="bi bi-book"></i> <?= esc($course['title']) ?> 
                        <span class="badge bg-info"><?= esc($course['course_code']) ?></span>
                    </h5>
                </div>
                <a href="<?= base_url('admin/manage_courses') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Courses
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

            <!-- Upload Form Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-arrow-up-fill"></i> Upload New Material</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('material/upload/' . $course_id) ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="material_file" class="form-label fw-bold">
                                Select File <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control form-control-lg" 
                                   id="material_file" 
                                   name="material_file" 
                                   required
                                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar">
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Supported formats:</strong> PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, RAR
                                <br>
                                <i class="bi bi-info-circle"></i> 
                                <strong>Maximum file size:</strong> 10MB
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="alert alert-info border-0">
                                <h6 class="alert-heading">
                                    <i class="bi bi-lightbulb-fill"></i> Upload Guidelines:
                                </h6>
                                <ul class="mb-0">
                                    <li>Choose clear, descriptive file names for easy identification</li>
                                    <li>Ensure files are virus-free and safe for students</li>
                                    <li>Compress large files into ZIP format if needed</li>
                                    <li>Verify file content before uploading</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-cloud-upload-fill"></i> Upload Material
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-x-circle"></i> Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Existing Materials Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-files"></i> Uploaded Materials 
                        <span class="badge bg-light text-dark"><?= count($materials) ?></span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($materials)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No materials uploaded yet</h5>
                            <p class="text-muted">Upload your first course material using the form above.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="10%">Type</th>
                                        <th>File Name</th>
                                        <th width="15%">Uploaded</th>
                                        <th width="20%" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($materials as $material): ?>
                                        <tr>
                                            <td class="align-middle"><?= esc($material['id']) ?></td>
                                            <td class="align-middle">
                                                <?php
                                                $extension = strtolower(pathinfo($material['file_name'], PATHINFO_EXTENSION));
                                                $iconClass = 'bi-file-earmark';
                                                $badgeClass = 'bg-secondary';
                                                
                                                if (in_array($extension, ['pdf'])) {
                                                    $iconClass = 'bi-file-earmark-pdf-fill';
                                                    $badgeClass = 'bg-danger';
                                                } elseif (in_array($extension, ['doc', 'docx'])) {
                                                    $iconClass = 'bi-file-earmark-word-fill';
                                                    $badgeClass = 'bg-primary';
                                                } elseif (in_array($extension, ['ppt', 'pptx'])) {
                                                    $iconClass = 'bi-file-earmark-ppt-fill';
                                                    $badgeClass = 'bg-warning';
                                                } elseif (in_array($extension, ['xls', 'xlsx'])) {
                                                    $iconClass = 'bi-file-earmark-excel-fill';
                                                    $badgeClass = 'bg-success';
                                                } elseif (in_array($extension, ['zip', 'rar'])) {
                                                    $iconClass = 'bi-file-earmark-zip-fill';
                                                    $badgeClass = 'bg-info';
                                                } elseif (in_array($extension, ['txt'])) {
                                                    $iconClass = 'bi-file-earmark-text-fill';
                                                    $badgeClass = 'bg-secondary';
                                                }
                                                ?>
                                                <i class="<?= $iconClass ?> fs-3"></i>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <strong><?= esc($material['file_name']) ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <span class="badge <?= $badgeClass ?> text-uppercase">
                                                                <?= esc($extension) ?>
                                                            </span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-event"></i>
                                                    <?= date('M j, Y', strtotime($material['created_at'])) ?>
                                                    <br>
                                                    <i class="bi bi-clock"></i>
                                                    <?= date('g:i A', strtotime($material['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('material/view/' . $material['id']) ?>" 
                                                       class="btn btn-sm btn-info" 
                                                       title="View Material"
                                                       target="_blank">
                                                        <i class="bi bi-eye-fill"></i> View
                                                    </a>
                                                    <a href="<?= base_url('material/download/' . $material['id']) ?>" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Download Material">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            onclick="confirmDelete(<?= $material['id'] ?>, '<?= esc($material['file_name']) ?>')"
                                                            title="Delete Material">
                                                        <i class="bi bi-trash-fill"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Information Panel -->
            <div class="mt-4">
                <div class="card shadow-sm border-info">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle-fill text-info"></i> Material Management Information:
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Supported File Types:</h6>
                                <ul class="mb-3">
                                    <li><i class="bi bi-file-earmark-pdf-fill text-danger"></i> PDF Documents</li>
                                    <li><i class="bi bi-file-earmark-word-fill text-primary"></i> Word Documents (DOC, DOCX)</li>
                                    <li><i class="bi bi-file-earmark-ppt-fill text-warning"></i> PowerPoint (PPT, PPTX)</li>
                                    <li><i class="bi bi-file-earmark-excel-fill text-success"></i> Excel (XLS, XLSX)</li>
                                    <li><i class="bi bi-file-earmark-text-fill"></i> Text Files (TXT)</li>
                                    <li><i class="bi bi-file-earmark-zip-fill text-info"></i> Compressed Files (ZIP, RAR)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Important Notes:</h6>
                                <ul class="mb-0">
                                    <li>Maximum file size is <strong>10MB</strong></li>
                                    <li>Only teachers and admins can upload materials</li>
                                    <li>Students must be enrolled to download materials</li>
                                    <li>Deleted materials cannot be recovered</li>
                                    <li>File names should be descriptive and clear</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(materialId, fileName) {
    if (confirm('Are you sure you want to delete this material?\n\nFile: ' + fileName + '\n\nThis action cannot be undone.')) {
        window.location.href = '<?= base_url('material/delete/') ?>' + materialId;
    }
}

// File input change event to show selected file name
document.getElementById('material_file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        console.log('Selected file:', fileName);
    }
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
</body>
</html>
