<?php require_once 'controllers/authController.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
   <title>Admin Registration | ICoNS</title>
  <?php require_once 'constants/links.php';?>
  </head>

  <body class="bg-light">
    <br>
    <div class="container">
      <div class="col-md-5 mx-auto">
    <form class="form-signin card" action="schlradminregister.php" method="post">
      <div class="text-center mb-4">
        <img class="mb-4 mt-3" src="images/icon_brand_logo.png" alt="" width="400" height="62">
        <br>  
        <h1 class="h3 mb-1 font-weight-normal">Create Admin Account</h1>
      </div>
      <?php if(count($errors) > 0): ?>
                  <div class="alert alert-danger">
                    <?php foreach($errors as $error): ?>
                      <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
      <div class="form-outline mb-3 mx-5">
        <input type="text" name="first_name" id="inputAFname" class="form-control form-control-lg" required autofocus>
        <label class="form-label" for="inputAFname">First Name</label>
      </div>

      <div class="form-outline mb-3 mx-5">
        <input type="text" name="last_name" id="inputALname" class="form-control form-control-lg" required autofocus>
        <label class="form-label" for="inputALname">Last Name</label>
      </div>

      <div class="form-outline mb-3 mx-5">
        <input type="email" name="email" id="inputEmail" class="form-control form-control-lg" required autofocus>
        <label class="form-label" for="inputEmail">Email address</label>
      </div>

      <div class="form-outline mb-3 mx-5">
        <input type="password" name="password" id="inputPassword" class="form-control form-control-lg" required>
        <label class="form-label" for="inputPassword">Password</label>
      </div>

      <div class="form-outline mb-3 mx-5">
        <input type="password" name="confirmpassword" id="inputConfirmPassword" class="form-control form-control-lg" required>
        <label class="form-label" for="inputConfirmPassword">Confirm Password</label>
      </div>
      <button class="btn btn-lg btn-primary mx-5 pt-2" type="submit" name="register-admin" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;">Sign up</button>
      <p class="text-center pt-2"><a href="schlradminlogin.php">Already have an account</a></p>
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
