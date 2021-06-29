<?php 

require_once 'controllers/authController.php';
require_once 'controllers/createController.php'; 

if (!isset($_SESSION['admin_id'])) {
  header('location: schlradminlogin.php');
  exit();
} 

$selectScores = $conn->query("SELECT * from tbl_exam_results ORDER BY date_taken DESC");

if(isset($_POST['delete-exam-results'])){
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $delQuery1="DELETE from tbl_exam_results WHERE score_id='$id'";
      mysqli_query($conn, $delQuery1);
    }
    header('location: exam_results.php');
    exit();
  }
}

$display1 = "display: none;";
$display2 = "";

$modalClass1 = "";
$modalClass2 = "";

if(isset($_POST['add-to-qualified'])){
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectScores1 = mysqli_query($conn, "SELECT * FROM tbl_exam_results WHERE score_id='$id' LIMIT 1");
      $queries9 = mysqli_fetch_all($selectScores1, MYSQLI_ASSOC);
      foreach ($queries9 as $query9) {
      if ($query9['remark'] == "Failed") {
        $remark_message = "The applicant failed the exam.";
        $modalClass1 = "enrollmodal1";
      } else if ($query9['remark'] == "") {
        $remark_message = "The applicant has no result of the exam.";
        $modalClass1 = "enrollmodal1";
      }
      else {
        $applicant_id = $query9['student_id'];
        $selectApplicants = mysqli_query($conn, "SELECT * FROM tbl_students WHERE student_id='$applicant_id' LIMIT 1");
        $queries10 = mysqli_fetch_all($selectApplicants, MYSQLI_ASSOC);
        $modalClass2 = "enrollmodal2";
      }
    }
    }
    $selectScores = $conn->query("SELECT * from tbl_exam_results ORDER BY date_taken DESC");
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
  <title>Exam Results | ICoNS</title>
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
          <a href="scholars.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-user-graduate fa-fw me-3"></i><span>Scholars</span>
          </a>
          <a href="examination.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-file-alt fa-fw me-3"></i><span>Examination</span>
          </a>
          <a href="exam_results.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
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
      <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger">
          <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div id="tableExamResults" style="<?php echo $display2; ?>"> 
      <form action="exam_results.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2">
              <strong>Exam Results</strong>
            </h5>
            </div>
            <div class="col-md-6 text-end">
              <button type="submit" name="add-to-qualified" class="btn btn-success" id="btnAddToScholars" style="display: none;">
                <i class="fas fa-door-open"></i> Add to Qualified
              </button>
              <button type="submit" name="delete-exam-results" onclick="return confirm('Are you sure you want to remove this exam result/s?')" class="btn btn-danger" id="btnDeleteExamResults" style="display: none;">
                <i class="fas fa-trash-alt"></i> Delete
              </button>   
            </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectScores) > 0) { ?>
              <table id="dtExamResults" class="table table-hover" cellspacing="0" width="100%">
  <thead>
    <tr style="border-bottom: solid 3px lightgray;">
      <th><input type="checkbox" id="checkAll4" class="form-check-input"></th>
      <th class="th-sm">ID
      </th>
      <th class="th-sm">Applicant Name
      </th>
      <th class="th-sm">Exam Description
      </th>
      <th class="th-sm">Score
      </th>
      <th class="th-sm">Date Taken
      </th>
      <th class="th-sm">Remark
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
      $counter = 1;
      while($row=mysqli_fetch_array($selectScores)){
        $exam_id = $row['exam_id'];

        $applicantQuery = "SELECT * FROM tbl_students WHERE student_id=? LIMIT 1";
        $stmt = $conn->prepare($applicantQuery);
        $stmt->bind_param('s', $row['student_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $applicantResult = $result->fetch_assoc();

        if (!empty($applicantResult)) {
          $applicant_name = $applicantResult['last_name'].", ".$applicantResult['first_name']." ".$applicantResult['ext_name']." ".substr($applicantResult['middle_name'], 0, 1).".";
        }
        

        $examQuery = "SELECT * FROM tbl_exam WHERE exam_id=? LIMIT 1";
        $stmt2 = $conn->prepare($examQuery);
        $stmt2->bind_param('d', $row['exam_id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $examResult = $result2->fetch_assoc();

        if (!empty($examResult)) {
          $exam_name = $examResult['exam_name'];
        }

        $questionQuery = $conn->query("SELECT * from tbl_questions WHERE exam_id='$exam_id'");

        $qpoints = 0;

        while($row2=mysqli_fetch_array($questionQuery)){
          $qpoints += $row2['points'];
        }
    ?>
    <tr>
      <td><input type="checkbox" class="checkItem4 form-check-input" value="<?= $row['score_id'];?>" name="id[]"></td>
      <td><?= $row['student_id'];?></td>
      <td><?= $applicant_name;?></td>
      <td><?= $exam_name;?></td>
      <td><?= $row['score']."/".$qpoints;?></td>
      <td><?= $row['date_taken'];?></td>
      <td><?= $row['remark'];?></td>
    </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th>
      </th>
      <th class="th-sm">ID
      </th>
      <th class="th-sm">Applicant Name
      </th>
      <th class="th-sm">Exam Description
      </th>
      <th class="th-sm">Score
      </th>
      <th class="th-sm">Date Taken
      </th>
      <th class="th-sm">Remark
      </th>
    </tr>
  </tfoot>
</table>
<?php 
                } else { ?>
              <div id="zeroSelected"><small><i>No exam results yet</i></small></div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
        </form>
        </div>
      <!--Section: Applicants-->
    </div>
  </main>
  <!--Main layout-->
  <div class="modal fade staticBackdrop <?php echo $modalClass1; ?>" id="startExamDialog" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white mx-auto" id="staticBackdropLabel">Sorry!</h5>
        </div>
        <div class="modal-body px-4 py-4 text-center">
          <p>You cannot enroll this applicant.</p>
          <p><strong><?php echo $remark_message; ?></strong></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-lg mx-auto" data-mdb-dismiss="modal">
            <i class="fas fa-times"></i> Close
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- Add Exam Modal -->
<form action="exam_results.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass2; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Confirmation Message
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <p>Are you sure you want to add <strong><?php foreach ($queries10 as $query10): echo $query10['first_name']." ".substr($query10['middle_name'], 0, 1).". ".$query10['last_name']." ".$query10['ext_name']; endforeach; ?></strong> to qualified as scholar?</p>
        <input type="text" name="applicant-id" value="<?php foreach ($queries10 as $query10): echo $query10['student_id']; endforeach; ?>" hidden>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="add-qualified" class="btn btn-floating btn-success"><i class="fas fa-plus"></i></button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- End of Add Exam Modal -->
</body>
  <?php require_once 'constants/scripts.php'?>
</html>