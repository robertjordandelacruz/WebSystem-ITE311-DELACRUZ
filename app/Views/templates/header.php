<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <title><?= $title ?? 'DELA CRUZ LMS' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-dark">

    <?php 
    $session = \Config\Services::session();
    $userRole = $session->get('role');
    $isLoggedIn = $session->get('isLoggedIn');
    ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #000000ff;">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="<?= $isLoggedIn ? base_url('dashboard') : base_url() ?>">
                📚 LMS DASHBOARD
                <?php if ($isLoggedIn): ?>
                    <span class="badge bg-light text-dark ms-2 rounded-pill fw-bold">
                        <?= ucfirst($userRole) ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">                
                <ul class="navbar-nav me-auto">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-bold" href="<?= base_url('dashboard') ?>">
                                🏠 Dashboard
                            </a>
                        </li>
                        
                        <?php if ($userRole === 'admin'): ?>
                            <!-- Admin Navigation -->
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="<?= base_url('admin/manage_users') ?>">
                                    👥 Manage Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="<?= base_url('admin/manage_courses') ?>">
                                    📚 Manage Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    📊 Reports
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    ⚙️ Settings
                                </a>
                            </li>
                        <?php elseif ($userRole === 'teacher'): ?>
                            <!-- Teacher Navigation -->
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="<?= base_url('teacher/courses') ?>">
                                    📚 My Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    📝 Assignments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    📊 Gradebook
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    👥 Students
                                </a>
                            </li>
                        <?php elseif ($userRole === 'student'): ?>
                            <!-- Student Navigation -->
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="<?= base_url('student/courses') ?>">
                                    📚 My Courses
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    📝 Assignments
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    📊 Grades
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-3 fw-bold" href="#">
                                    📅 Schedule
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Public Navigation -->
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-bold" href="<?= base_url() ?>">🏠 Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-bold" href="<?= base_url('about') ?>">ℹ️ About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link px-3 fw-bold" href="<?= base_url('contact') ?>">📞 Contact</a>
                        </li>
                    <?php endif; ?>
                </ul>
                  <ul class="navbar-nav">
                    <?php if ($isLoggedIn): ?>
                        <!-- Notification Bell -->
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" 
                               href="#" 
                               id="notificationDropdown" 
                               role="button" 
                               data-bs-toggle="dropdown" 
                               aria-expanded="false"
                               title="Notifications">
                                <i class="bi bi-bell-fill fs-5 text-white"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                      id="notification-badge" 
                                      style="display: none; font-size: 0.7rem;">
                                    0
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" 
                                aria-labelledby="notificationDropdown" 
                                style="width: 380px; max-height: 500px; overflow-y: auto;">
                                <!-- Notification Header -->
                                <li class="dropdown-header bg-primary text-white sticky-top" style="font-size: 1rem; padding: 0.75rem 1rem;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="bi bi-bell"></i> Notifications
                                        </span>
                                        <span class="badge bg-light text-primary" id="notification-count">0</span>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider m-0"></li>
                                
                                <!-- Notification List -->
                                <li id="notification-list" style="max-height: 400px; overflow-y: auto;">
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1"></i>
                                        <p class="mb-0 mt-2">No notifications</p>
                                    </div>
                                </li>
                                
                                <!-- View All Footer -->
                                <li><hr class="dropdown-divider m-0"></li>
                                <li class="text-center">
                                    <a class="dropdown-item text-primary fw-bold py-2" href="#">
                                        <i class="bi bi-arrow-right-circle"></i> View All Notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <!-- Logged In User Menu -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="badge bg-light text-dark me-2 rounded-circle fw-bold" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                    <?= strtoupper(substr($session->get('name'), 0, 1)) ?>
                                </span>
                                <?= esc($session->get('name')) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <h6 class="dropdown-header text-center">
                                        <span class="badge rounded-pill fw-bold" style="background-color: #1e3a8a; color: white;">
                                            <?= ucfirst($userRole) ?>
                                        </span>
                                    </h6>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item fw-semibold" href="#">👤 Profile</a></li>
                                <li><a class="dropdown-item fw-semibold" href="#">⚙️ Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-bold" href="<?= base_url('logout') ?>">🚪 Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Public User Menu -->
                        <li class="nav-item">
                            <a class="nav-link fw-bold px-3" href="<?= base_url('login') ?>">
                                🔑 Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light rounded-pill ms-2 px-3 fw-bold" href="<?= base_url('register') ?>">
                                📝 Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content container starts here -->
    <div class="container-fluid p-0">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>        
            <?php endif; ?>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
            crossorigin="anonymous"></script>
    
    <!-- jQuery (required for Step 5 & 6) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
            crossorigin="anonymous"></script>
    
    <?php if ($isLoggedIn): ?>
    <!-- Notification System Script with jQuery (Step 5 & 6) -->
    <script>
        // Step 5.4: jQuery function to fetch notifications using $.get()
        function fetchNotifications() {
            $.get('<?= base_url('notifications') ?>', function(data) {
                if (data.success) {
                    // Step 5.5: Update badge count with returned data
                    updateNotificationBadge(data.unread_count);
                    
                    // Step 5.6: Populate dropdown menu with notifications
                    displayNotifications(data.notifications);
                }
            }).fail(function(error) {
                console.error('Error fetching notifications:', error);
            });
        }
        
        // Step 5.5: Update notification badge - hide if 0, show otherwise
        function updateNotificationBadge(count) {
            const $badge = $('#notification-badge');
            const $countSpan = $('#notification-count');
            
            if (count > 0) {
                // Show badge with count
                $badge.text(count > 99 ? '99+' : count).show();
                $countSpan.text(count);
            } else {
                // Hide badge if count is 0
                $badge.hide();
                $countSpan.text('0');
            }
        }
        
        // Step 5.6: Display notifications with Bootstrap alert classes
        function displayNotifications(notifications) {
            const $notificationList = $('#notification-list');
            
            // If no notifications, show empty state
            if (!notifications || notifications.length === 0) {
                $notificationList.html(`
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                        <p class="mb-0 fw-bold">No notifications yet</p>
                        <small>You're all caught up!</small>
                    </div>
                `);
                return;
            }
            
            // Build notification list with Bootstrap alert classes (Step 5.6)
            let html = '';
            notifications.forEach(notification => {
                // Use Bootstrap alert classes for styling
                const alertClass = notification.is_unread ? 'alert-info' : 'alert-light';
                const unreadBadge = notification.is_unread ? '<span class="badge bg-primary">New</span>' : '';
                const fontWeight = notification.is_unread ? 'fw-bold' : '';
                
                html += `
                    <div class="alert ${alertClass} m-2 py-2 px-3 notification-item" 
                         data-notification-id="${notification.id}" 
                         data-is-read="${notification.is_read}"
                         style="cursor: pointer; border-left: 4px solid ${notification.is_unread ? '#0d6efd' : '#dee2e6'};">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="${fontWeight} text-dark mb-1">
                                    <i class="bi bi-info-circle-fill me-1"></i>
                                    ${notification.message}
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> ${notification.formatted_date}
                                </small>
                            </div>
                            <div class="ms-2">
                                ${unreadBadge}
                                ${notification.is_unread ? `
                                    <button class="btn btn-sm btn-outline-primary mark-read-btn ms-2" 
                                            data-notification-id="${notification.id}"
                                            title="Mark as read">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $notificationList.html(html);
            
            // Step 5.7: Add "Mark as Read" button click handlers
            $('.mark-read-btn').on('click', function(e) {
                e.stopPropagation(); // Prevent triggering parent click
                const notificationId = $(this).data('notification-id');
                markAsRead(notificationId);
            });
            
            // Also mark as read when clicking the notification item itself
            $('.notification-item').on('click', function(e) {
                const notificationId = $(this).data('notification-id');
                const isRead = $(this).data('is-read');
                
                if (isRead === 0) {
                    markAsRead(notificationId);
                }
            });
        }
        
        // Step 5.7: Mark notification as read using jQuery $.post()
        function markAsRead(notificationId) {
            // Get CSRF token from meta tag
            const csrfMeta = document.querySelector('meta[name^="csrf"]');
            const csrfName = csrfMeta ? csrfMeta.getAttribute('name') : '<?= csrf_token() ?>';
            const csrfHash = csrfMeta ? csrfMeta.getAttribute('content') : '<?= csrf_hash() ?>';
            
            // Create data object with CSRF token
            const postData = {};
            postData[csrfName] = csrfHash;
            
            $.post(`<?= base_url('notifications/mark_read') ?>/${notificationId}`, postData, function(data) {
                // Update CSRF token if provided
                if (data.csrf_hash && csrfMeta) {
                    csrfMeta.setAttribute('content', data.csrf_hash);
                }
                
                if (data.success) {
                    // Update badge count immediately
                    updateNotificationBadge(data.unread_count);
                    
                    // Remove notification from list with fade animation
                    $(`.notification-item[data-notification-id="${notificationId}"]`)
                        .fadeOut(300, function() {
                            $(this).remove();
                            
                            // If no notifications left, show empty state
                            if ($('#notification-list .notification-item').length === 0) {
                                displayNotifications([]);
                            }
                        });
                }
            }).fail(function(error) {
                console.error('Error marking notification as read:', error);
                alert('Failed to mark notification as read. Please try again.');
            });
        }
        
        // Step 6.1: Call notification function when page loads using $(document).ready()
        $(document).ready(function() {
            // Initial fetch of notifications on page load
            fetchNotifications();
            
            // Step 6.2: Set interval to fetch notifications every 60 seconds for real-time updates
            setInterval(fetchNotifications, 60000);
        });
        
        // Refresh notifications when dropdown is clicked/opened
        $('#notificationDropdown').on('click', function() {
            fetchNotifications();
        });
    </script>
    <?php endif; ?>
</body>
</html>