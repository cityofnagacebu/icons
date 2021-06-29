<?php 

require_once 'controllers/authController.php'; 

if (!isset($_SESSION['firstname'])) {
  header('location: index.php');
  exit();
}

if (isset($_GET['token'])) {
  $token = $_GET['token'];
  verifyAdmin($token);
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
   <title>Verification | ICoNS</title>
  <?php require_once 'constants/links.php';?>
  </head>

  <body class="bg-light">
    <br>
    <?php if(!$_SESSION['verified']): ?>
       <div class="container">
        <div class="col-md-5 mx-auto">
    <form class="form-signin">
      <div class="text-center mb-3">
        <img class="mb-4" src="images/icon_brand_logo.png" alt="" width="400" height="62">
        <br> 
        <br>  
        <h3 class="h4 mb-1 font-weight-normal">Thank you for registering <strong><?php echo $_SESSION['firstname']; ?></strong>.</h3>
      </div>
      <div class="card px-2 mx-auto text-center">
        <p id="not" class="mt-2 p-3">
          <small>You need to verify your account. <br> Sign in to your email account and click on the verification link we have sent to you at <strong class="text-primary"><?php echo $_SESSION['email']; ?></strong>.</small>
        </p>
      </div>
      <br>
      <hr>  
      <div class="row">
        <div class="col"><p><a href="index.php">Back to Home</a></p></div>
        <div class="col"><p class="text-muted text-end">ICoNS &copy; 2020-2021</p></div>
      </div>
    </form>
  </div>
</div>
    <?php endif; ?>
    <?php if($_SESSION['verified']): ?>
    <form class="form-signin" action="schlradmin.php" method="post">
      <div class="text-center mb-4">
        <img class="mb-4" src="images/icon_brand_logo.png" alt="" width="400" height="62">
        <br>  
        <br>  
        <h1 class="h3 mb-3 font-weight-normal">Administrator Portal</h1>
      </div>
      <?php if ($_SESSION['verified'] === 1): ?>
        <div class="alert <?php echo $_SESSION['alert-class']; ?>">
          <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
            unset($_SESSION['alert-class']);
            unset($_SESSION['firstname']);
          ?>
        </div>
      <?php endif;?>
      <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger">
          <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="form-label-group">
        <input type="email" name="email" id="inputEmail" class="form-control rounded-0" placeholder="Email address" required autofocus>
        <label for="inputEmail">Email address</label>
      </div>

      <div class="form-label-group">
        <input type="password" name="password" id="inputPassword" class="form-control rounded-0" placeholder="Password" required>
        <label for="inputPassword">Password</label>
      </div>
      <button class="btn btn-lg btn-primary btn-block rounded-0" type="submit" name="login-admin" style="font-family: 'ZonaPro_Bold';">Sign in</button>
      <br>
      <hr>  
      <div class="row">
        <div class="col"><p><a href="index.php">Back to Home</a></p></div>
        <div class="col"><p class="text-right">ICoNS &copy; 2020-2021</p></div>
      </div>
    </form>
    <?php endif; ?>
  </body>
    <?php require_once 'constants/scripts.php'?>
</html>
