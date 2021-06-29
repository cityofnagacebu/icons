<?php 

require_once 'controllers/authController.php';
require_once 'controllers/createController.php';
require_once 'controllers/updateController.php'; 


if (!isset($_SESSION['admin_id'])) {
  header('location: schlradminlogin.php');
  exit();
} 

$selectExams = $conn->query("SELECT * from tbl_exam");

if(isset($_POST['delete-exam'])){
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $delQuery1="DELETE from tbl_exam WHERE exam_id='$id'";
      mysqli_query($conn, $delQuery1);

      $selectQuery = $conn->query("SELECT question_id from tbl_questions WHERE exam_id='$id'");
      $counter = 1;
      while($row3=mysqli_fetch_array($selectQuery)){
        $question_id = $row3['question_id'];
        $delQuery2="DELETE from tbl_choices WHERE question_id='$question_id'";
        mysqli_query($conn, $delQuery2);
      }

      $delQuery3="DELETE from tbl_questions WHERE exam_id='$id'";
      mysqli_query($conn, $delQuery3);
    }
    header('location: examination.php');
    exit();
  }
}

if(isset($_POST['delete-question'])){
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $delQuery1="DELETE from tbl_choices WHERE question_id='$id'";
      mysqli_query($conn, $delQuery1);

      $delQuery2="DELETE from tbl_questions WHERE question_id='$id'";
      mysqli_query($conn, $delQuery2);
    }
  }
  
  if(isset($_POST['exam-id'])){
    foreach ($_POST['exam-id'] as $id) {
      $questionQuery2 = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");

      $items = mysqli_num_rows($questionQuery2);
      $qpoints = 0;

      $counter = 1;
      while($row=mysqli_fetch_array($questionQuery2)){
        $qpoints += $row['points'];
      }

      $passing_score = $qpoints * 0.75;
      $time_limit = ($items * 0.75) * 60000;

      $update_query = "UPDATE tbl_exam SET num_items = '$items', passing_score = '$passing_score', time_limit = '$time_limit' WHERE exam_id = '$id'";
      mysqli_query($conn, $update_query);
    }
  }
  header('location: examination.php');
  exit();
}

$display1 = "";
$display2 = "display: none;";
$display3 = "display: none;";

if(isset($_POST['view-questions'])){
  $display1 = "display: none;";
  $display2 = "";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectExam = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
      $queries1 = mysqli_fetch_all($selectExam, MYSQLI_ASSOC);

      $selectQuestions = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");
    } 
  }
}

if(isset($_GET['exam'])){
  $display1 = "display: none;";
  $display2 = "";
  $id = $_GET['exam'];
      $selectExam = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
      $queries1 = mysqli_fetch_all($selectExam, MYSQLI_ASSOC);

      $selectQuestions = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");
}

$modalClass1 = "";
$modalClass2 = "";
$modalClass3 = "";
$modalClass4 = "";

if(isset($_POST['edit-examine'])){
  $modalClass1 = "modalExam";
  if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectExam2 = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
      $queries3 = mysqli_fetch_all($selectExam2, MYSQLI_ASSOC);
    }
  }
}

if(isset($_POST['modal-add-question'])){
  $display1 = "display: none;";
  $display2 = "";
  if(isset($_POST['exam-id'])){
    foreach ($_POST['exam-id'] as $id) {
      $selectExam = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
      $queries1 = mysqli_fetch_all($selectExam, MYSQLI_ASSOC);

      $selectQuestions = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");
      $modalClass2 = "modalExam";
      $selectExam3 = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
      $queries4 = mysqli_fetch_all($selectExam3, MYSQLI_ASSOC);
    }
  }
}

if(isset($_POST['modal-edit-question'])){
  $display1 = "display: none;";
  $display2 = "";
  if(isset($_POST['exam-id'])){
    foreach ($_POST['exam-id'] as $id) {
    $selectExam = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
    $queries1 = mysqli_fetch_all($selectExam, MYSQLI_ASSOC);
    $selectQuestions = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");
    $modalClass3 = "modalExam";
    }
  }
    if(isset($_POST['id'])){
      foreach ($_POST['id'] as $id) {
        $selectQuestion = $conn->query("SELECT * from tbl_questions WHERE question_id='$id'");
        $queries5 = mysqli_fetch_all($selectQuestion, MYSQLI_ASSOC);

        $selectChoices = $conn->query("SELECT * from tbl_choices WHERE question_id='$id'");
      }
    }
}

if(isset($_POST['view-full'])){
  $display1 = "display: none;";
  $display2 = "";
  if(isset($_POST['exam-id'])){
    foreach ($_POST['exam-id'] as $id) {
    $selectExam = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
    $queries1 = mysqli_fetch_all($selectExam, MYSQLI_ASSOC);
    $selectQuestions = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");
    $modalClass4 = "modalExam";
    }
  }
    if(isset($_POST['id'])){
      foreach ($_POST['id'] as $id) {
        $selectQuestion2 = $conn->query("SELECT * from tbl_questions WHERE question_id='$id'");
        $queries7 = mysqli_fetch_all($selectQuestion2, MYSQLI_ASSOC);

        $selectChoices2 = $conn->query("SELECT * from tbl_choices WHERE question_id='$id'");
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
  <title>Examinations | ICoNS</title>
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

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none !important; 
      margin: 0; 
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
          <a href="examination.php" class="list-group-item list-group-item-action py-2 ripple active" aria-current="true">
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
                  <i class="fas fa-folder-plus"></i> Add Exam
                </button></div>
          </div>
        </div>
      </div>
      <!--Section: Exams-->
      <div id="tableExams" style="<?php echo $display1; ?>"> 
      <form action="examination.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header position-sticky">
            <div class="row">
              <div class="col-md-6">
                <h5 class="mb-0 py-2">
                  <strong>Examination</strong>
                </h5>
              </div>
              <div class="col-md-6 text-end">
                <button type="submit" name="delete-exam" onclick="return confirm('Are you sure you want to remove this exam/s?')" class="btn btn-danger" id="btnDelete" style="display: none;">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
                <button type="submit" name="edit-examine" class="btn btn-warning" id="btnEdit" style="display: none;">
                  <i class="fas fa-edit"></i> Edit
                </button> 
                <button type="submit" name="view-questions" class="btn btn-success" id="btnViewQuestion" style="display: none;">
                  <i class="fas fa-eye"></i> Questions
                </button>  
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectExams) > 0) { ?>
              <table id="dtExams" class="table table-hover" cellspacing="0" width="100%">
                <thead>
                  <tr style="border-bottom: solid 3px lightgray;">
                    <th><input type="checkbox" id="checkAll2" class="form-check-input"></th>
                    <th class="th-sm">Description</th>
                    <th class="th-sm">Exam Type</th>
                    <th class="th-sm">No. of Items</th>
                    <th class="th-sm">Passing Score</th>
                    <th class="th-sm">Duration</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    while($row2=mysqli_fetch_array($selectExams)){
                  ?>
                  <tr>
                    <td><input type="checkbox" class="checkItem2 form-check-input" value="<?= $row2['exam_id'];?>" name="id[]"></td>
                    <td><?= $row2['exam_name'];?></td>
                    <td><?= $row2['type'];?></td>
                    <td><?= $row2['num_items'];?></td>
                    <td><?= $row2['passing_score'];?></td>
                    <td><?= floor($row2['time_limit']/60000).':'.floor(($row2['time_limit']%60000)/1000);?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th class="th-sm">Description</th>
                    <th class="th-sm">Exam Type</th>
                    <th class="th-sm">No. of Items</th>
                    <th class="th-sm">Passing Score</th>
                    <th class="th-sm">Duration</th>
                  </tr>
                </tfoot>
              </table>
              <?php 
                } else { ?>
              <div id="zeroSelected"><small><i>No exams yet</i></small></div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
      </form>
      </div>
      <!--Section: Exams-->
      <!--Section: Questions-->
      <div id="tableQuestions" style="<?php echo $display2; ?>"> 
      <form action="examination.php" method="POST">
      <section class="mb-4">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h5 class="mb-0 py-2"><a href="examination.php" style="font-family: 'Roboto Thin';"><strong>Examination</strong></a> /
                <strong>
                <?php
                  foreach ($queries1 as $query1):
                    echo $query1['exam_name'];
                  endforeach;
                ?>
                </strong>
                </h5>
                <input type="number" name="exam-id[]" value="<?php foreach ($queries1 as $query1): echo $query1['exam_id'];endforeach;?>" hidden>
              </div>
              <div class="col-md-6 text-end">
                <button type="submit" name="modal-add-question" class="btn btn-primary" id="btnAddQuestion">
                  <i class="fas fa-plus-square"></i> Add Question
                </button>
                <button type="submit" name="delete-question" onclick="return confirm('Are you sure you want to remove this question/s?')" class="btn btn-danger" id="btnDeleteQuestion" style="display: none;">
                  <i class="fas fa-trash-alt"></i> Delete
                </button>
                <button type="submit" name="modal-edit-question" class="btn btn-warning" id="btnEditItem" style="display: none;">
                  <i class="fas fa-edit"></i> Edit Item
                </button> 
                <button type="submit" name="view-full" class="btn btn-success" id="btnViewFull" style="display: none;">
                  <i class="fas fa-eye"></i> View Full
                </button>  
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <?php if (mysqli_num_rows($selectQuestions) > 0) { ?>
              <table id="dtQuestions" class="table table-hover" cellspacing="0" width="100%">
                <thead>
                  <tr style="border-bottom: solid 3px lightgray;">
                    <th><input type="checkbox" id="checkAll3" class="form-check-input"></th>
                    <th class="th-sm">Question</th>
                    <th class="th-sm">Answer</th>
                    <th class="th-sm">Points</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $counter = 1;
                    while($row4=mysqli_fetch_array($selectQuestions)){
                  ?>
                  <tr>
                    <td><input type="checkbox" class="checkItem3 form-check-input" value="<?= $row4['question_id'];?>" name="id[]"></td>
                    <td><?= $row4['question'];?></td>
                    <td><?= $row4['answer'];?></td>
                    <td><?= $row4['points'];?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th class="th-sm">Question</th>
                    <th class="th-sm">Answer</th>
                    <th class="th-sm">Points</th>
                  </tr>
                </tfoot>
              </table>
              <?php 
                } else { ?>
              <div id="zeroSelected"><small><i>No questions yet</i></small></div>
              <?php } ?>
            </div>
          </div>
        </div>
      </section>
      </form>
      </div>
      <!--Section: Questions-->
    </div>
  </main>
  <!--Main layout-->
    <!-- Modal -->

<!-- Add Exam Modal -->
<form action="examination.php" method="post">
<div class="modal fade staticBackdrop" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Add Examination
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
          <input type="text" id="inputExam" name="exam" class="form-control form-control-lg required" required>
          <label class="form-label" for="inputExam">Description</label>
        </div>
        <div class="form-outline mt-3">
          <input type="text" id="inputType" name="type" class="form-control form-control-lg required" required>
          <label class="form-label" for="inputType">Type of Exam Questions</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="add-exam" class="btn btn-floating btn-primary"><i class="fas fa-plus"></i></button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- End of Add Exam Modal -->
<!-- Edit Exam Modal -->
<form action="examination.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass1; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Edit Examination
        </h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <input type="number" name="exam-id" value="<?php foreach ($queries3 as $query3): echo $query3['exam_id']; endforeach; ?>" hidden>
        <div class="form-outline">
          <input type="text" id="inputExam" name="exam" class="form-control form-control-lg" 
                 value="<?php foreach ($queries3 as $query3): echo $query3['exam_name']; endforeach; ?>">
          <label class="form-label" for="inputExam">Description</label>
        </div>
        <div class="form-outline mt-3">
          <input type="text" id="inputType" name="type" class="form-control form-control-lg"
                 value="<?php foreach ($queries3 as $query3): echo $query3['type']; endforeach; ?>">
          <label class="form-label" for="inputType">Type of Exam Questions</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="edit-exam" class="btn btn-floating btn-primary"><i class="fas fa-check"></i></button>
      </div>
    </div>
  </div>
</div>
</form>
<!-- End of Edit Exam Modal -->
<!-- Add Question Modal -->
<form action="examination.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass2; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Add Question to <?php foreach ($queries4 as $query4): echo $query4['exam_name']; endforeach; ?>
        </h5>
        <input type="number" name="exam-id" value="<?php foreach ($queries4 as $query4): echo $query4['exam_id']; endforeach; ?>" hidden>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <div class="form-outline">
          <textarea class="form-control" name="question" id="textAreaQuestion" rows="3"></textarea>
          <label class="form-label" for="textAreaQuestion">Question</label>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-outline mt-3">
              <input type="text" id="inputChoice1" name="choice[]" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputChoice1">Choice 1</label>
            </div>
          </div>
          <div class="col">
            <div class="form-outline mt-3">
              <input type="text" id="inputChoice2" name="choice[]" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputChoice2">Choice 2</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-outline mt-3">
              <input type="text" id="inputChoice3" name="choice[]" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputChoice3">Choice 3</label>
            </div>
          </div>
          <div class="col">
            <div class="form-outline mt-3">
              <input type="text" id="inputChoice4" name="choice[]" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputChoice4">Choice 4</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-outline mt-3">
              <input type="text" id="inputAnswer" name="answer" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputAnswer">Correct Answer</label>
            </div>
          </div>
          <div class="col">
            <div class="form-outline mt-3">
              <input type="number" id="inputPoints" name="points" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputPoints">Point/s</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="add-question" class="btn btn-floating btn-primary"><i class="fas fa-plus"></i></button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- Edit Question Modal -->
<form action="examination.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass3; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel">Edit Item</h5>
        <input type="number" name="question-id" value="<?php foreach ($queries5 as $query5): echo $query5['question_id']; endforeach; ?>" hidden>
        <input type="number" name="exam-id" value="<?php foreach ($queries5 as $query5): echo $query5['exam_id']; endforeach; ?>" hidden>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <div class="form-outline">
          <textarea class="form-control" name="question" id="textAreaQuestion" rows="3"><?php foreach ($queries5 as $query5): echo $query5['question']; endforeach; ?></textarea>
          <label class="form-label" for="textAreaQuestion">Question</label>
        </div>
        <div class="row">
        <?php
          $choiceNum = 1;
          $counter = 1;
          while($row5=mysqli_fetch_array($selectChoices)){
        ?>
          
            <input type="number" name="choice-id[]" value="<?= $row5['choice_id'];?>" hidden>
          
          <div class="col-md-6">
            <div class="form-outline mt-3">
              <input type="text" id="inputChoice2" name="choice[]" value="<?= $row5['choice'];?>" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputChoice2">Choice <?php echo $choiceNum; ?></label>
            </div>
          </div>
        <?php $choiceNum++; }  ?>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-outline mt-3">
              <input type="text" id="inputAnswer" name="answer" value="<?php foreach ($queries5 as $query5): echo $query5['answer']; endforeach; ?>" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputAnswer">Correct Answer</label>
            </div>
          </div>
          <div class="col">
            <div class="form-outline mt-3">
              <input type="number" id="inputPoints" name="points" value="<?php foreach ($queries5 as $query5): echo $query5['points']; endforeach; ?>" class="form-control form-control-lg required" required>
              <label class="form-label" for="inputPoints">Point/s</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button type="submit" name="edit-question" class="btn btn-floating btn-primary"><i class="fas fa-check"></i></button>
      </div>
    </div>
  </div>
</div>
</form>

<!-- Full View Modal -->
<form action="examination.php" method="post">
<div class="modal fade staticBackdrop <?php echo $modalClass4; ?>" id="staticBackdrop" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="staticBackdropLabel"></h5>
        <button
          type="button"
          class="btn-close"
          data-mdb-dismiss="modal"
          aria-label="Close">
        </button>
      </div>
      <div class="modal-body px-4 py-4">
        <p class="note note-light">
        <strong style="font-family: 'Roboto Thin';">Question:</strong> <strong><?php foreach ($queries7 as $query7): echo $query7['question']; endforeach; ?></strong>
        </p>
        <div class="row">
          <div class="col me-3">
          <ul>
        <?php
          $counter = 1;
          while($row6=mysqli_fetch_array($selectChoices2)){
        ?>          
          <li><?= $row6['choice'];?></li>
        <?php }  ?>
        </ul>
        </div>
        </div>
        <hr>
        <div class="row">
          <div class="col">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <div class="row">
                  <div class="col" style="font-family: 'Roboto Thin';"><strong>Correct Answer</strong></div>
                  <div class="col">
                    <strong>
                      <?php
                        foreach ($queries7 as $query7):
                          echo $query7['answer'];
                        endforeach;
                      ?>
                    </strong>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="col">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">
                <div class="row">
                  <div class="col" style="font-family: 'Roboto Thin';"><strong>Point/s</strong></div>
                  <div class="col">
                    <strong>
                      <?php
                        foreach ($queries7 as $query7):
                          echo $query7['points'];
                        endforeach;
                      ?>
                    </strong>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-floating btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i>
        </button>
      </div>
    </div>
  </div>
</div>
</form>

</body>
  <?php require_once 'constants/scripts.php'?>
</html>