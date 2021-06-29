<?php 

require_once 'controllers/authController.php'; 
if (isset($_SESSION['scholar_id'])) {
  header('location: myprofile.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
   <title>Scholar Portal | ICoNS</title>
  <?php require_once 'constants/links.php';?>
  </head>

  <body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <br>
    <div class="container">
      <div class="col-md-5 mx-auto">
    <form class="form-signin card" action="login.php" method="post">
      <div class="text-center mb-4">
        <img class="mb-4 mt-3" src="images/icon_brand_logo.png" alt="" style="width: 86%;">
        <br>  
        <br>  
        <h1 class="h3 mb-3 font-weight-normal">Scholar Portal</h1>
      </div>
        <?php if (isset($_GET['registration'])): ?>
        <div class="alert alert-success">
          <?php 
            echo "Hello ".$_GET['firstname']." ".$_GET['lastname'].". You can now login to your account."; 
          ?>
        </div>
        <?php endif;?>
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

      <div class="form-outline mb-3 mx-5">
        <input type="password" name="password" id="inputPassword" class="form-control form-control-lg" required>
        <label class="form-label" for="inputPassword">Password</label>
      </div>
      <button class="btn btn-lg btn-primary mx-5 pt-2" type="submit" name="login-scholar" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;">Sign in</button>
      <p class="text-center pt-2"><a href="forgot_password.php">Forgot Password?</a></p>
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
