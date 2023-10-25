<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

ini_set('display_errors', 0);

require_once 'auth.php';

if (!file_exists("setup") || !is_dir("setup")) {
    mkdir("setup");
}

if (file_exists("setup") && is_dir("setup")) {
    foreach (scandir("setup") as $filename) {
        $path = "setup/" . $filename;
        if (pathinfo($path)['extension'] === "php") {
            include_once $path;
        }
    }
}

auth_this_http_request();

require_once 'vendor/autoload.php';
require_once 'fetch_data.php';
require_once 'cache.php';
require_once 'settings.php';

$_POST = DefaultSettings::applyOn($_POST);

BodyCache::deleteOldCaches();
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