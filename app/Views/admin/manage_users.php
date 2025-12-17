<?= view('templates/header', ['title' => $title]) ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="fw-bold text-primary">
                    <i class="bi bi-people-fill"></i> Manage Users
                </h1>
                <a href="<?= base_url('admin/manage_users?create=true') ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus-fill"></i> Add New User
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
                        <h5 class="mb-0"><i class="bi bi-person-plus-fill"></i> Create New User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/manage_users?action=create') ?>">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= old('name') ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= old('email') ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label fw-bold">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="teacher" <?= old('role') === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                        <option value="student" <?= old('role') === 'student' ? 'selected' : '' ?>>Student</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> Create User
                                </button>
                                <a href="<?= base_url('admin/manage_users') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($showEditForm && isset($editUser)): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('admin/manage_users?action=edit&id=' . $editUser['id']) ?>">
                            <?= csrf_field() ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?= old('name', $editUser['name']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= old('email', $editUser['email']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Leave blank to keep current password">
                                    <small class="text-muted">Only fill this if you want to change the password</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label fw-bold">Role</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="admin" <?= old('role', $editUser['role']) === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="teacher" <?= old('role', $editUser['role']) === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                                        <option value="student" <?= old('role', $editUser['role']) === 'student' ? 'selected' : '' ?>>Student</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-lg"></i> Update User
                                </button>
                                <a href="<?= base_url('admin/manage_users') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-lg"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-table"></i> Users List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            No users found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $usr): ?>
                                        <tr>
                                            <td class="align-middle"><?= esc($usr['id']) ?></td>
                                            <td class="align-middle">
                                                <strong><?= esc($usr['name']) ?></strong>
                                                <?php if ($usr['id'] == $currentAdminID): ?>
                                                    <span class="badge bg-info text-dark ms-1">You</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle"><?= esc($usr['email']) ?></td>
                                            <td class="align-middle">
                                                <?php
                                                $badgeClass = 'bg-secondary';
                                                if ($usr['role'] === 'admin') $badgeClass = 'bg-danger';
                                                elseif ($usr['role'] === 'teacher') $badgeClass = 'bg-primary';
                                                elseif ($usr['role'] === 'student') $badgeClass = 'bg-success';
                                                ?>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= ucfirst(esc($usr['role'])) ?>
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <small><?= date('M j, Y', strtotime($usr['created_at'])) ?></small>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group" role="group">
                                                    <?php if ($usr['id'] != $currentAdminID): ?>
                                                        <a href="<?= base_url('admin/manage_users?action=edit&id=' . $usr['id']) ?>" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="Edit User">
                                                            <i class="bi bi-pencil-fill"></i> Edit
                                                        </a>
                                                        
                                                        <?php if ($usr['role'] !== 'admin'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    onclick="confirmDelete(<?= $usr['id'] ?>, '<?= esc($usr['name']) ?>')"
                                                                    title="Delete User">
                                                                <i class="bi bi-trash-fill"></i> Delete
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-secondary disabled" 
                                                                    title="Cannot delete admin users"
                                                                    disabled>
                                                                <i class="bi bi-shield-fill-x"></i> Protected
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-secondary disabled" 
                                                                disabled>
                                                            <i class="bi bi-person-fill-lock"></i> Your Account
                                                        </button>
                                                    <?php endif; ?>
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
function confirmDelete(userId, userName) {
    if (confirm('Are you sure you want to delete user: ' + userName + '?\n\nThis action cannot be undone.')) {
        window.location.href = '<?= base_url('admin/manage_users') ?>?action=delete&id=' + userId;
    }
}
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
</body>
</html>
