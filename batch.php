<?php 

require_once 'controllers/authController.php';
require_once 'controllers/createController.php'; 
require_once 'controllers/updateController.php'; 

if (!isset($_SESSION['admin_id'])) {
  header('location: schlradminlogin.php');
  exit();
} 

$selectBatch = $conn->query("SELECT * from tbl_batch ORDER BY batch_num DESC");

if(isset($_POST['edit-batch-modal'])){
  $modalClass1 = "modalBatch";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $batchquery = mysqli_query($conn, "SELECT * FROM tbl_batch WHERE batch_num='$id' LIMIT 1");
      $queries2 = mysqli_fetch_all($batchquery, MYSQLI_ASSOC);
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
  <title>Batch | ICoNS</title>
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
          <a href="batch.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
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
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-10">
            <?php if(count($errors) > 0): ?>
        <div class="alert alert-danger mt-0">
          <?php foreach($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>      
          </div>   
          <div class="col-md-2">
            <div class="text-end mb-4"><button type="button" data-mdb-toggle="modal" data-mdb-target="#staticBackdrop" class="btn btn-primary" id="btnAddExam">
                  <i class="fas fa-plus"></i> Add Batch
                </button></div>
          </div>
        </div>
      </div>
      
      
      <div id="tableBatch" style="<?php echo $display2; ?>"> 
      <form action="batch.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
            <div class="col-md-6">
              <h5 class="mb-0 py-2">
              <strong>Batch List</strong>
            </h5>
            </div>
            <div class="col-md-6 text-end">
              <button type="submit" name="edit-batch-modal" class="btn btn-warning" id="btnEditBatch" style="display: none;">
                  <i class="fas fa-edit"></i> Change No. of Slots
                </button>  
            </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectBatch) > 0) { ?>
              <table id="dtBatch" class="table table-hover" cellspacing="0" width="100%">
  <thead>
    <tr style="border-bottom: solid 3px lightgray;">
      <th><input type="checkbox" id="checkAll6" class="form-check-input"></th>
      <th class="th-sm">Batch
      </th>
      <th class="th-sm">Year
      </th>
      <th class="th-sm">Number of Scholars
      </th>
      <th class="th-sm">Graduated
      </th>
      <th class="th-sm">Employed
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
      $counter = 1;
      while($row=mysqli_fetch_array($selectBatch)){
        $batch_num = $row['batch_num'];

        $selectStudentBatch = $conn->query("SELECT * from tbl_student_batch WHERE batch_num='$batch_num'");

        $num_scholars = 0;
        if (mysqli_num_rows($selectStudentBatch) > 0) {
          while ($row2=mysqli_fetch_array($selectStudentBatch)) {
            $student_id = $row2['student_id'];
            $selectScholarshipInfo = $conn->query("SELECT * from tbl_scholarship_info WHERE student_id='$student_id' LIMIT 1");
            $num_scholars += mysqli_num_rows($selectScholarshipInfo);
          }
        } else {
          $num_scholars = 0;
        }
    ?>
    <tr>
      <td><input type="checkbox" class="checkItem6 form-check-input" value="<?= $row['batch_num'];?>" name="id[]"></td>
      <td><?= "Batch ".$batch_num;?></td>
      <td><?= $row['year'];?></td>
      <td><?= $num_scholars;?></td>
      <td>0</td>
      <td>0</td>
    </tr>
  <?php } ?>
  </tbody>
  <tfoot>
    <tr>
      <th>
      </th>
      <th class="th-sm">Batch
      </th>
      <th class="th-sm">Year
      </th>
      <th class="th-sm">Number of Scholars
      </th>
      <th class="th-sm">Graduated
      </th>
      <th class="th-sm">Employed
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
  <form action="batch.php" method="post">
<div class="modal fade staticBackdrop" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Add Batch
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <div class="form-outline">
          <?php
          $batch_number = 1;

          $selectBatchNum = "SELECT batch_num FROM tbl_batch ORDER BY batch_num DESC LIMIT 1";
          $batchNumResult = mysqli_query($conn, $selectBatchNum);

          $batchNum = mysqli_fetch_array($batchNumResult, MYSQLI_ASSOC);
          if (!isset($batchNum['batch_num'])) {
            
        ?>
          <input type="number" id="inputBatchNum" name="batch" class="form-control form-control-lg required" value="<?php echo $batch_number; ?>" readonly>
          <?php
        } else {
          ?>
          <input type="number" id="inputBatchNum" name="batch" class="form-control form-control-lg required" value="<?php echo $batchNum['batch_num'] + 1; ?>" readonly>
        <?php }

          ?>
          <label class="form-label" for="inputBatchNum">Batch Number</label>

        </div>
        <div class="form-outline mt-3">
          <input type="number" id="inputNumSlots" name="num-slots" class="form-control form-control-lg required" required>
          <label class="form-label" for="inputNumSlots">Number of Slots</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="add-batch" class="btn btn-floating btn-primary"><i class="fas fa-plus"></i></button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- Edit Num Slots Modal -->
<form action="batch.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass1; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Edit Number of Slots
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <div class="form-outline">
          <input type="number" id="inputBatchNum" name="batch-num" class="form-control form-control-lg" 
                 value="<?php foreach ($queries2 as $query2): echo $query2['batch_num']; endforeach; ?>" readonly>
          <label class="form-label" for="inputBatchNum">Batch Number</label>
        </div>
        <div class="form-outline mt-3">
          <input type="text" id="inputNumSlots" name="num-slots" class="form-control form-control-lg"
                 value="<?php foreach ($queries2 as $query2): echo $query2['num_slots']; endforeach; ?>">
          <label class="form-label" for="inputNumSlots">Number of Slots</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="edit-batch" class="btn btn-floating btn-warning"><i class="fas fa-check"></i></button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- End of Edit Num Slots Modal -->
</body>
  <?php require_once 'constants/scripts.php'?>
</html>