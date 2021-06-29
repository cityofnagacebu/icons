<?php 

require_once 'controllers/authController.php';
require_once 'controllers/createController.php'; 

if (!isset($_SESSION['admin_id'])) {
  header('location: schlradminlogin.php');
  exit();
} 

$year = date('Y');

$selectBatch = $conn->query("SELECT * from tbl_batch WHERE year='$year'");

$selectApplicantInfo = $conn->query("SELECT * from tbl_application_info WHERE application_status<>'Enrolled' ORDER BY student_id DESC");

if(isset($_POST['delete-applicant'])){
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $delQuery1="DELETE from tbl_students WHERE student_id='$id'";
      mysqli_query($conn, $delQuery1);

      $delQuery2="DELETE from tbl_address WHERE student_id='$id'";
      mysqli_query($conn, $delQuery2);

      $delQuery3="DELETE from tbl_contact_details WHERE student_id='$id'";
      mysqli_query($conn, $delQuery3);

      $delQuery4="DELETE from tbl_application_info WHERE student_id='$id'";
      mysqli_query($conn, $delQuery4);

      $delQuery5="DELETE from tbl_parents WHERE student_id='$id'";
      mysqli_query($conn, $delQuery5);

      $delQuery6="DELETE from tbl_files WHERE student_id='$id'";
      mysqli_query($conn, $delQuery6);

      $delQuery7="DELETE from tbl_student_batch WHERE student_id='$id'";
      mysqli_query($conn, $delQuery7);
    }
    header('location: applicants.php');
    exit();
  }
}

$display1 = "display: none;";
$display2 = "";

if(isset($_POST['view-applicant'])){
  $display1 = "";
  $display2 = "display: none;";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectApplicant = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries1 = mysqli_fetch_all($selectApplicant, MYSQLI_ASSOC);

      $selectAddress = mysqli_query($conn, "SELECT * FROM tbl_address WHERE student_id='$id' LIMIT 1");
      $queries3 = mysqli_fetch_all($selectAddress, MYSQLI_ASSOC);

      $selectContactDetails = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries4 = mysqli_fetch_all($selectContactDetails, MYSQLI_ASSOC);

      $selectApplicationInfo = mysqli_query($conn, "SELECT * FROM tbl_application_info WHERE student_id='$id' LIMIT 1");
      $queries5 = mysqli_fetch_all($selectApplicationInfo, MYSQLI_ASSOC);

      $selectParent = mysqli_query($conn, "SELECT * FROM tbl_parents WHERE student_id='$id' LIMIT 1");
      $queries6 = mysqli_fetch_all($selectParent, MYSQLI_ASSOC);

      $selectFiles = mysqli_query($conn, "SELECT * FROM tbl_files WHERE student_id='$id'");
      $queries2 = mysqli_fetch_all($selectFiles, MYSQLI_ASSOC);
    }
  }
}

$modalClass1 = "";
$modalClass2 = "";
$modalClass3 = "";

if(isset($_POST['email-applicant'])){
  
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectApplicant3 = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries7 = mysqli_fetch_all($selectApplicant3, MYSQLI_ASSOC);

      $selectEmail = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries8 = mysqli_fetch_all($selectEmail, MYSQLI_ASSOC);

      $selectToken = mysqli_query($conn, "SELECT * FROM tbl_tokens WHERE student_id='$id' LIMIT 1");
      $queries9 = mysqli_fetch_all($selectToken, MYSQLI_ASSOC);

      $selectTrack = mysqli_query($conn, "SELECT * FROM tbl_application_info WHERE student_id='$id' LIMIT 1");
      $queries10 = mysqli_fetch_all($selectTrack, MYSQLI_ASSOC);
    }
    foreach ($queries10 as $query10) {
      if ($query10['track'] == "Academic") {
        $modalClass1 = "modalApplicants";
      }
      else {
        $modalClass2 = "modalApplicants";
      }
    }
  }
}

if(isset($_POST['email-applicant2'])){
  $display1 = "";
  $display2 = "display: none;";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectApplicant = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries1 = mysqli_fetch_all($selectApplicant, MYSQLI_ASSOC);

      $selectAddress = mysqli_query($conn, "SELECT * FROM tbl_address WHERE student_id='$id' LIMIT 1");
      $queries3 = mysqli_fetch_all($selectAddress, MYSQLI_ASSOC);

      $selectContactDetails = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries4 = mysqli_fetch_all($selectContactDetails, MYSQLI_ASSOC);

      $selectApplicationInfo = mysqli_query($conn, "SELECT * FROM tbl_application_info WHERE student_id='$id' LIMIT 1");
      $queries5 = mysqli_fetch_all($selectApplicationInfo, MYSQLI_ASSOC);

      $selectParent = mysqli_query($conn, "SELECT * FROM tbl_parents WHERE student_id='$id' LIMIT 1");
      $queries6 = mysqli_fetch_all($selectParent, MYSQLI_ASSOC);

      $selectFiles = mysqli_query($conn, "SELECT * FROM tbl_files WHERE student_id='$id'");
      $queries2 = mysqli_fetch_all($selectFiles, MYSQLI_ASSOC);
    }

    foreach ($_POST['id'] as $id) {
      $selectApplicant3 = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries7 = mysqli_fetch_all($selectApplicant3, MYSQLI_ASSOC);

      $selectEmail = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries8 = mysqli_fetch_all($selectEmail, MYSQLI_ASSOC);

      $selectToken = mysqli_query($conn, "SELECT * FROM tbl_tokens WHERE student_id='$id' LIMIT 1");
      $queries9 = mysqli_fetch_all($selectToken, MYSQLI_ASSOC);

      $selectTrack = mysqli_query($conn, "SELECT * FROM tbl_application_info WHERE student_id='$id' LIMIT 1");
      $queries10 = mysqli_fetch_all($selectTrack, MYSQLI_ASSOC);
    }
    foreach ($queries10 as $query10) {
      if ($query10['track'] == "Academic") {
        $modalClass1 = "modalApplicants";
      }
      else {
        $modalClass2 = "modalApplicants";
      }
    }
  }
}

if(isset($_POST['send-exam-modal'])){
  $modalClass3 = "modalApplicants";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectApplicant3 = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries7 = mysqli_fetch_all($selectApplicant3, MYSQLI_ASSOC);

      $selectEmail = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries8 = mysqli_fetch_all($selectEmail, MYSQLI_ASSOC);

      $selectToken = mysqli_query($conn, "SELECT * FROM tbl_tokens WHERE student_id='$id' LIMIT 1");
      $queries9 = mysqli_fetch_all($selectToken, MYSQLI_ASSOC);
    }
    $selectExams = $conn->query("SELECT * from tbl_exam");
  }
}

if(isset($_POST['send-exam-modal2'])){
  $display1 = "";
  $display2 = "display: none;";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectApplicant = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries1 = mysqli_fetch_all($selectApplicant, MYSQLI_ASSOC);

      $selectAddress = mysqli_query($conn, "SELECT * FROM tbl_address WHERE student_id='$id' LIMIT 1");
      $queries3 = mysqli_fetch_all($selectAddress, MYSQLI_ASSOC);

      $selectContactDetails = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries4 = mysqli_fetch_all($selectContactDetails, MYSQLI_ASSOC);

      $selectApplicationInfo = mysqli_query($conn, "SELECT * FROM tbl_application_info WHERE student_id='$id' LIMIT 1");
      $queries5 = mysqli_fetch_all($selectApplicationInfo, MYSQLI_ASSOC);

      $selectParent = mysqli_query($conn, "SELECT * FROM tbl_parents WHERE student_id='$id' LIMIT 1");
      $queries6 = mysqli_fetch_all($selectParent, MYSQLI_ASSOC);

      $selectFiles = mysqli_query($conn, "SELECT * FROM tbl_files WHERE student_id='$id'");
      $queries2 = mysqli_fetch_all($selectFiles, MYSQLI_ASSOC);
    }

    foreach ($_POST['id'] as $id) {
      $selectApplicant3 = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
      $queries7 = mysqli_fetch_all($selectApplicant3, MYSQLI_ASSOC);

      $selectEmail = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
      $queries8 = mysqli_fetch_all($selectEmail, MYSQLI_ASSOC);

      $selectToken = mysqli_query($conn, "SELECT * FROM tbl_tokens WHERE student_id='$id' LIMIT 1");
      $queries9 = mysqli_fetch_all($selectToken, MYSQLI_ASSOC);
    }
    $selectExams = $conn->query("SELECT * from tbl_exam");
    $modalClass3 = "modalApplicants";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <link rel = "icon" href ="images/cityofnaga.png" type = "image/x-icon"> 
  <title>Applicants | ICoNS</title>
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
    </style>
</head>
<body>
  <!--Main Navigation-->
  <header>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white">
      <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
          <a href="admin_dashboard.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main Dashboard</span>
          </a>
          <a href="batch.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-flag fa-fw me-3"></i><span>Batch</span>
          </a>
          <a href="applicants.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
            <i class="fas fa-users fa-fw me-3"></i><span>Applicants</span>
          </a>
          <a href="scholars.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-user-graduate fa-fw me-3"></i><span>Scholars</span>
          </a>
          <a href="examination.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-file-alt fa-fw me-3"></i><span>Examination</span>
          </a>
          <a href="exam_results.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-chart-bar fa-fw me-3"></i><span>Exam Results</span>
          </a>
          <a href="schedule.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-calendar-alt fa-fw me-3"></i><span>Schedule</span>
          </a>
        </div>
      </div>
    </nav>
    <!-- Sidebar -->

    <!-- Navbar -->
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
      <?php require_once 'admin_nav.php';?>
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


      <!--Section: Applicants-->
      <div id="tableApplicants" style="<?php echo $display2; ?>"> 
      <form action="applicants.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2">
              <strong>Applicants</strong>
            </h5>
            </div>
            <div class="col-md-6 text-end">
              <button type="submit" name="email-applicant" class="btn btn-primary" id="btnSendEmail" style="display: none;"><i class="fas fa-envelope"></i> Email</button>
              <button type="submit" name="send-exam-modal" class="btn btn-warning" id="btnSendExam" style="display: none;"><i class="fas fa-paper-plane"></i> Send Exam</button>
              <button type="submit" name="view-applicant" class="btn btn-success" id="btnDetails" style="display: none;">
                <i class="fas fa-eye"></i> Details
              </button>
              <button type="submit" name="delete-applicant" onclick="return confirm('Are you sure you want to remove this applicant/s?')" class="btn btn-danger" id="btnDelete" style="display: none;">
                <i class="fas fa-trash-alt"></i> Delete
              </button>   
            </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectBatch) > 0) { 
                if (mysqli_num_rows($selectApplicantInfo) > 0) { ?>
              <table id="dtApplicants" class="table table-hover" cellspacing="0" width="100%">
  <thead>
    <tr style="border-bottom: solid 3px lightgray;">
      <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
      <th class="th-sm">ID
      </th>
      <th class="th-sm">Surname
      </th>
      <th class="th-sm">Given Name
      </th>
      <th class="th-sm">Middle Name
      </th>
      <th class="th-sm">Track
      </th>
      <th class="th-sm">Date Applied
      </th>
      <th class="th-sm">Status
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
      $counter = 1;
      while($row=mysqli_fetch_array($selectApplicantInfo)){
      $student_id = $row['student_id'];

      $selectApplicants = $conn->query("SELECT * from tbl_students WHERE student_id='$student_id' LIMIT 1");
      $queryApplicants=mysqli_fetch_array($selectApplicants);
    ?>
    <tr>
      <td><input type="checkbox" class="checkItem form-check-input" value="<?= $queryApplicants['student_id'];?>" name="id[]"></td>
      <td><?= $queryApplicants['student_id'];?></td>
      <td><?= $queryApplicants['last_name'];?></td>
      <td><?= $queryApplicants['first_name'].' '.$queryApplicants['ext_name'];?></td>
      <td><?= $queryApplicants['middle_name'];?></td>
      <td><?= $row['track'];?></td>
      <td><?= $row['date_applied'];?></td>
      <td><?= $row['application_status'];?></td>
    </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th>
      </th>
      <th class="th-sm">ID
      </th>
      <th class="th-sm">Surname
      </th>
      <th class="th-sm">Given Name
      </th>
      <th class="th-sm">Middle Name
      </th>
      <th class="th-sm">Track
      </th>
      <th class="th-sm">Date Applied
      </th>
      <th class="th-sm">Status
      </th>
    </tr>
  </tfoot>
</table>
<?php 
                } else { ?>
              <div id="zeroSelected"><small><i>No applicant yet</i></small></div>
              <?php }
              } else { ?>
                <div id="zeroSelected"><small><i>Admission for new applicants has been closed.</i></small></div>
                <?php }
               ?>
            </div>
          </div>
        </div>
      </section>
        </form>
        </div>
      <!--Section: Applicants-->

      <!--Section: Applicants-->
      <div id="details" style="<?php echo $display1; ?>">
      <form action="applicants.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2"><a href="applicants.php" style="font-family: 'Roboto Thin';"><strong>Applicants</strong></a> /
              <strong>
                <?php
                  foreach ($queries1 as $query1):
                    echo $query1['student_id'];
                  endforeach;
                ?>
              </strong>
            </h5>
            <input type="text" name="id[]" value="<?php foreach ($queries1 as $query1): echo $query1['student_id']; endforeach; ?>" hidden>
            </div>
            <div class="col-md-6 text-end">
              <button type="submit" name="email-applicant2" class="btn btn-primary" id="btnSendEmail"><i class="fas fa-envelope"></i> Email</button>
              <button type="submit" name="send-exam-modal2" class="btn btn-warning" id="btnSendExam"><i class="fas fa-paper-plane"></i> Send Exam</button>
              <button type="submit" name="delete-applicant" onclick="return confirm('Are you sure you want to remove this applicant?')" class="btn btn-danger" id="btnDelete">
                <i class="fas fa-trash-alt"></i> Delete
              </button>   
            </div>
            </div>
          </div>
          <div class="card-body">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Given Name</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries1 as $query1):
                                echo $query1['first_name'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Middle Name</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries1 as $query1):
                                echo $query1['middle_name'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Surname</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries1 as $query1):
                                echo $query1['last_name'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Extension Name</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries1 as $query1):
                                echo $query1['ext_name'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Purok/Street</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries3 as $query3):
                                echo $query3['purok'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Barangay</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries3 as $query3):
                                echo $query3['barangay'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <span></span>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Parent/Guardian's Name</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries6 as $query6):
                                echo $query6['parent_name'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Email Address</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries4 as $query4):
                                echo $query4['email'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Contact Number</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries4 as $query4):
                                echo $query4['phone_num'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Track</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries5 as $query5):
                                echo $query5['track'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Date Applied</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries5 as $query5):
                                $date_applied = strtotime($query5['date_applied']);
                                echo date("F j, Y", $date_applied);
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Status</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries5 as $query5):
                                echo $query5['application_status'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <span></span>
                  </ul>
                </div>
              </div>
              <br>  
              <span style="font-family: 'Roboto Thin';"><strong>Attachments:</strong></span><br>  
              <a href="attachments/'.$query2['file_name'].'"></a>
              <?php
                foreach ($queries2 as $query2):
                  echo '<a href="attachments/'.$query2['file_name'].'" target="_blank"><div class="cropped">
                        <img src="attachments/'.$query2['file_name'].'" class="imageThumb" alt="'.$query2['file_name'].'"></div></a>';
                endforeach;
              ?>
              
            </div>
          </div>
        </div>
      </section>
        </form>
</div>
    </div>
  </main>
  <!--Main layout-->
    <!-- Modal -->
<!-- Modal -->
<form action="applicants.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass1; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Recipient: <a href="#" class="text-white" style="text-decoration: underline;" data-mdb-toggle="popover" data-mdb-placement="bottom" data-mdb-trigger="focus"   data-mdb-content="<?php foreach ($queries8 as $query8): echo $query8['email']; endforeach; ?>">
            <?php foreach ($queries7 as $query7): echo $query7['first_name']." ".substr($query7['middle_name'], 0, 1).". ".$query7['last_name']." ".$query7['ext_name']; endforeach; ?></a>
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <input type="text" name="studentid" value="<?php foreach ($queries7 as $query7): echo $query7['student_id']; endforeach; ?>" hidden>
        <input type="text" name="fname" value="<?php foreach ($queries7 as $query7): echo $query7['first_name']; endforeach; ?>" hidden>
        <input type="text" name="lname" value="<?php foreach ($queries7 as $query7): echo $query7['last_name']; endforeach; ?>" hidden>
        <input type="email" name="email" value="<?php foreach ($queries8 as $query8): echo $query8['email']; endforeach; ?>" hidden>
        <input type="text" name="token" value="<?php foreach ($queries9 as $query9): echo $query9['token']; endforeach; ?>" hidden>
        <div class="form-outline">
          <textarea class="form-control" name="message" id="textAreaExample" rows="3"></textarea>
          <label class="form-label" for="textAreaExample">Message <small><i>(optional)</i></small></label>
        </div>
        <br>  
        <small><i style="font-size: 13px;">Please check all the lacking documents of the applicant.</i></small>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="School Head's Certification" />
              <label class="form-check-label" for="inlineCheckbox1">School Head's Certification</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Certified True Copy of Form 138" />
              <label class="form-check-label" for="inlineCheckbox2">Certified True Copy of Form 138</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Certificate of Accreditation and Equivalency" />
              <label class="form-check-label" for="inlineCheckbox1">Certificate of Accreditation and Equivalency</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Certificate of Completion(Junior High)" />
              <label class="form-check-label" for="inlineCheckbox2">Certificate of Completion(Junior High)</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Certificate of Residency" />
              <label class="form-check-label" for="inlineCheckbox1">Certificate of Residency</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Voter's Certificate or Voter's ID" />
              <label class="form-check-label" for="inlineCheckbox2">Voter's Certificate or Voter's ID</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="BIR Certification of Income" />
              <label class="form-check-label" for="inlineCheckbox1">BIR Certification of Income</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Affidavit of Parent/s" />
              <label class="form-check-label" for="inlineCheckbox2">Affidavit of Parent/s</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Affidavit of Guardian" />
              <label class="form-check-label" for="inlineCheckbox1">Affidavit of Guardian</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Affidavit of Punong Barangay" />
              <label class="form-check-label" for="inlineCheckbox2">Affidavit of Punong Barangay</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Death Certificate of Parents" />
              <label class="form-check-label" for="inlineCheckbox1">Death Certificate of Parents</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="2x2 Formal ID Picture" />
              <label class="form-check-label" for="inlineCheckbox2">2x2 Formal ID Picture</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="send-email" class="btn btn-floating btn-primary"><i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- Modal -->
<form action="applicants.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass2; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Recipient: <a href="#" class="text-white" style="text-decoration: underline;" data-mdb-toggle="popover" data-mdb-placement="bottom" data-mdb-trigger="focus"   data-mdb-content="<?php foreach ($queries8 as $query8): echo $query8['email']; endforeach; ?>">
            <?php foreach ($queries7 as $query7): echo $query7['first_name']." ".substr($query7['middle_name'], 0, 1).". ".$query7['last_name']." ".$query7['ext_name']; endforeach; ?></a>
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <input type="text" name="studentid" value="<?php foreach ($queries7 as $query7): echo $query7['student_id']; endforeach; ?>" hidden>
        <input type="text" name="fname" value="<?php foreach ($queries7 as $query7): echo $query7['first_name']; endforeach; ?>" hidden>
        <input type="text" name="lname" value="<?php foreach ($queries7 as $query7): echo $query7['last_name']; endforeach; ?>" hidden>
        <input type="email" name="email" value="<?php foreach ($queries8 as $query8): echo $query8['email']; endforeach; ?>" hidden>
        <input type="text" name="token" value="<?php foreach ($queries9 as $query9): echo $query9['token']; endforeach; ?>" hidden>
        <div class="form-outline">
          <textarea class="form-control" name="message" id="textAreaExample" rows="3"></textarea>
          <label class="form-label" for="textAreaExample">Message</label>
        </div>
        <br>  
        <small><i style="font-size: 13px;">Please check all the lacking documents of the applicant.</i></small>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="School Head's Certification" />
              <label class="form-check-label" for="inlineCheckbox1">School Head/Sports Coordinator Certificate</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Certified True Copy of Form 138" />
              <label class="form-check-label" for="inlineCheckbox2">Certificate of Completion(Senior High)</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Certificate of Accreditation and Equivalency" />
              <label class="form-check-label" for="inlineCheckbox1">Certificate of Residency</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Certificate of Completion(Junior High)" />
              <label class="form-check-label" for="inlineCheckbox2">Voter's Certificate or Voter's ID</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Certificate of Residency" />
              <label class="form-check-label" for="inlineCheckbox1">Affidavit of Parent/s</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Voter's Certificate or Voter's ID" />
              <label class="form-check-label" for="inlineCheckbox2">Affidavit of Guardian</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="BIR Certification of Income" />
              <label class="form-check-label" for="inlineCheckbox1">Affidavit of Punong Barangay</label>
            </div>
          </div>
          <div class="col-md-6"> 
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox2" value="Affidavit of Parent/s" />
              <label class="form-check-label" for="inlineCheckbox2">Death Certificate of Parents</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" name="request[]" type="checkbox" id="inlineCheckbox1" value="Affidavit of Guardian" />
              <label class="form-check-label" for="inlineCheckbox1">2x2 Formal ID Picture</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="send-email" class="btn btn-floating btn-primary"><i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- Add Exam Modal -->
<form action="applicants.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass3; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Send to <?php foreach ($queries7 as $query7): echo $query7['first_name']." ".substr($query7['middle_name'], 0, 1).". ".$query7['last_name']." ".$query7['ext_name']; endforeach; ?>
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <input type="text" name="studentid" value="<?php foreach ($queries7 as $query7): echo $query7['student_id']; endforeach; ?>" hidden>
        <input type="text" name="fname" value="<?php foreach ($queries7 as $query7): echo $query7['first_name']; endforeach; ?>" hidden>
        <input type="text" name="lname" value="<?php foreach ($queries7 as $query7): echo $query7['last_name']; endforeach; ?>" hidden>
        <input type="email" name="email" value="<?php foreach ($queries8 as $query8): echo $query8['email']; endforeach; ?>" hidden>
        <input type="text" name="token" value="<?php foreach ($queries9 as $query9): echo $query9['token']; endforeach; ?>" hidden>
        <p>Select exam:</p>
        <?php
          $num3 = 1;
          while($row4=mysqli_fetch_array($selectExams)){
        ?>
          <div class="form-check mt-3 ms-5">
          <input
            class="form-check-input"
            type="radio"
            name="exam-id"
            id="flexRadioDefault<?php echo $num3;?>"
            value="<?= $row4['exam_id'];?>"
          required/>
          <label class="form-check-label" for="flexRadioDefault<?php echo $num3;?>"> <?= $row4['exam_name'];?></label>
          </div>
        <?php $num3++;} ?> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="send-exam" class="btn btn-floating btn-primary"><i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- End of Add Exam Modal -->
</body>
  <?php require_once 'constants/scripts.php'?>
</html>