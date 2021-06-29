<?php 

require_once 'controllers/authController.php'; 
require_once 'controllers/createController.php';

if (!isset($_SESSION['scholar_id'])) {
  header('location: login.php');
  exit();
} 

$modalClass = "";
  if (isset($_GET['enrollment'])) {
  $modalClass = "enrollSuccessModal"; 
}

$email = $_SESSION['email'];



$selectContactDetails = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE email='$email' LIMIT 1");
$queries4 = mysqli_fetch_all($selectContactDetails, MYSQLI_ASSOC);

foreach ($queries4 as $query4):
  $scholar_id = $query4['student_id'];

  $selectSchedule = $conn->query("SELECT * from tbl_schedules WHERE activity='Enrollment Period' AND applicable_for = '$scholar_id' AND status = 'Closed'");

  $selectScholarshipStatus = $conn->query("SELECT * from tbl_scholarship_info WHERE student_id='$scholar_id' LIMIT 1");

  $selectScholarInfo = mysqli_query($conn, "SELECT * FROM tbl_scholarship_info WHERE student_id='$scholar_id' ORDER BY date_enrolled DESC LIMIT 1");
  $queries1 = mysqli_fetch_all($selectScholarInfo, MYSQLI_ASSOC);        
endforeach;
                            
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <link rel = "icon" href ="images/cityofnaga.png" type = "image/x-icon"> 
  <title>Online Enrollment | ICoNS</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
  <!-- MDB -->
  <link rel="stylesheet" href="css/mdb.min.css" />
  <!-- Data Tables -->
  <link rel="stylesheet" href="css/datatables2.min.css" />
  <!-- Custom styles -->
  <link rel="stylesheet" href="css/admin.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
    crossorigin="anonymous"></script>
  <style>
    .highlight {
      background-color: #D3D3D3 !important;
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
<body>
  <!--Main Navigation-->
  <header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
          <a href="myprofile.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-portrait fa-fw me-3"></i><span>My Profile</span>
          </a>
          <a href="online_enrollment.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
            <i class="fas fa-file-signature fa-fw me-3"></i><span>Online Enrollment</span>
          </a>
        </div>
      </div>
    </nav>
    <!-- Sidebar -->

    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <?php require_once 'scholar_nav.php';?>
    </nav>
    <!-- Navbar -->
  </header>
  <!--Main Navigation-->

  <!--Main layout-->
  <main style="margin-top: 58px">
    <div class="container pt-4">
      <!-- Section: Main chart -->
      <!-- <section class="mb-4">
        <div class="card">
          <div class="card-header py-3">
            <h5 class="mb-0 text-center"><strong>Active Scholars</strong></h5>
          </div>
          <div class="card-body">
            <canvas class="my-4 w-100" id="myChart" height="380"></canvas>
          </div>
        </div>
      </section> -->
      <!-- Section: Main chart -->

      <!--Section: Statistics with subtitles-->
      <form action="online_enrollment.php" method="POST">
        <section class="mb-4">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <h5 class="mb-0 py-2">
                    <strong>Online Enrollment Form</strong>
                  </h5>
                  <input type="text" name="student-id" value="<?php foreach ($queries4 as $query4): echo $query4['student_id']; endforeach; ?>" hidden>
                </div>
                <div class="col-md-6 text-end"></div>
              </div>
            </div>
            <div class="card-body">
              <div class="container">
                <?php if (mysqli_num_rows($selectSchedule) == 0) { ?>
                <div class="col-md-12"><small><i>Reminder:</i></small><small class="text-muted"><i style="font-size: 13px;"> Once you submitted this form it is not reversible. Make sure that all the data you entered are accurate.</i></small></div><hr> 
                <div class="row">
                  <div class="col-md-6">
                    <?php if (mysqli_num_rows($selectScholarInfo) > 0) { ?>
                      <div class="col-md-12 mt-4">
                      <div class="form-label-group">
                        <select class="form-select form-control required" aria-label="Default select example" name="school" style="height: 45px;" required readonly>
                          <option selected value="<?php foreach ($queries1 as $query1): echo $query1['school']; endforeach; ?>"><?php foreach ($queries1 as $query1): echo $query1['school']; endforeach; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 mt-4">
                      <div class="form-label-group">
                        <select class="form-select form-control required" aria-label="Default select example" name="course" style="height: 45px;" required readonly>
                          <option selected value="<?php foreach ($queries1 as $query1): echo $query1['course']; endforeach; ?>"><?php foreach ($queries1 as $query1): echo $query1['course']; endforeach; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mt-4">
                        <div class="form-label-group">
                          <select class="form-select form-control required" aria-label="Default select example" name="year" style="height: 45px;" required autofocus>
                            <option selected value="<?php foreach ($queries1 as $query1): echo $query1['year']; endforeach; ?>">
                              <?php foreach ($queries1 as $query1):
                                      if ($query1['semester']=="2nd") {
                                      switch ($query1['year']) {
                                        case "1st":
                                          echo "2nd";
                                          break;
                                        case "2nd":
                                          echo "3rd";
                                          break;
                                        case "3rd":
                                          echo "4th";
                                          break;
                                        case "4th":
                                          echo "5th";
                                          break;
                                        case "5th":
                                          echo "6th";
                                          break;
                                        default:
                                          echo "Year";
                                        }   
                                      } else {
                                         echo $query1['year'];
                                      }
                                    endforeach; 
                              ?>
                            </option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                            <option value="3rd">3rd</option>
                            <option value="4th">4th</option>
                            <option value="5th">5th</option>
                            <option value="6th">6th</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6 mt-4 mb-4">
                        <div class="form-label-group">
                          <select class="form-select form-control required" aria-label="Default select example" name="semester" style="height: 45px;" required autofocus>
                            <option selected value="">
                              <?php foreach ($queries1 as $query1):
                                      switch ($query1['semester']) {
                                        case "1st":
                                          echo "2nd";
                                          break;
                                        case "2nd":
                                          echo "1st";
                                          break;
                                        default:
                                          echo "Semester";
                                        }   
                                    endforeach; 
                              ?>
                            </option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 mb-4">
                        <small><i>Note:</i></small><br> 
                        <small class="text-muted"><i style="font-size: 13px;">For the newly qualified scholars, attach one(1) scanned copy of the Certificate of Registration given to you by your chosen school or university.</i></small><br>
                        <small class="text-muted"><i style="font-size: 13px;">For the old scholars, attach a scanned copy of the Certificate of Registration(current) and the Grade Slip(previous semester) given to you by your school or university.</i></small>
                      </div>
                    </div>
                    <?php } else { ?>
                    <div class="col-md-12 mt-4">
                      <div class="form-label-group">
                        <select class="form-select form-control required" aria-label="Default select example" name="school" style="height: 45px;" required autofocus>
                          <option selected value="">College / University</option>
                          <option value="ASIAN COLLEGE OF TECHNOLOGY CyberTower Campus">
                            ASIAN COLLEGE OF TECHNOLOGY CyberTower Campus
                          </option>
                          <option value="ASIAN COLLEGE OF TECHNOLOGY North Campus">
                            ASIAN COLLEGE OF TECHNOLOGY North Campus
                          </option>
                          <option value="ASIAN COLLEGE OF TECHNOLOGY South Campus">
                            ASIAN COLLEGE OF TECHNOLOGY South Campus
                          </option>
                          <option value="CEBU NORMAL UNIVERSITY Main Campus">
                            CEBU NORMAL UNIVERSITY Main Campus
                          </option>
                          <option value="CEBU TECHNOLOGICAL UNIVERSITY Danao Campus">
                            CEBU TECHNOLOGICAL UNIVERSITY Danao Campus
                          </option>
                          <option value="CEBU TECHNOLOGICAL UNIVERSITY Minglanilla Ext. Campus">
                            CEBU TECHNOLOGICAL UNIVERSITY Minglanilla Ext. Campus
                          </option>
                          <option value="CEBU TECHNOLOGICAL UNIVERSITY Naga Ext. Campus">
                            CEBU TECHNOLOGICAL UNIVERSITY Naga Ext. Campus
                          </option>
                          <option value="CEBU TECHNOLOGICAL UNIVERSITY Main Campus">
                            CEBU TECHNOLOGICAL UNIVERSITY Main Campus
                          </option>
                          <option value="CEBU TECHNOLOGICAL UNIVERSITY San Fernando Ext. Campus">
                            CEBU TECHNOLOGICAL UNIVERSITY San Fernando Ext. Campus
                          </option>
                          <option value="CEBU INSTITUTE OF TECHNOLOGY - UNIVERSITY">
                            CEBU INSTITUTE OF TECHNOLOGY - UNIVERSITY
                          </option>
                          <option value="PROFESSIONAL ACADEMY OF THE PHILIPPINES">
                            PROFESSIONAL ACADEMY OF THE PHILIPPINES
                          </option>
                          <option value="ST. CECILIA'S COLLEGE - CEBU INC.">
                            ST. CECILIA'S COLLEGE - CEBU INC.
                          </option>
                          <option value="TALISAY CITY COLLEGE">
                            TALISAY CITY COLLEGE
                          </option>
                          <option value="UNIVERSITY OF CEBU Banilad Campus">
                            UNIVERSITY OF CEBU Banilad Campus
                          </option>
                          <option value="UNIVERSITY OF CEBU LM">
                            UNIVERSITY OF CEBU LM
                          </option>
                          <option value="UNIVERSITY OF CEBU Main Campus">
                            UNIVERSITY OF CEBU Main Campus
                          </option>
                          <option value="UNIVERSITY OF CEBU METC">
                            UNIVERSITY OF CEBU METC
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 mt-4">
                      <div class="form-label-group">
                        <select class="form-select form-control required" aria-label="Default select example" name="course" style="height: 45px;" required autofocus>
                          <option selected value="">Course</option>
                          <option value="Bachelor of Elementary Education Major in SPED">
                            Bachelor of Elementary Education Major in SPED
                          </option>
                          <option value="Bachelor of Secondary Education Major in Agriculture">
                            Bachelor of Secondary Education Major in Agriculture
                          </option>
                          <option value="Bachelor of Secondary Education Major in Araling Panlipunan">
                            Bachelor of Secondary Education Major in Araling Panlipunan
                          </option>
                          <option value="Bachelor of Secondary Education Major in Biological Sciences">
                            Bachelor of Secondary Education Major in Biological Sciences
                          </option>
                          <option value="Bachelor of Secondary Education Major in Chemistry">
                            Bachelor of Secondary Education Major in Chemistry
                          </option>
                          <option value="Bachelor of Secondary Education Major in Filipino">
                            Bachelor of Secondary Education Major in Filipino
                          </option>
                          <option value="Bachelor of Secondary Education Major in Industrial Arts">
                            Bachelor of Secondary Education Major in Industrial Arts
                          </option>
                          <option value="Bachelor of Secondary Education Major in MAPEH">
                            Bachelor of Secondary Education Major in MAPEH
                          </option>
                          <option value="Bachelor of Secondary Education Major in Physics">
                            Bachelor of Secondary Education Major in Physics
                          </option>
                          <option value="Bachelor of Secondary Education Major in Values">
                            Bachelor of Secondary Education Major in Values
                          </option>
                          <option value="Bachelor of Arts/Science Major in Guidance and Counseling">
                            Bachelor of Arts/Science Major in Guidance and Counseling
                          </option>
                          <option value="Bachelor of Science in Civil Engineering">
                            Bachelor of Science in Civil Engineering
                          </option>
                          <option value="Bachelor of Science in Computer Engineering">
                            Bachelor of Science in Computer Engineering
                          </option>
                          <option value="Bachelor of Science in Electrical Engineering">
                            Bachelor of Science in Electrical Engineering
                          </option>
                          <option value="Bachelor of Science in Electronics and Communication Engineering">
                            Bachelor of Science in Electronics and Communication Engineering
                          </option>
                          <option value="Bachelor of Science in Industrial Engineering">
                            Bachelor of Science in Industrial Engineering
                          </option>
                          <option value="Bachelor of Science in Marine Engineering">
                            Bachelor of Science in Marine Engineering
                          </option>
                          <option value="Bachelor of Science in Mechanical Engineering">
                            Bachelor of Science in Mechanical Engineering
                          </option>
                          <option value="Bachelor of Science in Dentistry">
                            Bachelor of Science in Dentistry
                          </option>
                          <option value="Bachelor of Science in Medical Technology">
                            Bachelor of Science in Medical Technology
                          </option>
                          <option value="Bachelor of Science in Nursing">
                            Bachelor of Science in Nursing
                          </option>
                          <option value="Bachelor of Science in Pharmacy">
                            Bachelor of Science in Pharmacy
                          </option>
                          <option value="Bachelor of Science in Physical Theraphy">
                            Bachelor of Science in Physical Theraphy
                          </option>
                          <option value="Bachelor of Science in Radiologic Technology">
                            Bachelor of Science in Radiologic Technology
                          </option>
                          <option value="Bachelor of Science in Civil Engineering">
                            Bachelor of Science in Civil Engineering
                          </option>
                          <option value="Bachelor of Science in Accountancy">
                            Bachelor of Science in Accountancy
                          </option>
                          <option value="Bachelor of Science in Agriculture">
                            Bachelor of Science in Agriculture
                          </option>
                          <option value="Bachelor of Science in Architecture">
                            Bachelor of Science in Architecture
                          </option>
                          <option value="Bachelor of Science in Criminology">
                            Bachelor of Science in Criminology
                          </option>
                          <option value="Bachelor of Science Major in Environment">
                            Bachelor of Science Major in Environment
                          </option>
                          <option value="Bachelor of Science in Marine Transportation">
                            Bachelor of Science in Marine Transportation
                          </option>
                          <option value="Bachelor of Science in Social Works">
                            Bachelor of Science in Social Works
                          </option>
                          <option value="Bachelor of Arts in Communication/ Development Communication">
                            Bachelor of Arts in Communication/ Development Communication
                          </option>
                          <option value="Bachelor of Arts/Science in Tourism">
                            Bachelor of Arts/Science in Tourism
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 mt-4">
                        <div class="form-label-group">
                          <select class="form-select form-control required" aria-label="Default select example" name="year" style="height: 45px;" required autofocus>
                            <option selected value="">Year</option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                            <option value="3rd">3rd</option>
                            <option value="4th">4th</option>
                            <option value="5th">5th</option>
                            <option value="6th">6th</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6 mt-4 mb-4">
                        <div class="form-label-group">
                          <select class="form-select form-control required" aria-label="Default select example" name="semester" style="height: 45px;" required autofocus>
                            <option selected value="">Semester</option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 mb-4">
                        <small><i>Note:</i></small><br> 
                        <small class="text-muted"><i style="font-size: 13px;">For the newly qualified scholars, attach one(1) scanned copy of the Certificate of Registration given to you by your chosen school or university.</i></small><br>
                        <small class="text-muted"><i style="font-size: 13px;">For the old scholars, attach a scanned copy of the Certificate of Registration(current) and the Grade Slip(previous semester) given to you by your school or university.</i></small>
                      </div>
                    </div>
                  <?php } ?>
                  </div>
                  <div class="col-md-6">
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
                    <div class="row mt-5">
                      <div class="col-md-6"></div>
                      <div class="col-md-6 text-end">
                        <button type="submit" name="submit-enrollment" onclick="return confirm('Are all the data you selected/entered correct?')" class="btn btn-primary" id="btnSubmitEnrollment"><i class="fas fa-check"></i> Submit</button>
                      </div>
                    </div>       
                  </div>
                </div>
                <?php 
                } else { ?>
              <div id="zeroSelected"><small><i>Online enrollment is not available this time.</i></small></div>
              <?php } ?>
              </div>
            </div>
          </div>
        </section>
      </form>
      <!--Section: Statistics with subtitles-->
      <?php if (mysqli_num_rows($selectScholarshipStatus) > 0) { ?>
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h5 class="mb-0 py-2">
                  <strong>Enrollment Status</strong>
                </h5>
              </div>
              <div class="col-md-6 text-end">  </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="dtEnrollmentStatus" class="table table-hover" cellspacing="0" width="100%">
                <thead>
                  <tr style="border-bottom: solid 3px lightgray;">
                    <th class="th-sm">School/University
                    </th>
                    <th class="th-sm">Course
                    </th>
                    <th class="th-sm">Year Level
                    </th>
                    <th class="th-sm">Semester
                    </th>
                    <th class="th-sm">Status
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    while($row=mysqli_fetch_array($selectScholarshipStatus)){
                  ?>
                  <tr>
                    <td><?= $row['school'];?></td>
                    <td><?= $row['course'];?></td>
                    <td><?= $row['year'];?></td>
                    <td><?= $row['semester'];?></td>
                    <td><?= $row['scholarship_status'];?></td>
                  </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th class="th-sm">School/University
                    </th>
                    <th class="th-sm">Course
                    </th>
                    <th class="th-sm">Year Level
                    </th>
                    <th class="th-sm">Semester
                    </th>
                    <th class="th-sm">Status
                    </th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </section>
      <?php }  ?>
    </div>
  </main>
  <!--Main layout-->
  <div class="modal fade staticBackdrop <?php echo $modalClass; ?>" id="startExamDialog" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white mx-auto" id="staticBackdropLabel">Confirmation Message
        </h5>
      </div>
      <div class="modal-body px-4 py-4 text-center">
        <p>We already received your enrollment info.</p>
        <p>Kindly check your email from time to time for some updates.</p>
        <p class="text-muted"><small>Please be patient. Thank you.🙂</small></p>
      </div>
      <div class="modal-footer">
        <a href="online_enrollment.php" style="text-decoration: none;" class="mx-auto">
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