<?php

	// DO NOT EDIT AFTER THIS LINE
	include('../../../php/config.php');
	
	function sendEmail($mail_to, $mail_body, $mail_subject, $mail_from, $mail_from_name, $api_key, $recaptcha_secret)
	{
		// Check recaptcha before preparing the email
		$captcha = $_POST["captcha"];
		$verify = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$captcha), true);
		$success = $verify["success"];

		if ($success == false) {
			print "<div class='alert alert-danger'>Wrong captcha verification. Please reload the page and try again</div>";

		} else {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\"personalizations\": [{\"to\": [{\"email\": \"$mail_from\"}]}],\"from\": {\"email\": \"$mail_from\", \"name\": \"$mail_from_name\"},\"subject\": \"$mail_subject\",\"content\": [{\"type\": \"text/html\", \"value\": \"$mail_body\"}]}",
				CURLOPT_HTTPHEADER => array(
					"authorization: Bearer $api_key",
					"cache-control: no-cache",
					"content-type: application/json"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				print "<div class='alert alert-danger'>Unable to send your request, please try again in few minutes</div>";
			} else {
				print "<div class='alert alert-success'>Your request has been sent to our team !</div>";
			}
		}
	}

	// Retrieve every form values
	$email = $_POST["email"];
	$name = $_POST["name"];
	$phone = $_POST["phoneNumber"];
	$message = $_POST["message"];

	// Build the mail body with every form fields values
	$mail_content = "<h2>New e-mail received from $website_name</h2>A new visitor has sent you a request via the site's contact form. Here is all his information<ul><li>Name: $name</li><li>Email: $email</li><li>Phone: $phone</li></ul><br /><hr /><br />Here is also the content of his request:<br /><br /><strong>$message</strong>";

	// Call the function to send the email using Sendgrid API
	sendEmail($_POST["email"], $mail_content, $subject, $mail_from, $mail_from_name, $cURL_key, $recaptcha_secret);
?>
