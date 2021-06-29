<?php

$errors = array();
$track = "";
$fname = "";
$mname = "";
$lname = "";
$extname = "";
$email = "";
$purok = "";
$barangay = "";
$phonenum = "";
$pname = "";


$request = array();
$token1	= "";

if (isset($_POST['add-batch'])) {
	$batch_num = $_POST['batch'];
	$num_slots = $_POST['num-slots'];

	$year = date('Y');
	// validation
	if ($num_slots > 300) {
		$errors['slots'] = "Number of slots must not be more than 300!";
	}

	$batchQuery = "SELECT * FROM tbl_batch WHERE year=? LIMIT 1";
	$stmt = $conn->prepare($batchQuery);
	$stmt->bind_param('d', $year);
	$stmt->execute();
	$result = $stmt->get_result();
	$batchCount = $result->num_rows;
	$stmt->close();

	if ($batchCount === 1) {
		$errors['batch'] = "You already created a batch for this year.";
	}

	if (count($errors) === 0) {

		$insert1 = "INSERT into tbl_batch (batch_num, num_slots, year) VALUES ('$batch_num', '$num_slots', '$year')";
		mysqli_query($conn, $insert1);

	  	header('location: batch.php');
		exit();
	}
}

// if applicant clicks on the submit button
if (isset($_POST['apply'])) {
	$track = $_POST['track'];
	$fname = $_POST['fname'];
	$mname = $_POST['mname'];
	$lname = $_POST['lname'];
	$extname = $_POST['extname'];
	$email = $_POST['email'];
	$purok = $_POST['purok'];
	$barangay = $_POST['barangay'];
	$phonenum = $_POST['phonenum'];
	$pname = $_POST['pname'];
	$status = "New";
	$note_subject = "New Applicant";
	$note_text = $fname . " " . $lname;
	$category = "applicants";

	$file_category = "Application Requirement";

	$year = date('Y');

	// validation
	$applicantQuery = "SELECT * FROM tbl_students WHERE first_name=? AND last_name=? LIMIT 1";
	$stmt = $conn->prepare($applicantQuery);
	$stmt->bind_param('ss', $fname, $lname);
	$stmt->execute();
	$result = $stmt->get_result();
	$applicantCount = $result->num_rows;
	$stmt->close();

	if ($applicantCount === 1) {
		$errors['name'] = "Applicant already exist.";
	}

	for($i=0;$i<count($_FILES["files"]["name"]);$i++) {
		if($_FILES['files']['size'][$i] > 200000) {
      		$errors['file'] = "File size should not be greated than 200Kb";
    	}
	}

	$num_str = sprintf("%05d", mt_rand(1, 99999));

	$student_id = "S".$num_str;

	if (count($errors) === 0) {
		$token = bin2hex(random_bytes(50));

		$insert1 = "INSERT into tbl_students (student_id, first_name, middle_name, last_name, ext_name) VALUES ('$student_id', '$fname', '$mname', '$lname', '$extname')";
		mysqli_query($conn, $insert1);

        $applicantQuery = "SELECT * FROM tbl_students WHERE first_name=? AND last_name=? LIMIT 1";
		$stmt = $conn->prepare($applicantQuery);
		$stmt->bind_param('ss', $fname, $lname);
		$stmt->execute();
		$result = $stmt->get_result();
		$applicantCount = $result->num_rows;
		$applicant = $result->fetch_assoc();

		$applicant_id = $applicant['student_id'];

		$batchQuery = "SELECT * FROM tbl_batch WHERE year=? LIMIT 1";
		$stmt = $conn->prepare($batchQuery);
		$stmt->bind_param('d', $year);
		$stmt->execute();
		$result = $stmt->get_result();
		$batchCount = $result->num_rows;
		$batch = $result->fetch_assoc();

		$batch_num = $batch['batch_num'];

		for($i=0;$i<count($_FILES["files"]["name"]);$i++) {
			// for the database
    		$fileName = time() . '-' . $_FILES["files"]["name"][$i];
    		// For image upload
	    	$target_dir="attachments/";
	    	$target_file = $target_dir . basename($fileName);
	    	// VALIDATION
    		// validate image size. Size is calculated in Bytes
	    	move_uploaded_file($_FILES["files"]["tmp_name"][$i], $target_file);
	    	$insert2 = "INSERT into tbl_files (student_id, file_name, category, uploaded_on) VALUES ('$applicant_id', '$fileName', '$file_category', NOW())";
	    	mysqli_query($conn, $insert2);    	
	  	}

	  	$insert3 = "INSERT into tbl_address (student_id, purok, barangay) VALUES ('$applicant_id', '$purok', '$barangay')";
	    	mysqli_query($conn, $insert3);	

	    	$insert4 = "INSERT into tbl_contact_details (student_id, email, phone_num) VALUES ('$applicant_id', '$email', '$phonenum')";
	    	mysqli_query($conn, $insert4);

	    	$insert5 = "INSERT into tbl_application_info (student_id, track, application_status, date_applied) VALUES ('$applicant_id', '$track', '$status', NOW())";
	    	mysqli_query($conn, $insert5);	    	

	    	$insert6 = "INSERT into tbl_tokens (student_id, token) VALUES ('$applicant_id', '$token')";
	    	mysqli_query($conn, $insert6);

	    	$insert7 = "INSERT into tbl_parents (student_id, parent_name) VALUES ('$applicant_id', '$pname')";
	    	mysqli_query($conn, $insert7);

	    	$insert8 = "INSERT into tbl_student_batch (student_id, batch_num) VALUES ('$applicant_id', '$batch_num')";
	    	mysqli_query($conn, $insert8);	 

 		$noteQuery = "INSERT INTO tbl_notification(note_subject, note_text, category) VALUES ('$note_subject', '$note_text', '$category')";
 		mysqli_query($conn, $noteQuery);

	  	header('location: application_form.php?registration=success');
		exit();
	}else {
			$errors['db_error'] = "Failed to register!";
	}
}

$request = array();
$token1	= "";

if (isset($_POST['send-email'])) {
	$student_id = $_POST['studentid'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$token1 = $_POST['token'];
	$message = $_POST['message'];
	$request = $_POST['request'];

	$status = "Emailed";

	sendRequestEmail($email, $fname, $lname, $message, $token1, $request);

	$update_query = "UPDATE tbl_application_info SET application_status = '$status' WHERE student_id = '$student_id'";
    mysqli_query($conn, $update_query);

	header('location: applicants.php');
	exit();
}

$token2	= "";

if (isset($_POST['upload-required'])) {
	$token2 = $_POST['token']; 
	$note_subject = "New File Upload";
	$category = "applicants";

	$file_category = "Application Requirement";

	for($i=0;$i<count($_FILES["files"]["name"]);$i++) {
		if($_FILES['files']['size'][$i] > 200000) {
      		$errors['file'] = "File size should not be greated than 200Kb";
    	}
	}

	if (count($errors) === 0) {

		$applicantQuery = "SELECT * FROM tbl_tokens WHERE token=? LIMIT 1";
		$stmt = $conn->prepare($applicantQuery);
		$stmt->bind_param('s', $token2);
		$stmt->execute();
		$result = $stmt->get_result();
		$applicantCount = $result->num_rows;
		$applicant = $result->fetch_assoc();

		$applicant_id = $applicant['student_id'];

		$applicantQuery2 = "SELECT * FROM tbl_students WHERE student_id=? LIMIT 1";
		$stmt2 = $conn->prepare($applicantQuery2);
		$stmt2->bind_param('s', $applicant_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$applicantCount2 = $result2->num_rows;
		$applicant2 = $result2->fetch_assoc();		

		$note_text = $applicant2['first_name'] . " " . $applicant2['last_name'] . " uploaded some documents.";

		for($i=0;$i<count($_FILES["files"]["name"]);$i++) {
			// for the database
    		$fileName = time() . '-' . $_FILES["files"]["name"][$i];
    		// For image upload
	    	$target_dir="attachments/";
	    	$target_file = $target_dir . basename($fileName);
	    	// VALIDATION
    		// validate image size. Size is calculated in Bytes
	    		move_uploaded_file($_FILES["files"]["tmp_name"][$i], $target_file);
	    		$insert2 = "INSERT into tbl_files (student_id, file_name, category, uploaded_on) VALUES ('$applicant_id', '$fileName', '$file_category', NOW())";
	    		mysqli_query($conn, $insert2);
	  	} 

 		$noteQuery = "INSERT INTO tbl_notification(note_subject, note_text, category) VALUES ('$note_subject', '$note_text', '$category')";
 		mysqli_query($conn, $noteQuery);

	  	header('location: index.php');
		exit();
	}else {
			$errors['db_error'] = "Failed to upload attachment/s!";
	}
}

if (isset($_POST['add-exam'])) {
	$exam = $_POST['exam'];
	$type = $_POST['type'];

	// validation
	$examQuery = "SELECT * FROM tbl_exam WHERE exam_name=? LIMIT 1";
	$stmt = $conn->prepare($examQuery);
	$stmt->bind_param('s', $exam);
	$stmt->execute();
	$result = $stmt->get_result();
	$examCount = $result->num_rows;
	$stmt->close();

	if ($examCount === 1) {
		$errors['exam'] = "Failed to add exam! Exam already exist!";
	}

	if (count($errors) === 0) {

		$insert1 = "INSERT into tbl_exam (exam_name, type) VALUES ('$exam', '$type')";
		mysqli_query($conn, $insert1);

	  	header('location: examination.php');
		exit();
	}
}

$choices = array();

if (isset($_POST['add-question'])) {
	$exam_id = $_POST['exam-id'];
	$question = $_POST['question'];
	$answer = $_POST['answer'];
	$points = $_POST['points'];


	// validation
	$questionQuery = "SELECT * FROM tbl_questions WHERE question=? LIMIT 1";
	$stmt = $conn->prepare($questionQuery);
	$stmt->bind_param('s', $question);
	$stmt->execute();
	$result = $stmt->get_result();
	$questionCount = $result->num_rows;
	$stmt->close();

	if ($questionCount === 1) {
		$errors['question'] = "Failed to add question! Question already exist!";
	}

	if (count($errors) === 0) {
		$insert1 = "INSERT into tbl_questions (exam_id, question, answer, points) VALUES ('$exam_id', '$question', '$answer', '$points')";
		mysqli_query($conn, $insert1);

		$questionQuery = "SELECT * FROM tbl_questions WHERE question=? LIMIT 1";
		$stmt = $conn->prepare($questionQuery);
		$stmt->bind_param('s', $question);
		$stmt->execute();
		$result = $stmt->get_result();
		$questionResult = $result->fetch_assoc();

		$question_id = $questionResult['question_id'];		

		foreach ($_POST['choice'] as $choice) {
      		$insert2 = "INSERT into tbl_choices (question_id, choice) VALUES ('$question_id', '$choice')";
			mysqli_query($conn, $insert2);
    	}

    	$questionQuery2 = $conn->query("SELECT * from tbl_questions WHERE exam_id='$exam_id'");

    	$items = mysqli_num_rows($questionQuery2);
    	$qpoints = 0;

      	$counter = 1;
      	while($row=mysqli_fetch_array($questionQuery2)){
        	$qpoints += $row['points'];
      	}

      	$passing_score = $qpoints * 0.75;
      	$time_limit = ($items * 0.75) * 60000;

      	$update_query = "UPDATE tbl_exam SET num_items = '$items', passing_score = '$passing_score', time_limit = '$time_limit' WHERE exam_id = '$exam_id'";
      	mysqli_query($conn, $update_query);

	  	$location = "location: examination.php?exam=".$exam_id."";

 		header($location);
  		exit();
	}
}

$token3	= "";

if (isset($_POST['send-exam'])) {
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$token3 = $_POST['token'];
	$exam_id = $_POST['exam-id'];

	$examQuery = "SELECT * FROM tbl_exam WHERE exam_id=? LIMIT 1";
	$stmt = $conn->prepare($examQuery);
	$stmt->bind_param('d', $exam_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$examResult = $result->fetch_assoc();

	$exam_name = $examResult['exam_name'];	

	$status = "Exam Sent";

	sendExamEmail($email, $fname, $lname, $token3, $exam_id, $exam_name);

	$update_query = "UPDATE tbl_applicants SET status = '$status' WHERE a_email = '$email'";
    mysqli_query($conn, $update_query);

	header('location: applicants.php');
	exit();
}

$token4	= "";

if (isset($_POST['submit-exam'])) {
	$token4 = $_POST['applicant-token'];
	$exam_id = $_POST['exam-id'];
	$num_items = $_POST['num-items'];
	$answers = $_POST['answers'];
	$points = $_POST['points'];

	$score = 0;

	$note_subject = "Finished the Exam";

	$examQuery = "SELECT * FROM tbl_exam WHERE exam_id=? LIMIT 1";
	$stmt1 = $conn->prepare($examQuery);
	$stmt1->bind_param('d', $exam_id);
	$stmt1->execute();
	$result1 = $stmt1->get_result();
	$examResult = $result1->fetch_assoc();

	$passing_score = $examResult['passing_score'];
	$status = $examResult['exam_name']." Taken";

	$applicantQuery = "SELECT * FROM tbl_tokens WHERE token=? LIMIT 1";
	$stmt2 = $conn->prepare($applicantQuery);
	$stmt2->bind_param('s', $token4);
	$stmt2->execute();
	$result2 = $stmt2->get_result();
	$applicantResult = $result2->fetch_assoc();

	$applicant_id = $applicantResult['student_id'];

	$applicantQuery2 = "SELECT * FROM tbl_students WHERE student_id=? LIMIT 1";
	$stmt3 = $conn->prepare($applicantQuery2);
	$stmt3->bind_param('s', $applicant_id);
	$stmt3->execute();
	$result3 = $stmt3->get_result();
	$applicantResult2 = $result3->fetch_assoc();		

	for ($x = 1; $x <= $num_items; $x++) {
		$applicantAnswer = 'flexRadioDefault'.$x.''; 
  		if ($_POST[$applicantAnswer] == $answers[$x-1]){
  			$score = $score + $points[$x-1];
  		}
	}

	$remark = "";
	$category = "exam_results";
	$note_remark = "";

	if ($score >= $passing_score){
  		$remark = "Passed";
  		$note_remark = "passed";
  	} else {
  		$remark = "Failed";
  		$note_remark = "failed";
  	}

  	$note_text = $applicantResult2['first_name'] . " " . $applicantResult2['last_name'] . " ".$note_remark." the ". $examResult['exam_name'] .".";

	$update_query1 = "UPDATE tbl_exam_results SET score = '$score', remark = '$remark' WHERE exam_id = '$exam_id' AND student_id = '$applicant_id'";
		mysqli_query($conn, $update_query1);

	$update_query2 = "UPDATE tbl_application_info SET application_status = '$status' WHERE student_id = '$applicant_id'";
    mysqli_query($conn, $update_query2);

    $noteQuery = "INSERT INTO tbl_notification(note_subject, note_text, category) VALUES ('$note_subject', '$note_text', '$category')";
 	mysqli_query($conn, $noteQuery);

    $location = "location: online_exam.php?exam=".$exam_id."&applicant=".$applicant_id."&exam-submitted=done";

	header($location);
	exit();
}

// if admin clicks on the add scholar button
if (isset($_POST['add-qualified'])) {
	$id = $_POST['applicant-id'];

	$status = "Qualified";

	// validation
	$applicantQuery = "SELECT * FROM tbl_application_info WHERE student_id=? AND application_status=? LIMIT 1";
	$stmt = $conn->prepare($applicantQuery);
	$stmt->bind_param('ss', $id, $status);
	$stmt->execute();
	$result = $stmt->get_result();
	$applicantCount = $result->num_rows;

	if ($applicantCount === 1) {
		$errors['applicant'] = "Applicant was already added to qualified.";
	}

	$applicantQuery3 = "SELECT * FROM tbl_students WHERE student_id=?";
	$stmt3 = $conn->prepare($applicantQuery3);
	$stmt3->bind_param('s', $id);
	$stmt3->execute();
	$result3 = $stmt3->get_result();
	$applicantResult = $result3->fetch_assoc();

	$student_id = $applicantResult['student_id'];
	$fname = $applicantResult['first_name'];
	$lname = $applicantResult['last_name'];

	$applicantQuery2 = "SELECT * FROM tbl_contact_details WHERE student_id=?";
	$stmt2 = $conn->prepare($applicantQuery2);
	$stmt2->bind_param('s', $student_id);
	$stmt2->execute();
	$result2 = $stmt2->get_result();
	$applicant = $result2->fetch_assoc();

	$email = $applicant['email'];

	$applicantQuery4 = "SELECT * FROM tbl_tokens WHERE student_id=?";
	$stmt4 = $conn->prepare($applicantQuery4);
	$stmt4->bind_param('s', $student_id);
	$stmt4->execute();
	$result4 = $stmt4->get_result();
	$applicant3 = $result4->fetch_assoc();

	$token = $applicant3['token'];

	if (count($errors) === 0) {
		sendConfirmationEmail($email, $fname, $lname, $token, $id);
		$update_query = "UPDATE tbl_application_info SET application_status = '$status' WHERE student_id = '$id'";
      	mysqli_query($conn, $update_query);

  		header('location: exam_results.php?enrollment=success');
		exit();
	}
}

// if scholar clicks on the submit enrollment button
if (isset($_POST['submit-enrollment'])) {
	$student_id = $_POST['student-id'];
	$school = $_POST['school'];
	$course = $_POST['course'];
	$year = $_POST['year'];
	$semester = $_POST['semester'];

	// validation
	for($i=0;$i<count($_FILES["files"]["name"]);$i++) {
		if($_FILES['files']['size'][$i] > 200000) {
      		$errors['file'] = "File size should not be greated than 200Kb";
    	}
	}

	if (count($errors) === 0) {

		$applicantQuery = "SELECT * FROM tbl_students WHERE student_id=? LIMIT 1";
		$stmt3 = $conn->prepare($applicantQuery);
		$stmt3->bind_param('s', $student_id);
		$stmt3->execute();
		$result3 = $stmt3->get_result();
		$applicantResult = $result3->fetch_assoc();

		$scholar_id = $applicantResult['student_id'];

		$note_subject = "New Scholar";
		$note_text = $applicantResult['first_name']." ".substr($applicantResult['middle_name'], 0, 1).". ".$applicantResult['last_name']." ".$applicantResult['ext_name'];
		$category = "scholars";

		$file_category = "Enrollment Requirement";

		$status = "Pending for Approval";

		$activity = "Enrollment Period";
		$applicable_for = $scholar_id;
		$schedule_status = "Closed";

		for($i=0;$i<count($_FILES["files"]["name"]);$i++) {
			// for the database
    		$fileName = time() . '-' . $_FILES["files"]["name"][$i];
    		// For image upload
	    	$target_dir="attachments/";
	    	$target_file = $target_dir . basename($fileName);
	    	// VALIDATION
    		// validate image size. Size is calculated in Bytes
	    		move_uploaded_file($_FILES["files"]["tmp_name"][$i], $target_file);
	    		$insert2 = "INSERT into tbl_files (student_id, file_name, category, uploaded_on) VALUES ('$scholar_id', '$fileName', '$file_category', NOW())";
	    		mysqli_query($conn, $insert2);
	  	} 

	  	$insert3 = "INSERT into tbl_scholarship_info (student_id, school, course, year, semester, scholarship_status, date_enrolled) VALUES ('$student_id', '$school', '$course', '$year', '$semester', '$status', NOW())";
	    	mysqli_query($conn, $insert3);

	    // if ($year == "1st" && $semester == "1st") {
	    // 	$update_query = "UPDATE tbl_application_info SET application_status = '$a_status' WHERE student_id = '$student_id'";
     //  		mysqli_query($conn, $update_query);
     //  	}

		$insert4 = "INSERT into tbl_schedules (activity, applicable_for, status) VALUES ('$activity', '$applicable_for', '$schedule_status')";
	    	mysqli_query($conn, $insert4);     		

 		$noteQuery = "INSERT INTO tbl_notification(note_subject, note_text, category) VALUES ('$note_subject', '$note_text', '$category')";
 		mysqli_query($conn, $noteQuery);

	  	header('location: online_enrollment.php?enrollment=success');
		exit();
	}else {
			$errors['db_error'] = "Failed to register!";
	}
}

if (isset($_POST['create-schedule'])) {
	$activity = $_POST['activity'];
	$date_start = $_POST['date-start'];
	$date_end = $_POST['date-end'];

	$applicable_for = "All";
	$status = "Open";

	// validation
	$scheduleQuery = "SELECT * FROM tbl_schedules WHERE activity=? AND date_start=? LIMIT 1";
	$stmt = $conn->prepare($scheduleQuery);
	$stmt->bind_param('ss', $activity, $date_start);
	$stmt->execute();
	$result = $stmt->get_result();
	$scheduleCount = $result->num_rows;
	$stmt->close();

	if ($scheduleCount === 1) {
		$errors['schedule'] = "Failed to create schedule! Schedule already exist!";
	}

	if (count($errors) === 0) {

		$insert1 = "INSERT into tbl_schedules (activity, applicable_for, date_start, date_end, status) VALUES ('$activity', '$applicable_for', '$date_start', '$date_end', '$status')";
		mysqli_query($conn, $insert1);

	  	header('location: schedule.php');
		exit();
	}
}





