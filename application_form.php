<?php 
    require 'config/db.php';
    require_once 'controllers/createController.php'; 

    $modalClass = "";
    if (isset($_GET['registration'])) {
       $modalClass = "applySuccessModal"; 
    }
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Application Form | ICoNS</title>
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

      #vh {
    margin: auto;
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-lg-center justify-content-center" style="height: 100vh;">
    <br>
    <div class="container">
        <div class="col-md-8 mx-auto">
            <form class="card px-5 py-2" action="application_form.php" method="post" enctype="multipart/form-data">
                <div class="text-center">
                    <img class="mt-2 mb-4" src="images/icon_brand_logo.png" alt="" style="width: 86%;">
                    <br>  
                    <h1 class="h3 mb-3 font-weight-normal">Application Form</h1>
                    <small><i style="font-size: 13px;">Note: Once you submitted this form it is not reversible. Make sure that all the data you entered are accurate.</i></small>
                </div>
                <?php if(count($errors) > 0): ?>
                  <div class="alert alert-danger">
                    <?php foreach($errors as $error): ?>
                      <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <div id="firstInput">
                <div class="row">
                    <div class="col-md-5 mt-3">
                        <div class="form-label-group">
                            <select class="form-select form-control required" aria-label="Default select example" name="track" style="height: 45px;" required autofocus>
                                <option selected value="">Select Track</option>
                                <option value="Academic">ACADEMIC TRACK</option>
                                <option value="Sports">SPORTS TRACK</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7 mt-3">
                        <div class="form-outline">
                            <input type="email" id="inputEmail" name="email" class="form-control form-control-lg rounded-0 required" required>
                            <label class="form-label" for="inputEmail">Email address</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputFname" name="fname" class="form-control form-control-lg required" required>
                            <label class="form-label" for="inputFname">First Name</label>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputMname" name="mname" class="form-control form-control-lg required" required>
                            <label class="form-label" for="inputMinitial">Middle Name</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputLname" name="lname" class="form-control form-control-lg required" required autofocus>
                            <label class="form-label" for="inputLname">Last Name</label>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputName_ext" name="extname" class="form-control form-control-lg rounded-0" maxlength="3">
                            <label class="form-label" for="inputName_ext">Ext. Name(Jr./Sr.)</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputPurok" name="purok" class="form-control form-control-lg required"  required>
                            <label class="form-label" for="inputPurok">Purok/Street</label>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputBarangay" name="barangay" class="form-control form-control-lg required" required>
                            <label class="form-label" for="inputBarangay">Barangay</label>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <div class="form-outline">
                            <input type="number" id="inputPhonenum" name="phonenum" class="form-control form-control-lg required" required>
                            <label class="form-label" for="inputPhonenum">Contact Number</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 mt-3">
                        <div class="form-outline">
                            <input type="text" id="inputPurok" name="pname" class="form-control form-control-lg required" required>
                            <label class="form-label" for="inputPurok">Parent/Guardian <small>(<i>Full Name</i>)</small></label>
                        </div>
                    </div>
                    <div class="col-md-3 mt-3">
                        <button class="btn btn-lg btn-primary btn-block toggle-disabled pt-2" type="button" id="button1" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;" disabled>Next <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
                </div>

                <div id="nextInput" style="display: none;">
                <div class="row mt-3">  
                    <div class="col-md-12">
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
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-md btn-light btn-block float-lg-left" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;" id="button2"><i class="fa fa-arrow-left"></i> Back</button>
                            </div>
                            <div class="col">
                                <button class="btn btn-md btn-primary btn-block float-lg-right" style="font-family: 'ZonaPro_Bold'; height: 43px; font-size: 17px;" type="submit" name="apply" onclick="load_unseen_notification()"><i class="fa fa-file-import"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <hr>  
            <div class="row">
              <div class="col"><p class="text-start"><a href="index.php">Back to Home</a></p></div>
              <div class="col"><p class="text-muted text-end">ICoNS &copy; 2020-2021</p></div>
            </div>
            </form>
        </div>
    </div>
    <div class="modal fade staticBackdrop <?php echo $modalClass; ?>" id="startExamDialog" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white mx-auto" id="staticBackdropLabel">Confirmation Message
        </h5>
      </div>
      <div class="modal-body px-4 py-4 text-center">
        <p>We already received your application.</p>
        <p>Kindly check your email from time to time for some updates.</p>
        <p class="text-muted"><small>Please be patient. Thank you.🙂</small></p>
      </div>
      <div class="modal-footer">
        <a href="application_form.php" style="text-decoration: none;" class="mx-auto">
            <button type="button" class="btn btn-success btn-lg">
                <i class="fas fa-thumbs-up"></i> Okay
            </button>
        </a>
      </div>
    </div>
  </div>
</div>
</body>
    <?php require_once 'constants/scripts.php'?>
</html>
