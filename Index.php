<!DOCTYPE html>
<?php

?>

<?php require("header.php"); ?>
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