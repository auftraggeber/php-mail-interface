<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

ini_set('display_errors', 0);

require_once 'fetch_data.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/Exception.php';
require_once 'phpmailer/SMTP.php';

File::fetchFiles();


try {
    $mail = new PHPMailer(true);
    
    if (isset($_POST['debug'])) {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    }
        
    SMTPConfiguration::shared()->apply($mail);
    MailConfiguration::shared()->apply($mail);
    
    foreach (File::getFiles() as $file) {
        $mail->addAttachment($file->getPath(), $file->getDisplayName());
    }

    $mail->send();
    $mail->smtpClose();

    die(json_encode([
        "success" => true,
        "message" => "done"
    ]));
}
catch (Exception $e) {
    die(json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]));
}

?>