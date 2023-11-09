<!DOCTYPE html>
<html lang="nl-be">
<?php require("header.php"); ?>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#">INFOSERVER</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="Homepage.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="About.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://go-ao.smartschool.be/helpdesk#!tickets/list/4ca0ce7d-eeb0-4842-802e-8c5701705bcf">Contact</a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-sm-4">
      <div class="card">
        <div class="card-header">
          Create an Account
        </div>
        <div class="card-body">
          <form onsubmit="handleFormSubmission(event)">
            <div class="form-group">
              <label for="username">Username:</label>
              <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Create Account</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>