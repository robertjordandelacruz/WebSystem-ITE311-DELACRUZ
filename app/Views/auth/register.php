<?= $this->include('templates/header') ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>User Registration</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
 <link 
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
  rel="stylesheet" 
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
  crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-lg p-4 rounded-4" style="max-width: 420px; width: 100%;">
    <h2 class="text-center mb-4">Sign Up</h2>

    <!-- Flash & Validation Messages -->
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <?php if(isset($validation)): ?>
      <div class="alert alert-warning"><?= $validation->listErrors() ?></div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="<?= site_url('register') ?>" method="post" class="needs-validation" novalidate>
      <?= csrf_field() ?>

      <div class="mb-3">
        <label for="username" class="form-label">Full Name</label>
        <input 
          type="text" 
          id="username" 
          name="name" 
          value="<?= set_value('name') ?>" 
          class="form-control" 
          placeholder="John Doe" 
          required>
      </div>

      <div class="mb-3">
        <label for="emailInput" class="form-label">Email Address</label>
        <input 
          type="email" 
          id="emailInput" 
          name="email" 
          value="<?= set_value('email') ?>" 
          class="form-control" 
          placeholder="name@example.com" 
          required>
      </div>

      <div class="mb-3">
        <label for="passwordInput" class="form-label">Password</label>
        <input 
          type="password" 
          id="passwordInput" 
          name="password" 
          class="form-control" 
          placeholder="********" 
          required>
      </div>

      <div class="mb-4">
        <label for="confirmInput" class="form-label">Confirm Password</label>
        <input 
          type="password" 
          id="confirmInput" 
          name="password_confirm" 
          class="form-control" 
          placeholder="********" 
          required>
      </div>

      <button type="submit" class="btn btn-success w-100 mb-3">Register</button>
      <p class="text-center mb-0">
        Already registered? <a href="<?= site_url('login') ?>">Login here</a>
      </p>
    </form>
  </div>
</div>

<script 
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
  crossorigin="anonymous"></script>
</body>
</html>
