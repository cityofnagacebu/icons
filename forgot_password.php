<?php 

require_once 'controllers/authController.php'; 

if (isset($_GET['password-token'])) {
  $passwordToken = $_GET['password-token'];
  resetPassword($passwordToken);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
   <title>Recover Password | ICoNS</title>
  <?php require_once 'constants/links.php';?>
  </head>

  <body class="bg-light">
    <br>
    <div class="container">
      <div class="col-md-5 mx-auto mt-5">
    <form class="form-signin card" action="forgot_password.php" method="post">
      <?php
        if (isset($_GET["resetlink"])) {
          if ($_GET["resetlink"] == "sent") {
      ?>
        <div class="text-center mb-4">
          <img class="mb-4 mt-3" src="images/icon_brand_logo.png" alt="ICONS Logo" width="400" height="62">
          <h1 class="h3 mb-3 font-weight-normal">Check your email!</h1>
        </div>
        <p id="not" class="text-center mx-5">
          <small>Sign in to your email account and click on the password reset link we have sent to you.</small>
        </p>
      <?php
        }
      } else {
      ?>
      <div class="text-center mb-4">
        <img class="mb-4 mt-3" src="images/icon_brand_logo.png" alt="" width="400" height="62">
        <br>  
        <br>  
        <h1 class="h3 mb-3 font-weight-normal">Recover your Password</h1>
      </div>
      <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger mx-5">
          <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="form-outline mb-3 mx-5">
        <input type="email" name="email" id="inputEmail" class="form-control form-control-lg" required autofocus>
        <label class="form-label" for="inputEmail">Email address</label>
      </div>
      <button class="btn btn-lg btn-primary mx-5 pt-2" type="submit" name="reset-request-submit" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;">Send</button>
      <p class="text-center pt-2"><a href="login.php">Back to Sign In</a></p>
      <?php
                    }
                ?>
      <hr>  
      <div class="row mx-4">
        <div class="col"><p><a href="index.php">Back to Home</a></p></div>
        <div class="col"><p class="text-muted text-end">ICoNS &copy; 2020-2021</p></div>
      </div>
    </form>
  </div>
</div>
  </body>
    <?php require_once 'constants/scripts.php'?>
</html>
