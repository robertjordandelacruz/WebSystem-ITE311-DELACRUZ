<?= $this->include('templates/header') ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>

  <!-- Bootstrap CDN -->
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
    crossorigin="anonymous">
</head>
<body class="bg-light">

<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
   <a class="navbar-brand" href="<?= base_url('/') ?>">
    <?= esc(session()->get('role')) ?>
</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if (session()->get('role') === 'teacher'): ?>
          <li class="nav-item"><a class="nav-link" href="#">My Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Assignments</a></li>
        <?php elseif (session()->get('role') === 'student'): ?>
          <li class="nav-item"><a class="nav-link" href="#">Assignments</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Grades</a></li>
        <?php elseif (session()->get('role') === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="#">Users</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Courses</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Activity</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link text-danger" href="<?= base_url('logout') ?>">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">

  <!-- Teacher Dashboard -->
  <?php if (session()->get('role') === 'teacher'): ?>
    <div class="row">
      <div class="col-md-4">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">My Courses</div>
          <ul class="list-group list-group-flush">
            <?php foreach ($courses ?? [] as $c): ?>
              <li class="list-group-item"><?= esc($c['title']) ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="card-body">
            <a href="#" class="btn btn-sm btn-primary">+ Add Course</a>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-info text-white">Notifications</div>
          <div class="card-body">
            <p>You have <strong><?= esc($newAssignments ?? 0) ?></strong> new assignment submissions.</p>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>


  <!-- Student Dashboard -->
  <?php if (session()->get('role') === 'student'): ?>
    <div class="row">
      <div class="col-md-4">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white">Upcoming Deadlines</div>
          <ul class="list-group list-group-flush">
            <?php foreach ($deadlines ?? [] as $d): ?>
              <li class="list-group-item"><?= esc($d['course']) ?> - <?= esc($d['date']) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-success text-white">Recent Grades</div>
          <div class="card-body">
            <table class="table table-striped table-hover">
              <thead>
                <tr><th>Course</th><th>Grade</th><th>Feedback</th></tr>
              </thead>
              <tbody>
                <?php foreach ($grades ?? [] as $g): ?>
                  <tr>
                    <td><?= esc($g['course']) ?></td>
                    <td><?= esc($g['grade']) ?></td>
                    <td><?= esc($g['feedback']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>


  <!-- Admin Dashboard -->
  <?php if (session()->get('role') === 'admin'): ?>
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card text-bg-primary shadow-sm">
          <div class="card-body text-center">
            <h5>Total Users</h5>
            <p class="fs-3"><?= esc($totalUsers ?? 0) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card text-bg-success shadow-sm">
          <div class="card-body text-center">
            <h5>Total Courses</h5>
            <p class="fs-3"><?= esc($totalCourses ?? 0) ?></p>
          </div>
        </div>
      </div>
    </div>
    
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">Recent Activity</div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead><tr><th>User</th><th>Action</th><th>Date</th></tr></thead>
          <tbody>
            <?php if (!empty($activities)): ?>
              <?php foreach ($activities as $a): ?>
                <tr>
                  <td><?= esc($a['user']) ?></td>
                  <td><?= esc($a['action']) ?></td>
                  <td><?= esc($a['created_at']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3" class="text-center">No recent activity</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</div>

<!-- Bootstrap JS -->
<script 
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
  crossorigin="anonymous"></script>
</body>
</html>
