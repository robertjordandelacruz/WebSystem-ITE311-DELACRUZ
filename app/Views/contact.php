<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" 
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
    crossorigin="anonymous">

    <title>Contact - Web System</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="<?= base_url() ?>">Web System</a>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url() ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('about') ?>">About Me</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="<?= base_url('contact') ?>">Contact <span class="sr-only">(current)</span></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-primary ml-2" href="<?= base_url('register') ?>">Register</a>
          </li>
        </ul>
      </div>
    </nav>

    <main class="container mt-4">
      <h1 class="text-center mb-4">Contact Page</h1>
      <p class="lead text-center mb-5">This is the contact page of Maruhom Web System.</p>
      
      <div class="row">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-body">
                      <h3 class="text-center mb-3">Get In Touch</h3>
                      <p class="text-center">Welcome to my contact page! You can reach out to me here.</p>      
                  </div>
              </div>
          </div>
          
          <div class="col-md-4">
              <div class="card">
                  <div class="card-header">
                      <h5 class="mb-0">Contact Information</h5>
                  </div>
                  <div class="card-body">
                      <p><strong>Name:</strong><br>Aslainie L. Maruhom</p>
                      <p><strong>Email:</strong><br>aslainiemaruhom@gmail.com</p>
                      <p><strong>Address:</strong><br>Purok 21, Fatima, Uhaw<br>General Santos City, Philippines</p>
                  </div>
              </div>
          </div>
      </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" 
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" 
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" 
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
    crossorigin="anonymous"></script>
  </body>
</html>