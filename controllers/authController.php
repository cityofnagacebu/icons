<?php

session_start();

require 'config/db.php';
require_once 'emailController.php';

$errors = array();
$firstname = "";
$lastname = "";
$email = "";
$_SESSION['verified'] = 0;

// if admin clicks on the sign up button
if (isset($_POST['register-admin'])) {
	$firstname = $_POST['first_name'];
	$lastname = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirmpassword = $_POST['confirmpassword'];

	$role = "admin";

	// validation
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    	$errors['password'] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
	}

	if ($password !== $confirmpassword) {
		$errors['password'] = "The two password do not match.";
	}

	$emailQuery = "SELECT * FROM tbl_users WHERE email=? LIMIT 1";
	$stmt = $conn->prepare($emailQuery);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$adminCount = $result->num_rows;
	$stmt->close();

	if ($adminCount > 0) {
		$errors['email'] = "Email already exists";
	}

	if (count($errors) === 0) {
		$password = password_hash($password, PASSWORD_DEFAULT);
		$token = bin2hex(random_bytes(50));
		$verified = false;

		$sql = "INSERT INTO tbl_users (first_name, last_name, email, verified, token, password, role, date_added) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('sssbsss', $firstname, $lastname, $email, $verified, $token, $password, $role);
		if ($stmt->execute()) {
			// login admin
			$admin_id = $conn->insert_id;
			$_SESSION['admin_id'] = $admin_id;
			$_SESSION['firstname'] = $firstname;
			$_SESSION['email'] = $email;
			$_SESSION['verified'] = $verified;

			sendVerificationEmail($email, $token);

			// set flash message
			$_SESSION['message'] = ". You are now logged in!";
			$_SESSION['alert-class'] = "alert-success";
			header('location: verify_account.php');
			exit();
		} else {
			$errors['db_error'] = "Failed to register!";
		}
	}
}

// if admin clicks on the done button
if (isset($_POST['login-admin'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	// validation
	if (count($errors) === 0) {
		$sql = "SELECT * FROM tbl_users WHERE email=? LIMIT 1";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('s', $email);
		$stmt->execute();
		$result = $stmt->get_result();
		$admin = $result->fetch_assoc();

		if (password_verify($password, $admin['password'])) {
			// login admin
				if ($admin['verified'] === 0) {
					$errors['login_fail'] = "Your email is not verified yet.";	
				} else {
					$update_query = "UPDATE tbl_users SET status = 'Logged' WHERE email = '$email'";
      				mysqli_query($conn, $update_query);
					$_SESSION['admin_id'] = $admin['user_id'];
					$_SESSION['role'] = $admin['role'];
					$_SESSION['firstname'] = $admin['first_name'];
					$_SESSION['email'] = $admin['email'];
					$_SESSION['verified'] = $admin['verified'];
					// set flash message
					$_SESSION['message'] = "Hello ".$_SESSION['firstname'].". You are now logged in!";
					$_SESSION['alert-class'] = "alert-success";
					header('location: admin_dashboard.php');
					exit();
				}
		} else {
			$errors['login_fail'] = "Login credentials do not match any account!";
		}
	}
}

// logout admin
if (isset($_GET['logout-admin'])) {
	$admin_id = $_GET['logout-admin'];
	$update_query = "UPDATE tbl_users SET status = '' WHERE user_id = '$admin_id'";
	session_destroy();
	unset($_SESSION['admin_id']);
	unset($_SESSION['firstname']);
	unset($_SESSION['email']);
	unset($_SESSION['verified']);
	header('location: schlradminlogin.php');
	exit();
}



// if scholar clicks on the sign up button
if (isset($_POST['register-scholar'])) {
	$id = $_POST['scholar-id'];
	$password = $_POST['password'];
	$confirmpassword = $_POST['confirmpassword'];

	$role = "scholar";

	// validation
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    	$errors['password'] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.";
	}

	if ($password !== $confirmpassword) {
		$errors['password'] = "The two password do not match.";
	}

	$scholarQuery = "SELECT * FROM tbl_students WHERE student_id=? LIMIT 1";
	$stmt = $conn->prepare($scholarQuery);
	$stmt->bind_param('s', $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$scholarCount = $result->num_rows;
	$scholar = $result->fetch_assoc();

	$student_id = $scholar['student_id'];
	$firstname = $scholar['first_name'];
	$lastname = $scholar['last_name'];

	$scholarQuery2 = "SELECT * FROM tbl_contact_details WHERE student_id=? LIMIT 1";
	$stmt3 = $conn->prepare($scholarQuery2);
	$stmt3->bind_param('s', $student_id);
	$stmt3->execute();
	$result3 = $stmt3->get_result();
	$scholar2 = $result3->fetch_assoc();	

	$email = $scholar2['email'];

	$scholarQuery3 = "SELECT * FROM tbl_tokens WHERE student_id=? LIMIT 1";
	$stmt4 = $conn->prepare($scholarQuery3);
	$stmt4->bind_param('s', $student_id);
	$stmt4->execute();
	$result4 = $stmt4->get_result();
	$scholar3 = $result4->fetch_assoc();

	$token = $scholar3['token'];

	$emailQuery = "SELECT * FROM tbl_users WHERE email=? LIMIT 1";
	$stmt2 = $conn->prepare($emailQuery);
	$stmt2->bind_param('s', $email);
	$stmt2->execute();
	$result2 = $stmt2->get_result();
	$userCount = $result2->num_rows;
	$stmt2->close();

	if ($userCount > 0) {
		$errors['email'] = "Email already exists";
	}

	$note_subject = "New User";
	$note_text = $firstname . " " . $lastname . " created an account.";
	$category = "scholars";

	if (count($errors) === 0) {
		$password = password_hash($password, PASSWORD_DEFAULT);
		$verified = 1;

		$sql = "INSERT INTO tbl_users (first_name, last_name, email, verified, token, password, role, date_added) VALUES ('$firstname', '$lastname', '$email', '$verified', '$token', '$password', '$role', NOW())";
		mysqli_query($conn, $sql);

		$noteQuery = "INSERT INTO tbl_notification(note_subject, note_text, category) VALUES ('$note_subject', '$note_text', '$category')";
 			mysqli_query($conn, $noteQuery);

		$location = "location: login.php?firstname=".$firstname."&lastname=".$lastname."&registration=success";
			header($location);
			exit();
	} 
}

// if scholar clicks on the done button
if (isset($_POST['login-scholar'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];

	$s_password = "";

	$sql = "SELECT * FROM tbl_users WHERE email=? LIMIT 1";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$scholar = $result->fetch_assoc();

	// validation
	if(mysqli_num_rows($result) == 0){
		$errors['login_fail'] = "Email does not exist!";
	} else {
		$s_password = $scholar['password'];
		if (!password_verify($password, $s_password)){
			$errors['login_fail'] = "Incorrect password!";
		}		
	}

	if (count($errors) === 0) {
		
			// login scholar
		$update_query = "UPDATE tbl_users SET status = 'Logged' WHERE email = '$email'";
      	mysqli_query($conn, $update_query);

					$_SESSION['scholar_id'] = $scholar['user_id'];
					$_SESSION['firstname'] = $scholar['first_name'];
					$_SESSION['email'] = $scholar['email'];
					$_SESSION['verified'] = $scholar['verified'];
					// set flash message
					$_SESSION['message'] = "Hello ".$_SESSION['firstname'].". You are now logged in!";
					$_SESSION['alert-class'] = "alert-success";
					header('location: myprofile.php');
					exit();
	}
}

// logout scholar
if (isset($_GET['logout-scholar'])) {
	$scholar_id = $_GET['logout-scholar'];
	$update_query = "UPDATE tbl_users SET status = '' WHERE user_id = '$scholar_id'";
    mysqli_query($conn, $update_query);	
	session_destroy();
	unset($_SESSION['scholar_id']);
	unset($_SESSION['firstname']);
	unset($_SESSION['email']);
	unset($_SESSION['verified']);
	header('location: login.php');
	exit();
}


//verify admin by token
function verifyAdmin($token)
{
	global $conn;
	$sql = "SELECT * FROM tbl_users WHERE token='$token' LIMIT 1";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
		$user = mysqli_fetch_assoc($result);
		$update_query = "UPDATE tbl_users SET verified=1 WHERE token='$token'";

		if (mysqli_query($conn, $update_query)) {
			$_SESSION['firstname'] = $user['first_name'];
			$_SESSION['verified'] = 1;
			$_SESSION['message'] = "Hello ".$_SESSION['firstname'].". You are now successfully registered.";
  			$_SESSION['alert-class'] = "alert-success";
		}
	} else {
		echo 'User not found';
	}
}

if (isset($_POST["reset-request-submit"])){

	$selector = bin2hex(random_bytes(8));
	$token = random_bytes(32);

	$url = "http://localhost/iamacityofnagascholar/create_new_password.php?selector=" . $selector . "&validator=" . bin2hex($token);

	$expires = date("U") + 900;

	$userEmail = $_POST['email'];

	$sql = "DELETE FROM tbl_passwordreset WHERE reset_email=?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
	} else {
		mysqli_stmt_bind_param($stmt, "s", $userEmail);
		mysqli_stmt_execute($stmt);
	}

	$sql = "INSERT INTO tbl_passwordreset (reset_email, reset_selector, reset_token, reset_expires) VALUES (?, ?, ?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)){
		echo "There was an error!";
	} else {
		$hashedToken = password_hash($token, PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashedToken, $expires);
		mysqli_stmt_execute($stmt);
	}

	mysqli_stmt_close($stmt);
	mysqli_close($conn);

	sendPasswordResetLink($userEmail, $url);

	header("Location: forgot_password.php?resetlink=sent");

}


if (isset($_POST["reset-password-submit"])){
	
	$selector = $_POST["selector"];
	$validator = $_POST["validator"];
	$password = $_POST["password"];
	$passwordRepeat = $_POST["confirmpassword"];



	if ($password != $passwordRepeat) {
		$errors['reset-fail'] = "The two password did not match!";
	} else {

	$currentDate = date("U");

	$sql = "SELECT * FROM tbl_passwordreset WHERE reset_selector=? AND reset_expires >= ?";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		$errors['reset-fail'] = "There was an error!";
	} else {
		mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
		mysqli_stmt_execute($stmt);

		$result = mysqli_stmt_get_result($stmt);
		if (!$row = mysqli_fetch_assoc($result)) {
			$errors['reset-fail'] = "You need to re-submit your reset request.";
		} else {

			$tokenBin = hex2bin($validator);
			$tokenCheck = password_verify($tokenBin, $row["reset_token"]);

			if ($tokenCheck === false) {
				$errors['reset-fail'] = "You need to re-submit your reset request.";
			} elseif ($tokenCheck === true) {

				$tokenEmail = $row['reset_email'];

				$sql = "SELECT * FROM tbl_users WHERE email=?;";
				$stmt = mysqli_stmt_init($conn);
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					$errors['reset-fail'] = "There was an error!";
				} else {
					mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					if (!$row = mysqli_fetch_assoc($result)) {
						$errors['reset-fail'] = "There was an error!";
					} else {

						$sql = "UPDATE tbl_users SET password=? WHERE email=?";
						$stmt = mysqli_stmt_init($conn);
						if (!mysqli_stmt_prepare($stmt, $sql)) {
							$errors['reset-fail'] = "There was an error!";
						} else {
							$newPwdHash = password_hash($password, PASSWORD_DEFAULT);
							mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
							mysqli_stmt_execute($stmt);

							$sql = "DELETE FROM tbl_passwordreset WHERE reset_email=?;";
							$stmt = mysqli_stmt_init($conn);
							if (!mysqli_stmt_prepare($stmt, $sql)){
								echo "There was an error!";
							} else {
								mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
								mysqli_stmt_execute($stmt);
								header("Location: login.php?newpswd=password_updated");
							}
						}
					}
				}
			}
		}
	}
}
}