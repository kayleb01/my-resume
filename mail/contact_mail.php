<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
require '../vendor/autoload.php';

$errors = [];
$my_mail = 'calebbala15@gmail.com';

if(!empty($_POST)){
	$name = stripcslashes($_POST['name']);
	$email = stripcslashes($_POST['email']);
	$subject = stripcslashes($_POST['subject']);
	$message = stripcslashes($_POST['message']);

	//Validation
	if (empty($name)) {
		$errors[] = "name field is empty";
	}
	if (empty($email)) {
		$errors[] = "email field is empty";
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors[] = "email provided is not a valid email";
	}
	if (empty($subject)) {
		$errors[] = "subject field is empty";
	}
	if (empty($message)) {
		$errors[] = "message field is empty";
	}

	if (!empty($errors)) {
		$_SESSION['errors'] = $errors;
		header('location:../index.php');
	}else{
		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->Host = 'smtp.mailtrap.io';
		$mail->SMTPAuth = true;
		$mail->Username = 'ecccc128d700bb';
		$mail->Password = '60e01e6b9d1766';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 2525;

		$mail->setFrom($email, 'Resume Contact Form');
		$mail->addAddress($my_mail, 'Me');
		$mail->Subject = $subject;

		$mail->isHTML(true);

		$bodyMessage = [ "Name: {$name}", "Email: {$email}", "Message:", nl2br($message)];
		$body = join('<br>', $bodyMessage);
		$mail->Body = $body;

		if($mail->send()){
			http_response_code(200);
			echo json_encode(['response' => 'success']);
		}else{
		$errors[] = "Something went wrong, please try again";
		$_SESSION['errors'] = $errors;
		header('location:../index.php');	
		}
	}
}