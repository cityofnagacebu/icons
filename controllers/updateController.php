<?php

if (isset($_POST['edit-batch'])) {
	$batch_num = $_POST['batch-num'];
	$num_slots = $_POST['num-slots'];

	// validation
	if ($num_slots > 300) {
		$errors['slots'] = "Number of slots must not be more than 300!";
	}

	if (count($errors) === 0) {

		$update_query = "UPDATE tbl_batch SET num_slots = '$num_slots' WHERE batch_num = '$batch_num'";
      	mysqli_query($conn, $update_query);

	  	header('location: batch.php');
		exit();
	}
}

if (isset($_POST['edit-exam'])) {
	$exam_id = $_POST['exam-id'];
	$exam = $_POST['exam'];
	$type = $_POST['type'];

	// validation
	$examQuery = "SELECT * FROM tbl_exam WHERE exam_name=? AND exam_id<>? LIMIT 1";
	$stmt = $conn->prepare($examQuery);
	$stmt->bind_param('sd', $exam, $exam_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$examCount = $result->num_rows;
	$stmt->close();

	if ($examCount === 1) {
		$errors['exam'] = "Failed to update exam! Exam description already exist!";
	}

	if (count($errors) === 0) {

		$update_query = "UPDATE tbl_exam SET exam_name = '$exam', type = '$type' WHERE exam_id = '$exam_id'";
      	mysqli_query($conn, $update_query);

	  	header('location: examination.php');
		exit();
	}
}

if (isset($_POST['edit-question'])) {
	$exam_id = $_POST['exam-id'];
	$question_id = $_POST['question-id'];
	$question = $_POST['question'];
	$answer = $_POST['answer'];
	$points = $_POST['points'];
	$choice_id = $_POST['choice-id'];
	$choice = $_POST['choice'];

	// validation
	$questionQuery = "SELECT * FROM tbl_questions WHERE question=? AND question_id<>? LIMIT 1";
	$stmt = $conn->prepare($questionQuery);
	$stmt->bind_param('sd', $question, $question_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$questionCount = $result->num_rows;
	$stmt->close();

	if ($questionCount === 1) {
		$errors['question'] = "Failed to update question! Question already exist!";
	}

	if (count($errors) === 0) {

		$update_query = "UPDATE tbl_questions SET question = '$question', answer = '$answer', points = '$points' WHERE question_id = '$question_id'";
      	mysqli_query($conn, $update_query);

      	$N = count($choice_id);
		for($i=0; $i < $N; $i++){
			$update_query2 = "UPDATE tbl_choices SET choice = '$choice[$i]' WHERE choice_id = '$choice_id[$i]'";
      		mysqli_query($conn, $update_query2);
		}

		$questionQuery2 = $conn->query("SELECT * from tbl_questions WHERE exam_id='$exam_id'");

		$qpoints = 0;

		$counter = 1;
      	while($row=mysqli_fetch_array($questionQuery2)){
        	$qpoints += $row['points'];
      	}

      	$passing_score = $qpoints * 0.75;

      	$update_query3 = "UPDATE tbl_exam SET passing_score = '$passing_score' WHERE exam_id = '$exam_id'";
      	mysqli_query($conn, $update_query3);

	  	header('location: examination.php');
		exit();
	}
}

if (isset($_POST['update-schedule'])) {
	$schedule_id = $_POST['schedule-id'];
	$date_start = $_POST['date-start'];
	$date_end = $_POST['date-end'];

	// validation

	if (count($errors) === 0) {

		$update_query = "UPDATE tbl_schedules SET date_start = '$date_start', date_end = '$date_end'  WHERE schedule_id = '$schedule_id'";
      	mysqli_query($conn, $update_query);

	  	header('location: schedule.php');
		exit();
	}
}

if (isset($_POST['approve'])) {
	if(isset($_POST['id'])){
    foreach ($_POST['id'] as $id) {
      $selectStudent = $conn->query("SELECT * from tbl_scholarship_info WHERE student_id='$id' LIMIT 1");
        $queryStudents=mysqli_fetch_array($selectStudent);
        $student_id = $queryStudents['student_id'];
      $status = "";
      if ($queryStudents['year'] == '1st' && $queryStudents['semester'] == '1st') {
      	$status = "New";
      } else {
      	$status = "Old";
      }
		$update_query = "UPDATE tbl_scholarship_info SET scholarship_status = '$status' WHERE student_id = '$student_id'";
      	mysqli_query($conn, $update_query);
      	header('location: scholars.php');
		exit();      
    }
  	}
}