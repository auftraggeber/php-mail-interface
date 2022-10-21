<?php
use PHPMailer\PHPMailer\PHPMailer;

session_start();
ini_set('display_errors', 0);

require_once 'fetch_data.php';
require_once 'phpmailer/PHPMailer.php';

File::fetchFiles();

try {
    $mail = new PHPMailer(true);
    SMTPConfiguration::shared()->apply($mail);
    MailConfiguration::shared()->apply($mail);
    
    foreach (File::getFiles() as $file) {
        $mail->addAttachment($file->getPath(), $file->getDisplayName());
    }
    
    $mail->send();

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