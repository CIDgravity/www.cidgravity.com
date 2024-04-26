<?php

	// DO NOT EDIT AFTER THIS LINE
	include('../../../php/config.php');
	
	function handleContactFormToSlack($mail_content, $slack_token, $recaptcha_secret)
	{
		// Check recaptcha before preparing the email
		$captcha = $_POST["captcha"];
		$verify = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$captcha), true);
		$success = $verify["success"];

		if ($success == false) {
			print "<div class='alert alert-danger'>Wrong captcha verification. Please reload the page and try again</div>";

		} else {
			$ch = curl_init("https://slack.com/api/chat.postMessage");
			$data = http_build_query([
				"token" => $slack_token,
				"channel" => "#contact-form",
				"text" => $mail_content,
				"username" => "MySlackBot",
			]);
	
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);

			if ($err) {
				print "<div class='alert alert-danger'>Unable to send your request, please try again in few minutes</div>";
			} else {
				print "<div class='alert alert-success'>Your request has been sent to our team !</div>";
			}
		}
	}

	// Retrieve every form values
	$user_email = $_POST["email"];
	$user_name = $_POST["name"];
	$user_phone = $_POST["phoneNumber"];
	$message = $_POST["message"];

	// Build the mail body with every form fields values
	$mail_content = "<h2>New contact request</h2>A new visitor has sent you a request via the site's contact form. Here is all his information<ul><li>Name: $user_name</li><li>Email: $user_email</li><li>Phone: $user_phone</li></ul><br /><hr /><br />Here is also the content of his request:<br /><br /><strong>$message</strong>";

	// Call the function to send the email using Sendgrid API
	handleContactFormToSlack($mail_content, $slack_token, $recaptcha_secret);
?>
