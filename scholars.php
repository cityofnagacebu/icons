<?php 

require_once 'controllers/authController.php';
require_once 'controllers/createController.php'; 
require_once 'controllers/updateController.php'; 

if (!isset($_SESSION['admin_id'])) {
  header('location: schlradminlogin.php');
  exit();
}

$selectScholars = $conn->query("SELECT * from tbl_scholarship_info ORDER BY date_enrolled DESC");

$display1 = "display: none;";
$display2 = "";

if(isset($_POST['view-scholar'])){
  $display1 = "";
  $display2 = "display: none;";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectApplicant = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$id' LIMIT 1");
          $queries1 = mysqli_fetch_all($selectApplicant, MYSQLI_ASSOC);

          $selectContactDetails = mysqli_query($conn, "SELECT * FROM tbl_contact_details WHERE student_id='$id' LIMIT 1");
          $queries4 = mysqli_fetch_all($selectContactDetails, MYSQLI_ASSOC);

          $selectAddress = mysqli_query($conn, "SELECT * FROM tbl_address WHERE student_id='$id' LIMIT 1");
          $queries3 = mysqli_fetch_all($selectAddress, MYSQLI_ASSOC);

          $selectApplicationInfo = mysqli_query($conn, "SELECT * FROM tbl_application_info WHERE student_id='$id' LIMIT 1");
          $queries5 = mysqli_fetch_all($selectApplicationInfo, MYSQLI_ASSOC);

          $selectScholarshipInfo = mysqli_query($conn, "SELECT * FROM tbl_scholarship_info WHERE student_id='$id' LIMIT 1");
          $queries7 = mysqli_fetch_all($selectScholarshipInfo, MYSQLI_ASSOC);

          $selectParent = mysqli_query($conn, "SELECT * FROM tbl_parents WHERE student_id='$id' LIMIT 1");
          $queries6 = mysqli_fetch_all($selectParent, MYSQLI_ASSOC);

          $selectFiles = mysqli_query($conn, "SELECT * FROM tbl_files WHERE student_id='$id'");
          $queries2 = mysqli_fetch_all($selectFiles, MYSQLI_ASSOC);
    }
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
  <title>Scholars | ICoNS</title>
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
          <a href="applicants.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-users fa-fw me-3"></i><span>Applicants</span>
          </a>
          <a href="scholars.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
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
      <div id="tableScholars" style="<?php echo $display2; ?>"> 
      <form action="scholars.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2">
              <strong>Scholars</strong>
            </h5>
            </div>
            <div class="col-md-6 text-end">
              <button type="submit" name="approve" class="btn btn-primary" id="btnApprove" style="display: none;">
                <i class="fas fa-thumbs-up"></i> Approve
              </button>
              <button type="submit" name="view-scholar" class="btn btn-success" id="btnScholarDetails" style="display: none;">
                <i class="fas fa-eye"></i> Details
              </button>  
            </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectScholars) > 0) { ?>
              <table id="dtScholars" class="table table-hover" cellspacing="0" width="100%">
  <thead>
    <tr style="border-bottom: solid 3px lightgray;">
      <th><input type="checkbox" id="checkAll5" class="form-check-input"></th>
      <th class="th-sm">ID
      </th>
      <th class="th-sm">Name
      </th>
      <th class="th-sm">School
      </th>
      <th class="th-sm">Course
      </th>
      <th class="th-sm">Year
      </th>
      <th class="th-sm">Semester
      </th>
      <th class="th-sm">Status
      </th>
      <th class="th-sm">Date Enrolled
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
      $counter = 1;
      while($row=mysqli_fetch_array($selectScholars)){
        $student_id = $row['student_id'];

        $selectStudent = $conn->query("SELECT * from tbl_students WHERE student_id='$student_id' LIMIT 1");
        $queryStudents=mysqli_fetch_array($selectStudent);
    ?>
    <tr>
      <td><input type="checkbox" class="checkItem5 form-check-input" value="<?= $queryStudents['student_id'];?>" name="id[]"></td>
      <td><?= $queryStudents['student_id'];?></td>
      <td><?= $queryStudents['first_name'].' '.substr($queryStudents['middle_name'], 0, 1).'. '.$queryStudents['last_name'].' '.$queryStudents['ext_name'];?></td>
      <td><?= $row['school'];?></td>
      <td><?= $row['course'];?></td>
      <td><?= $row['year'];?></td>
      <td><?= $row['semester'];?></td>
      <td><?= $row['scholarship_status'];?></td>
      <td><?= $row['date_enrolled'];?></td>
    </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th>
      </th>
      <th class="th-sm">ID
      </th>
      <th class="th-sm">Name
      </th>
      <th class="th-sm">School
      </th>
      <th class="th-sm">Course
      </th>
      <th class="th-sm">Year
      </th>
      <th class="th-sm">Semester
      </th>
      <th class="th-sm">Status
      </th>
      <th class="th-sm">Date Enrolled
      </th>
    </tr>
  </tfoot>
</table>
<?php 
                } else { ?>
              <div id="zeroSelected"><small><i>No scholars yet</i></small></div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
        </form>
        </div>
      <!--Section: Applicants-->

      <!--Section: Applicants-->
      <div id="details" style="<?php echo $display1; ?>">
      <form action="scholars.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2"><a href="scholars.php" style="font-family: 'Roboto Thin';"><strong>Scholars</strong></a> /
              <strong>
                <?php
                  foreach ($queries1 as $query1):
                    echo $query1['student_id'];
                  endforeach;
                ?>
              </strong>
            </h5>
            <input type="number" name="id[]" value="<?php foreach ($queries1 as $query1): echo $query1['scholar_id']; endforeach; ?>" hidden>
            </div>
            <div class="col-md-6 text-end">
              <!-- <button type="submit" name="email-applicant2" class="btn btn-primary" id="btnSendEmail"><i class="fas fa-envelope"></i> Email</button>
              <button type="submit" name="send-exam-modal2" class="btn btn-warning" id="btnSendExam"><i class="fas fa-paper-plane"></i> Send Exam</button>
              <button type="submit" name="delete-applicant" onclick="return confirm('Are you sure you want to remove this applicant?')" class="btn btn-danger" id="btnDelete">
                <i class="fas fa-trash-alt"></i> Delete
              </button>    -->
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
                    <span></span>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="list-group list-group-flush">
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
                    <span></span>
                  </ul>
                </div>
              </div>
              <?php if (mysqli_num_rows($selectScholarshipInfo) > 0) { ?>
              <div class="row">
                <div class="col-md-6">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>School</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries7 as $query7):
                                echo $query7['school'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col" style="font-family: 'Roboto Thin';"><strong>Course</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries7 as $query7):
                                echo $query7['course'];
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
                        <div class="col mb-4" style="font-family: 'Roboto Thin';"><strong>Year Level</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries7 as $query7):
                                echo $query7['year'];
                              endforeach;
                            ?>
                          </strong>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="row">
                        <div class="col mb-4" style="font-family: 'Roboto Thin';"><strong>Semester</strong></div>
                        <div class="col">
                          <strong>
                            <?php
                              foreach ($queries7 as $query7):
                                echo $query7['semester'];
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
            <?php } ?>
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
</body>
  <?php require_once 'constants/scripts.php'?>
</html>