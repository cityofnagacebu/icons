<?php 

require_once 'controllers/authController.php';
require_once 'controllers/createController.php'; 
require_once 'controllers/updateController.php'; 

if (!isset($_SESSION['admin_id'])) {
  header('location: schlradminlogin.php');
  exit();
} 

$date_now = date("Y-m-d");

$selectSchedule = $conn->query("SELECT * from tbl_schedules WHERE applicable_for='All' AND status<>'Closed' ORDER BY date_start DESC");

if(isset($_POST['delete-schedules'])){
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $delQuery1="DELETE from tbl_schedules WHERE schedule_id='$id'";
      mysqli_query($conn, $delQuery1);
    }
    header('location: schedule.php');
    exit();
  }
}

$display2 = "display: none;";
$display1 = "";

if(isset($_POST['edit-schedule'])){
  $display2 = "";
  $display1 = "display: none;";

  $selectSchedule = $conn->query("SELECT * from tbl_schedules WHERE applicable_for='All' AND status<>'Closed' ORDER BY date_start DESC");

  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectSchedule2 = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE schedule_id='$id' LIMIT 1");
      $queries1 = mysqli_fetch_all($selectSchedule2, MYSQLI_ASSOC);
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
  <title>Schedule | ICoNS</title>
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
  <link rel="stylesheet" href="css/datepicker.min.css"/>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
    crossorigin="anonymous"></script>
    <style>
      input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none !important; 
    margin: 0; 
}

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
          <a href="exam_results.php" class="list-group-item list-group-item-action py-2 ripple">
            <i class="fas fa-chart-bar fa-fw me-3"></i><span>Exam Results</span>
          </a>
          <a href="schedule.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
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
      <form action="schedule.php" method="POST">
      <div style="<?php echo $display1; ?>">
      <div class="col-md-12" id="hideCreateSchedule">
        <div class="row">
          <div class="col-md-9">
            <?php if(count($errors) > 0): ?>
            <div class="alert alert-danger mt-0">
              <?php foreach($errors as $error): ?>
              <li><?php echo $error; ?></li>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>      
          </div>
          <div class="col-md-3">
            <div class="text-end mb-4">
              <button type="button" class="btn btn-primary" id="btnAddSchedule">
                  <i class="fas fa-plus"></i> Create Schedule
              </button>
            </div>
          </div>
        </div>
      </div>   
      <div class="col-md-12" id="addSchedule" style="display: none;">
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="form-label-group">
              <select class="form-select form-control required" aria-label="Default select example" name="activity" style="height: 45px;" required autofocus>
                <option selected value="">Activity</option>
                <option value="Acceptance of Applicants">Acceptance of Applicants</option>
                <option value="Qualifying Examination">Qualifying Examination</option>
                <option value="Enrollment Period">Enrollment Period</option>
              </select>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="form-outline">
              <input data-toggle="datepicker" type="text" class="form-control form-control-lg required" name="date-start" value="<?php date_default_timezone_set('Asia/Manila'); echo date("Y-m-d");?>" id="inputDateStart">
              <label class="form-label" for="inputDateStart">Date Start</label>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="form-outline">
              <input data-toggle="datepicker" type="text" class="form-control form-control-lg required" name="date-end" value="<?php date_default_timezone_set('Asia/Manila'); $tomorrow = date("Y-m-d", time() + 86400); echo $tomorrow;?>" id="inputDateEnd">
              <label class="form-label" for="inputDateEnd">Date End</label>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="text-end">
              <button type="button" class="btn btn-light" id="btnCancelSchedule">
                <i class="fas fa-times"></i> Cancel
              </button>
              <button type="submit" class="btn btn-primary" id="btnCreateSchedule" name="create-schedule">
                <i class="fas fa-check"></i> Create
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>
    </form>
      <form action="schedule.php" method="POST">
      <div style="<?php echo $display2; ?>">
      <div class="col-md-12" id="editSchedule">
        <div class="row">
          <div class="col-md-3 mb-3">
            <input type="number" name="schedule-id" value="<?php foreach ($queries1 as $query1): echo $query1['schedule_id']; endforeach; ?>" hidden>
            <div class="form-label-group">
              <select class="form-select form-control required" aria-label="Default select example" name="activity" style="height: 45px;" required readonly>
                <option selected value="<?php foreach ($queries1 as $query1): echo $query1['activity']; endforeach; ?>"><?php foreach ($queries1 as $query1): echo $query1['activity']; endforeach; ?></option>
              </select>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="form-outline">
              <input data-toggle="datepicker" type="text" class="form-control form-control-lg required" name="date-start" value="<?php foreach ($queries1 as $query1): echo $query1['date_start']; endforeach; ?>" id="inputDateStart">
              <label class="form-label" for="inputDateStart">Date Start</label>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="form-outline">
              <input data-toggle="datepicker" type="text" class="form-control form-control-lg required" name="date-end" value="<?php foreach ($queries1 as $query1): echo $query1['date_end']; endforeach; ?>" id="inputDateEnd">
              <label class="form-label" for="inputDateEnd">Date End</label>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="text-end">
              <a href="schedule.php" style="text-decoration: none;"><button type="button" class="btn btn-light" id="btnCancelSchedule">
                <i class="fas fa-times"></i> Cancel
              </button></a>
              <button type="submit" class="btn btn-primary" id="btnUpdateSchedule" name="update-schedule">
                <i class="fas fa-check"></i> Done
              </button>
            </div>
          </div>
        </div>
      </div>
      </div>
      </form>
      
      <div id="tableSchedules"> 
      <form action="schedule.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2">
              <strong>Schedules</strong>
            </h5>
            </div>
            <div class="col-md-6 text-end">
              <button type="submit" name="edit-schedule" class="btn btn-warning" id="btnEditSchedule" style="display: none;">
                  <i class="fas fa-edit"></i> Modify
                </button>
                <button type="submit" name="delete-schedules" onclick="return confirm('Are you sure you want to remove this schedule/s?')" class="btn btn-danger" id="btnDeleteSchedule" style="display: none;">
                <i class="fas fa-trash-alt"></i> Delete
              </button>  
            </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectSchedule) > 0) { ?>
              <table id="dtSchedules" class="table table-hover" cellspacing="0" width="100%">
  <thead>
    <tr style="border-bottom: solid 3px lightgray;">
      <th><input type="checkbox" id="checkAll7" class="form-check-input"></th>
      <th class="th-sm">Activity
      </th>
      <th class="th-sm">Date Start
      </th>
      <th class="th-sm">Date End
      </th>
      <th class="th-sm">Status
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
      while($row=mysqli_fetch_array($selectSchedule)){
    ?>
    <tr>
      <td><input type="checkbox" class="checkItem7 form-check-input" value="<?= $row['schedule_id'];?>" name="id[]"></td>
      <td><?= $row['activity'];?></td>
      <td><?= $row['date_start'];?></td>
      <td><?= $row['date_end'];?></td>
      <td><?= $row['status'];?></td>
    </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th>
      </th>
      <th class="th-sm">Activity
      </th>
      <th class="th-sm">Date Start
      </th>
      <th class="th-sm">Date End
      </th>
      <th class="th-sm">Status
      </th>
    </tr>
  </tfoot>
</table>
<?php 
                } else { ?>
              <div id="zeroSelected"><small><i>No schedules yet</i></small></div>
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
</body>
  <?php require_once 'constants/scripts.php'?>
</html>