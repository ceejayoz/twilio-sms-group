<?php

require_once('Services/Twilio.php');

define('TWILIO_SID', '');
define('TWILIO_TOKEN', '');
define('TWILIO_NUMBER', '+15555555555');
define('MODERATOR_NUMBER', '+15555555555');

$log = fopen('text.log', 'a');

$users = array(
	'Name' => '+15555555555',
	'Name 2' => '+15555555555',
);

$client = new Services_Twilio(TWILIO_SID, TWILIO_TOKEN);

if($_POST['From']) {
	if($_POST['From'] == MODERATOR_NUMBER) {
		$message = $_POST['Body'];
		
		fwrite($log, 'INBOUND MODERATOR: ' . $message . "\n");
		
		foreach($users as $name => $number) {
			fwrite($log, 'OUTBOUND MESSAGE (' . $name . ' - ' . $number . '): ' . $message . "\n");
			
			$client->account->sms_messages->create(TWILIO_NUMBER, $number, $message);
		}
		
		$client->account->sms_messages->create(TWILIO_NUMBER, MODERATOR_NUMBER, $message);
	} else {
		$message = $_POST['Body'];
		
		// figure out which of our users this is from
		foreach($users as $name => $number) {
			if($_POST['From'] == $number) {
				$from = $name;
				break;
			}
		}
		
		if(!$from) {
			// unknown user
			$from = $_POST['From'];
		}
		
		$message = $from . ': ' . $message;
		
		if(strlen($message) > 160) {
			// with the addition of the name, we may hit the limit
			// split it into chunks
			$message = str_split($message, 160);
		}
		
		fwrite($log, 'INBOUND USER (' . $from . ' - ' . $_POST['From'] . '): ' . $_POST['Body'] . "\n");
		
		if(is_array($message)) {
			foreach($message as $part) {
				$client->account->sms_messages->create(TWILIO_NUMBER, MODERATOR_NUMBER, $part);
			}
		} else {
			$client->account->sms_messages->create(TWILIO_NUMBER, MODERATOR_NUMBER, $message);
		}
	}
}