<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// DO NOT EDIT AFTER THIS LINE
	include('./config.php');
	
	// Handle contact form to email using sendgrid API
	function handleContactFormToEmail($contact_name, $contact_email, $contact_phone, $contact_message, $sendgrid_api_key, $sendgrid_registered_from)
	{
		// Send the email using sendgrid API
		$curl = curl_init();

		// Define some variables
		$mail_body = "<h2>New e-mail received from CIDgravity contact form</h2>A new visitor has sent you a request via the site's contact form. Here is all his information<ul><li>Name: $contact_name</li><li>Email: $contact_email</li><li>Phone: $contact_phone</li></ul><br /><hr /><br />Here is also the content of his request:<br /><br /><strong>$contact_message</strong>";
		$mail_subject = "[CONTACT] New message from CIDgravity contact form"
		$mail_from_name = "CIDgravity contact"

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"personalizations\": [{\"to\": [{\"email\": \"$sendgrid_registered_from\"}]}],\"from\": {\"email\": \"$sendgrid_registered_from\", \"name\": \"$mail_from_name\"},\"subject\": \"$mail_subject\",\"content\": [{\"type\": \"text/html\", \"value\": \"$mail_body\"}]}",
			CURLOPT_HTTPHEADER => array(
				"authorization: Bearer $sendgrid_api_key",
				"cache-control: no-cache",
				"content-type: application/json"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return false
		}

		return true
	}

	// Handle contact form to slack message
	function handleContactFormToSlack($contact_name, $contact_email, $contact_phone, $contact_message, $slack_webhook_endpoint)
	{
		$data = '{
			"blocks": [
				{
					"type": "section",
					"text": {
						"type": "mrkdwn",
						"text": "New request from cidgravity.com contact form has arrived!\nHere is all the details you need to know"
					}
				},
				{
					"type": "divider"
				},
				{
					"type": "section",
					"text": {
						"type": "mrkdwn",
						"text": "\n\n*Contact name:* ' . $contact_name . '\n*Email:* ' . $contact_email . '\n*Phone number:* ' . $contact_phone . '"
					}
				},
				{
					"type": "divider"
				},
				{
					"type": "section",
					"text": {
						"type": "mrkdwn",
						"text": "' . $contact_message . '"
					}
				}
			]
		}';

		// Initialize curl and send the request
		$ch = curl_init($slack_webhook_endpoint);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data)
		));

		$response = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);

		if ($err) {
			return false
		}

		return true
	}

	// Retrieve every form values
	$user_email = $_POST["email"];
	$user_name = $_POST["name"];
	$user_phone = $_POST["phoneNumber"];
	$message = $_POST["message"];

	// Check captcha
	$verify = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$_POST["captcha"]), true);

	if ($verify["success"] == false) {
		print "<div class='alert alert-danger'>Wrong captcha verification. Please reload the page and try again</div>";

	} else {

		// Call the function to send the slack message using webhook
		$success_slack = handleContactFormToSlack($user_name, $user_email, $user_phone, $message, $slack_webhook_endpoint);

		// Call the function to send the email using Sendgrid API
		$success_email = handleContactFormToEmail($user_name, $user_email, $user_phone, $message, $sendgrid_api_key, $sendgrid_registered_from);

		if ($success_slack && $success_email) {
			print "<div class='alert alert-success'>Your request has been sent to our team !</div>";
		} else {
			print "<div class='alert alert-danger'>Unable to send your request, please try again in few minutes</div>";
		}
	}
?>
