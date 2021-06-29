<?php
require 'config/db.php';


if(isset($_POST['view'])){

  if($_POST["view"] != '')
    {
      $update_query = "UPDATE tbl_notification SET note_status = 1 WHERE note_status = 0";
      mysqli_query($conn, $update_query);
    }

    $update_query = "UPDATE tbl_notification SET note_status = 1 WHERE note_status = 0";
    mysqli_query($conn, $update_query);

    $query = "SELECT * FROM tbl_notification ORDER BY note_id DESC LIMIT 5";
    $result = mysqli_query($conn, $query);
    $output = '';
      if(mysqli_num_rows($result) > 0)
      {
        while($row = mysqli_fetch_array($result))
        {
          $output .= '
            <li>
            <a class="dropdown-item" href="'.$row["category"].'.php">
            <strong>'.$row["note_subject"].'</strong><br />
            <small><em>'.$row["note_text"].'</em></small>
            </a>
            </li>';
        }
      }
      else{
        $output .= '
          <li><a class="dropdown-item" href="#" class="text-bold text-italic">No Notifications Found</a></li>';
      }

      date_default_timezone_set('Asia/Manila');
      $current_date = date("Y-m-d"); 
      $selectSchedules = mysqli_query($conn, "SELECT * FROM tbl_schedules WHERE applicable_for='All' AND status<>'Closed' ORDER BY date_start DESC");
      $queries9 = mysqli_fetch_all($selectSchedules, MYSQLI_ASSOC);
      foreach ($queries9 as $query9) {
        $date_end = $query9['date_end'];
        $schedule_id = $query9['schedule_id'];
        $expiry = date("Y-m-d", strtotime($date_end . ' +1 day'));

        if ($current_date == $expiry) {
          $update_query2 = "UPDATE tbl_schedules SET status = 'Closed' WHERE schedule_id = '$schedule_id'";
          mysqli_query($conn, $update_query2);
        }
        
      }

  $status_query = "SELECT * FROM tbl_notification WHERE note_status=0";
  $result_query = mysqli_query($conn, $status_query);
  $count = mysqli_num_rows($result_query);
  $selectQuery1 = $conn->query("SELECT * from tbl_application_info WHERE application_status='New'");
  $newApplicantsCount = mysqli_num_rows($selectQuery1);
  $selectQuery2 = $conn->query("SELECT * from tbl_scholarship_info WHERE scholarship_status='New'");
  $newScholarsCount = mysqli_num_rows($selectQuery2);
  $selectQuery3 = $conn->query("SELECT * from tbl_scholarship_info WHERE scholarship_status<>'Inactive'");
  $activeCount = mysqli_num_rows($selectQuery3);
  $selectQuery4 = $conn->query("SELECT * from tbl_scholarship_info WHERE scholarship_status='Inactive'");
  $inactiveCount = mysqli_num_rows($selectQuery4);
  $selectQuery5 = $conn->query("SELECT * from tbl_exam_results WHERE remark='Passed'");
  $passedCount = mysqli_num_rows($selectQuery5);
  $selectQuery6 = $conn->query("SELECT * from tbl_exam_results WHERE remark='Failed'");
  $failedCount = mysqli_num_rows($selectQuery6);
  $selectQuery7 = $conn->query("SELECT * from tbl_application_info WHERE application_status<>'Scholarship Exam Taken' AND application_status<>'Scholar'");
  $didNotTakeCount = mysqli_num_rows($selectQuery7);
  $selectQuery8 = $conn->query("SELECT * from tbl_users WHERE status='Logged' AND role='scholar'");
  $loggedCount = mysqli_num_rows($selectQuery8);
  $selectQuery9 = $conn->query("SELECT * from tbl_students");
  $applicantsCount = mysqli_num_rows($selectQuery9);
  $data = array(
    'notification' => $output,
    'unseen_notification'  => $count,
    'new_applicants' => $newApplicantsCount,
    'new_scholars' => $newScholarsCount,
    'active_scholars' => $activeCount,
    'inactive_scholars' => $inactiveCount,
    'passed' => $passedCount,
    'failed' => $failedCount,
    'did_not_take' => $didNotTakeCount,
    'logged' => $loggedCount,
    'applicants' => $applicantsCount
  );

  echo json_encode($data);

}

if(isset($_POST['token'])){
  $exam_id = $_POST['exam'];
  $token = $_POST['token'];

  $score = 0;
  $remark = "";
  $category = "exam_results";

  $note_subject = "Started the Exam";

  $examQuery = "SELECT * FROM tbl_exam WHERE exam_id=? LIMIT 1";
  $stmt1 = $conn->prepare($examQuery);
  $stmt1->bind_param('d', $exam_id);
  $stmt1->execute();
  $result1 = $stmt1->get_result();
  $examResult = $result1->fetch_assoc();

  $applicantQuery = "SELECT * FROM tbl_tokens WHERE token=? LIMIT 1";
  $stmt2 = $conn->prepare($applicantQuery);
  $stmt2->bind_param('s', $token);
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

  $note_text = $applicantResult2['first_name'] . " " . $applicantResult2['last_name'] . " started the ". $examResult['exam_name'] .".";

  $insert2 = "INSERT into tbl_exam_results (student_id, exam_id, score, date_taken, remark) VALUES ('$applicant_id', '$exam_id', '$score', NOW(), '$remark')";
    mysqli_query($conn, $insert2);

    $noteQuery = "INSERT INTO tbl_notification(note_subject, note_text, category) VALUES ('$note_subject', '$note_text', '$category')";
  mysqli_query($conn, $noteQuery);

}

?>