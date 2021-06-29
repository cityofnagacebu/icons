<?php
    require_once 'controllers/authController.php';
    require_once 'controllers/createController.php';

    if (isset($_GET['exam'])) {
      $id = $_GET['exam'];
      if (isset($_GET['token'])) {
      $token = $_GET['token'];

      $applicantQuery = "SELECT * FROM tbl_tokens WHERE token=? LIMIT 1";
      $stmt = $conn->prepare($applicantQuery);
      $stmt->bind_param('s', $token);
      $stmt->execute();
      $result = $stmt->get_result();
      $applicantResult = $result->fetch_assoc();

      $applicant_id = $applicantResult['student_id'];

      $selectScores = $conn->query("SELECT * from tbl_exam_results WHERE exam_id='$id' AND student_id='$applicant_id'");
      }

      $selectExam = mysqli_query($conn, "SELECT * FROM tbl_exam WHERE exam_id=$id LIMIT 1");
      $queries1 = mysqli_fetch_all($selectExam, MYSQLI_ASSOC);

      $selectQuestions = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id'");
      $selectQuestions2 = $conn->query("SELECT * from tbl_questions WHERE exam_id='$id' ORDER BY RAND()");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Online Exam | ICoNS</title>
  <?php require_once 'constants/links.php';?>
  <style>
    body{
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }

    #submitExam {
      position: absolute;
      right: 5%;
      top: 80%;
      display: none;
      z-index: 1;
    }
  </style>
</head>
<body>
  <?php if (isset($_GET['token']) && mysqli_num_rows($selectScores) == 0) { ?>
  <form action="online_exam.php" method="post">
    <input type="text" name="applicant-token" id="applicantToken" value="<?php echo $_GET['token'];?>" hidden>
    <input type="text" name="exam-id" id="examID" value="<?php echo $_GET['exam'];?>" hidden>
  <div class="d-flex align-items-center justify-content-around bg-light">
    <div class="col-md-7 mx-3">
      <div class="row">
        <div class="col-md-7">
          <a class="navbar-brand"><img src="images/icon_brand_logo.png" style="width: 100%;" alt=""></a>
        </div>
        <div class="col-md-5"></div>
      </div>  
    </div>
    <div class="col-md-3 mx-3">
      <div class="row">
        <div class="col-md-5"><input type="number" id="totalMinutes" value="<?php foreach ($queries1 as $query1): echo floor($query1['time_limit']/60000);endforeach;?>" hidden><input type="number" id="totalSeconds" value="<?php foreach ($queries1 as $query1): echo floor(($query1['time_limit']%60000)/1000);endforeach;?>" hidden></div>
        <div class="col-md-7">
          <div class="card">
  <div class="card-body text-center">
    <span class="text-center" style="font-family: 'Roboto Thin'; font-size: 22px;"><strong id="countdownTimer"><?php foreach ($queries1 as $query1): echo floor($query1['time_limit']/60000);endforeach;?>:<?php foreach ($queries1 as $query1): if(floor(($query1['time_limit']%60000)/1000) == 0){echo "00";} else {echo floor(($query1['time_limit']%60000)/1000);};endforeach;?></strong></span>
  </div>
</div>
        </div>
      </div>    
    </div>
  </div>
  <div id="carouselBasicExample2" class="carousel carousel-dark slide">
    <!-- Indicators -->
    <div class="carousel-indicators col-md-10 mx-auto">
      <?php
        $num = 0;
        while($row2=mysqli_fetch_array($selectQuestions)){
      ?>
        <button id="btnSlide<?php echo $num+1;?>" 
        type="button"
        class="indicator" 
        data-mdb-target="#carouselBasicExample2"
        data-mdb-slide-to="<?php echo $num;?>"
        aria-current="true"
        aria-label="Slide <?php echo $num+1;?>"
      ></button>  
      <?php $num++;} ?>
      <!-- <button id="btnSlide1" 
      type="button"
      class="indicator" 
      data-mdb-target="#carouselBasicExample2"
      data-mdb-slide-to="0"
      aria-current="true"
      aria-label="Slide 1"
      ></button> -->
    </div>

    <!-- Inner -->
    <div class="carousel-inner">
      <?php
        $num1 = 1;
        while($row1=mysqli_fetch_array($selectQuestions2)){
      ?>
        <div class="carousel-item" data-mdb-interval="false">
        <div style="height: 500px; width: 100%;">
          <div class="col-md-8 mx-auto mt-5">
            <div class="mx-5">
              <p class="note note-primary lead">
                <?= $row1['question'];?> (<?php if($row1['points']==1){echo $row1['points']." pt.";} else { echo $row1['points']." pts.";}?>)
              </p>
              <?php
                $question_id = $row1['question_id'];
                $selectChoices = $conn->query("SELECT * from tbl_choices WHERE question_id='$question_id'");

                $num2 = 1;
                while($row3=mysqli_fetch_array($selectChoices)){
              ?>
                <div class="form-check mt-3 ms-5">
                <input
                  class="form-check-input"
                  type="radio"
                  name="flexRadioDefault<?php echo $num1;?>"
                  id="flexRadioDefault<?php echo $num2;?>"
                  value="<?= $row3['choice'];?>"
                  />
                  <label class="form-check-label" for="flexRadioDefault<?php echo $num2;?>"> <?= $row3['choice'];?></label>
                </div>
              <?php $num2++;} ?> 
              <div><input type="text" name="answers[]" value="<?= $row1['answer'];?>" hidden><input type="text" name="points[]" value="<?= $row1['points'];?>" hidden></div>
            </div>
          </div>
        </div>
      </div>
      <?php $num1++;} ?>
  </div>
  <!-- Inner -->

  <!-- Controls -->
  <button
    class="carousel-control-prev"
    type="button"
    data-mdb-target="#carouselBasicExample2"
    data-mdb-slide="prev"
  >
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button
    class="carousel-control-next"
    type="button"
    data-mdb-target="#carouselBasicExample2"
    data-mdb-slide="next"
  >
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>  </div>
  <button type="button" data-mdb-toggle="modal" data-mdb-target="#submitExamDialog" class="btn btn-primary btn-lg" id="submitExam"><i class="fas fa-file-import"></i> Submit</button>
  <div><input type="number" id="totalNum" name="num-items" value="<?php foreach ($queries1 as $query1): echo $query1['num_items'];endforeach;?>" hidden></div>
  <div class="progress" style="height: 25px">
  <div id="progressBar" 
    class="progress-bar progress-bar-striped progress-bar-animated"
    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="100"
  >
    <ul class="list-inline pt-3">
  <li class="list-inline-item zero">0</li>
  <span class="numChecked"></span>
  <li class="list-inline-item">/</li>
  <li class="list-inline-item"><?php foreach ($queries1 as $query1): echo $query1['num_items'];endforeach;?></li>
</ul>
  </div>
</div>
<!-- Start Exam Modal -->
<div class="modal fade staticBackdrop startModal" id="startExamDialog" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white mx-auto" id="staticBackdropLabel"><?php foreach ($queries1 as $query1): echo $query1['exam_name'];endforeach;?>
        </h5>
      </div>
      <div class="modal-body px-4 py-4 text-center">
        <p>This is a multiple choice type of exam that contains <strong><?php foreach ($queries1 as $query1): echo $query1['num_items'];endforeach;?> items</strong> and has a duration of <strong>  <?php foreach ($queries1 as $query1): echo floor($query1['time_limit']/60000);endforeach;?>min</strong> and <strong><?php foreach ($queries1 as $query1): echo floor(($query1['time_limit']%60000)/1000);endforeach;?>sec</strong>.</p>
        <p>Please choose the best answer. Good luck!</p>
        <p class="text-danger"><small>Warning: Once you already click start, do not try to refresh this page just to reset the duration. You can no longer access the exam if you do.</small></p>
        <p><small><em>-Time is gold. Don't waste time.-</em></small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-lg mx-auto" data-mdb-dismiss="modal">
          <i class="fas fa-play"></i> Start
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End of Start Exam Modal -->
<!-- Submit Exam Modal -->
<div class="modal fade staticBackdrop" id="submitExamDialog" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white mx-auto" id="staticBackdropLabel">Submit Exam
        </h5>
      </div>
      <div class="modal-body px-4 py-4 text-center">
        <p>Are you sure you want to submit this exam?</p>
        <p>You still have remaining time to review your answers.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-light" data-mdb-dismiss="modal">
          <i class="fas fa-arrow-left"></i> Review
        </button>
        <button type="submit" name="submit-exam" class="btn btn-lg btn-primary"><i class="fas fa-check"></i> Done</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Submit Exam Modal -->
<!-- End Exam Modal -->
<div class="modal fade staticBackdrop endModal" id="startExamDialog" data-mdb-backdrop="static" data-mdb-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title text-white mx-auto" id="staticBackdropLabel">Oops! Sorry.
        </h5>
      </div>
      <div class="modal-body px-4 py-4 text-center">
        <p>Time is up.</p>
        <p>You can no longer access the exam.</p>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit-exam" class="btn btn-danger btn-lg mx-auto">
          <i class="fas fa-file-import"></i> Submit
        </button>
      </div>
    </div>
  </div>
</div>
<!-- End of End Exam Modal -->
</form>
<?php } else if(isset($_GET['token']) && mysqli_num_rows($selectScores) > 0) { ?>
    <div class="d-flex align-items-center justify-content-around bg-light">
    <div class="col-md-7 mx-3">
  <div class="row">
    <div class="col-md-7">
      <a class="navbar-brand"><img src="images/icon_brand_logo.png" style="width: 100%;" alt=""></a>
    </div>
    <div class="col-md-5"></div>
  </div>
      
</div>
<div class="col-md-3 mx-3">
  <div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-7">
      <a href="index.php" style="text-decoration: none;">
      <button type="button" class="btn btn-lg btn-outline-primary btn-block inquire-now"><i class="fas fa-home"></i> Go to Home</button></a>
    </div>
  </div>    
</div>
  </div>
  <div style="height: 500px;">
  <div class="container d-flex align-items-center justify-content-center text-center h-100 mt-6">
            <div class="text-dark">
              <h1 class="display-3 mb-3 text-danger">Sorry! You already taken the exam.</h1>
            </div>
          </div>
          </div>
<?php } else if(isset($_GET['exam-submitted'])) { 
  $exam_id = $_GET['exam'];
  $applicant_id = $_GET['applicant'];

  $questionQuery = $conn->query("SELECT * from tbl_questions WHERE exam_id='$exam_id'");

  $qpoints = 0;
  while($row=mysqli_fetch_array($questionQuery)){
    $qpoints += $row['points'];
  }

  $applicantQuery = "SELECT * FROM tbl_students WHERE student_id=? LIMIT 1";
  $stmt2 = $conn->prepare($applicantQuery);
  $stmt2->bind_param('s', $applicant_id);
  $stmt2->execute();
  $result2 = $stmt2->get_result();
  $applicantResult = $result2->fetch_assoc();

  $applicant_name = $applicantResult['first_name'].' '.$applicantResult['last_name'];

  $scoreQuery = "SELECT * FROM tbl_exam_results WHERE student_id=? AND exam_id=? LIMIT 1";
  $stmt3 = $conn->prepare($scoreQuery);
  $stmt3->bind_param('sd', $applicant_id, $exam_id);
  $stmt3->execute();
  $result3 = $stmt3->get_result();
  $scoreResult = $result3->fetch_assoc();

  $score = $scoreResult['score'];
  ?>
  <div class="d-flex align-items-center justify-content-around bg-light">
    <div class="col-md-7 mx-3">
  <div class="row">
    <div class="col-md-7">
      <a class="navbar-brand"><img src="images/icon_brand_logo.png" style="width: 100%;" alt=""></a>
    </div>
    <div class="col-md-5"></div>
  </div>
      
</div>
<div class="col-md-3 mx-3">
  <div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-7">
      <a href="index.php" style="text-decoration: none;">
      <button type="button" class="btn btn-lg btn-outline-primary btn-block inquire-now"><i class="fas fa-home"></i> Go to Home</button></a>
    </div>
  </div>    
</div>
  </div>
  <div style="height: 500px;">
  <div class="container d-flex align-items-center justify-content-center text-center h-100 mt-6">
            <div class="text-dark">
              <h1 class="mb-3">Your score is</h1>
              <h1 class="display-1 mb-3"><?php echo $score.'/'.$qpoints;?></h1>
              <?php if ($scoreResult['remark'] == "Passed") {?>
              <h5 class="mb-4 text-success">Congratulations <?php echo $applicant_name;?> for passing the exam.</h5>
            <?php } else {?>
              <h5 class="mb-4 text-danger">Sorry! You failed the exam.☹ Better luck next time.</h5>
            <?php } ?>
            </div>
          </div>
          </div>
<?php } else { 
    header('location: index.php');
    exit();
 } ?>
</body>
  <?php require_once 'constants/scripts.php';?>
  <script>
    document.addEventListener('contextmenu', event => event.preventDefault());
  </script>
</html>