<?php

$from = '';
$to   = '';

if(!array_key_exists('form_name', $_POST)) {
    die('missing form name');
}

if ($_POST['form_name'] == 'form_1') {
	$fields = array('Name', 'Email', 'Company', 'City', 'State', 'Comments');
} elseif ($_POST['form_name'] == 'form_2') {
	$fields = array('Email');
} else {
	die('not a valid form name');
}

$csv = $_POST['form_name'] . '.csv';
if(!file_exists($csv)) {
	if(!touch($csv)) {
		die('form csv can not be created');
	}
}

if(filesize($csv) == 0) {
// touch file.csv leaves a file with 0 bytes, this populates the fields, if they need it
    $file = fopen($csv, 'w');
    fputcsv($file, $fields);
    fclose($file);
}

$data = array();
foreach ($fields as $field) {
	if(array_key_exists($field, $_POST)) {
		$data[$field] = $_POST[$field];
		var_dump($data);
	} else {
		$data[$field] = '';
	}
}
$file = fopen($csv, 'a');
fputcsv($file, $data);
fclose($file);

$subject = 'form ' . $_POST['form_name'] . ' submitted';
$body = $_POST['form_name']; // default value

$headers = 'From: ' . $from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
$mail_success = mail($to, $subject, $body, $headers);

if(!$mail_success) {die('failed to mail');}

header("location:javascript://history.go(-1)"); // go back 
//header("Location: http://www.redirect.to.url.com/"); // go somewhere
die('1');
