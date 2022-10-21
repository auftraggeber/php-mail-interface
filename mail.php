<?php

use PHPMailer\PHPMailer\PHPMailer;

session_start();

require_once 'fetch_data.php';
require_once 'phpmailer/PHPMailer.php';

File::fetchFiles();

$mail = new PHPMailer(true);
$mail->isSMTP();

?>