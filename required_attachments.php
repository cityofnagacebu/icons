<?php 
  require 'config/db.php'; 
  require_once 'controllers/createController.php'; 

  if (!isset($_GET['token'])) {
  header('location: index.php');
  exit();
} 
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Upload Form | ICoNS</title>
    <?php require_once 'constants/links.php';?>
    <style>
        input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none !important; 
    margin: 0; 
}

input[type="file"] {
  display: block;
}

.cropped {
    width: 177px; /* width of container */
    height: 177px; /* height of container */
    /*overflow: hidden;*/
    border: 5px solid #e9ecef;
    display: inline-block;
  margin: 10px 10px 0 0;
  border-radius: 5px;
  position: relative;
  background-color: #e9ecef;
  opacity: 0.7;
}

.cropped:hover{
    opacity: 1;
}

.imageThumb {
    cursor: pointer;
    max-height: 100%;
    max-width: 100%;
    width: auto;
    height: auto;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

.imgPrev {
    padding-top: 55%;
    color: #e9ecef;
}

.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
}
.remove:hover {
  background: white;
  color: black;
}

.reset {
    position: absolute;
    float: right;
    bottom: 0px;
}

.bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <br>
    <div class="container">
        <div class="col-md-8 mx-auto">
            <form class="card px-5 py-2" action="required_attachments.php" method="post" enctype="multipart/form-data">
                <div class="text-center">
                    <img class="mt-2 mb-4" src="images/icon_brand_logo.png" alt="" style="width: 80%;">
                    <br>  
                    <h1 class="h3 mb-3 font-weight-normal">Upload Required Documents</h1>
                    <small><i style="font-size: 13px;">Note: Once you submitted this form it is not reversible. Make sure that all the data you entered are accurate.</i></small>
                </div>
                <?php if(count($errors) > 0): ?>
                  <div class="alert alert-danger">
                    <?php foreach($errors as $error): ?>
                      <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <div class="row mt-3">  
                    <div class="col-md-12">
                        <input type="text" name="token" value="<?php echo $_GET['token']; ?>" hidden>
                        <small class="text-danger"><i style="font-size: 13px;">Files must not be more than 200KB. Accepted file formats are png, jpeg/jpg.</i></small>
                        <div class="input-group mb-1">
                                <input type="file" class="form-control toggle-disabled2" id="files" accept=".png, .jpeg, .jpg" name="files[]" multiple required/>
                                <label class="input-group-text" for="files">Attach</label>
                        </div> 
                        <div class="card" style="height: 220px;">
                            <div class="row mx-auto overflow-auto">
                                <div class="col-md-12">
                                    <h4 class="imgPrev">Image Preview</h4>
                                    <span id="fileView" ></span>
                                </div>
                            </div> 
                            <span id="resetBtn"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-md btn-primary btn-block float-lg-right" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;" type="submit" name="upload-required" onclick="load_unseen_notification()"><i class="fa fa-file-import"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            <hr>  
            <div class="row">
              <div class="col"><p class="text-muted text-center">ICoNS &copy; 2020-2021</p></div>
            </div>
            </form>
        </div>
    </div>
</body>
    <?php require_once 'constants/scripts.php'?>
</html>
