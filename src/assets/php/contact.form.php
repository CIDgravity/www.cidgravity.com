<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// DO NOT EDIT AFTER THIS LINE
	include('./config.php');
	
	function handleContactFormToSlack($contact_name, $contact_email, $contact_phone, $contact_message, $slack_webhook_endpoint, $recaptcha_secret)
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
			print "<div class='alert alert-danger'>Unable to send your request, please try again in few minutes</div>";
		} else {
			print "<div class='alert alert-success'>Your request has been sent to our team !</div>";
		}
	}

	// Retrieve every form values
	$user_email = $_POST["email"];
	$user_name = $_POST["name"];
	$user_phone = $_POST["phoneNumber"];
	$message = $_POST["message"];

	// Call the function to send the email using Sendgrid API
	handleContactFormToSlack($user_name, $user_email, $user_phone, $message, $slack_webhook_endpoint, $recaptcha_secret);
?>
