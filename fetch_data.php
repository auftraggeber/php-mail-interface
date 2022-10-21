<?php

use PHPMailer\PHPMailer\PHPMailer;

final class File {

    const POST_FILE_PATH_PARAM = "file_path_";
    const POST_FILE_NAME_PARAM = "file_name_";

    private static array $files = [];

    public static function fetchFiles() {
        $i = 0;
        while (isset($_POST[self::POST_FILE_PATH_PARAM . $i])) {
            new File($_POST[self::POST_FILE_PATH_PARAM . $i], $_POST[self::POST_FILE_NAME_PARAM . $i]);
            $i++;
        }
    }

    private static function append(File $file): void {
        self::$files[] = $file;
    }

    /**
     * @return File[] all files that were fetched
     */
    public static function getFiles(): array {
        return self::$files;
    }

    private string $path;
    private string $display_name;

    public function __construct(string $path, ?string $display_name) {
        $this->path = $path;
        $this->display_name = $display_name ?? basename($path);

        if (file_exists($this->path)) {
            self::append($this);
        }
    }

    public function getPath(): string {
        return $this->path;
    }

    public function getDisplayName(): string {
        return $this->display_name;
    }
}

final class SMTPConfiguration {
    const POST_SMTP_HOST_PARAM = "smtp_host";
    const POST_SMTP_PORT_PARAM = "smtp_port";
    const POST_SMTP_USERNAME_PARAM = "smtp_username";
    const POST_SMTP_PASSWORD_PARAM = "smtp_password";
    const POST_SMTP_ENCRYPTION_PARAM = "smtp_encryption";
    const POST_SMTP_AUTH_PARAM = "smtp_auth";

    private static ?SMTPConfiguration $instance = null;

    public static function shared(): SMTPConfiguration {
        if (self::$instance === null) {
            self::$instance = new SMTPConfiguration();
        }
        return self::$instance;
    }

    private ?string $host = null;
    private ?int $port = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $encryption = null;
    private ?bool $auth = null;

    private function __construct() {
        $this->host = $_POST[self::POST_SMTP_HOST_PARAM] ?? null;
        $this->port = isset($_POST[self::POST_SMTP_PORT_PARAM]) ? intval($_POST[self::POST_SMTP_PORT_PARAM]) : null;
        $this->username = $_POST[self::POST_SMTP_USERNAME_PARAM] ?? null;
        $this->password = $_POST[self::POST_SMTP_PASSWORD_PARAM] ?? null;
        $this->encryption = $_POST[self::POST_SMTP_ENCRYPTION_PARAM] ?? null;
        $this->auth = isset($_POST[self::POST_SMTP_AUTH_PARAM]) ? $_POST[self::POST_SMTP_AUTH_PARAM] == 1 : null;
    }

    public function apply(PHPMailer $phpMailer) {
        $phpMailer->isSMTP();

        if ($this->host !== null) {
            $phpMailer->Host = $this->host;
        }
        if ($this->port !== null) {
            $phpMailer->Port = $this->port;
        }
        if ($this->username !== null) {
            $phpMailer->Username = $this->username;
        }
        if ($this->password !== null) {
            $phpMailer->Password = $this->password;
        }
        if ($this->encryption !== null) {
            $phpMailer->SMTPSecure = $this->encryption;
        }
        if ($this->auth !== null) {
            $phpMailer->SMTPAuth = $this->auth;
        }
    }
}

final class MailConfiguration {

    const POST_FROM_PARAM = "from";
    const POST_FROM_NAME_PARAM = "from_name";
    const POST_TO_PARAM = "to";
    const POST_CC_PARAM = "cc";
    const POST_BCC_PARAM = "bcc";
    const POST_SUBJECT_PARAM = "subject";
    const POST_BODY_PARAM = "body";
    const POST_BODY_IS_HTML_PARAM = "body_is_html";
    const POST_CHARSET_PARAM = "charset";

    private static ?MailConfiguration $instance = null;

    public static function shared(): MailConfiguration {
        if (self::$instance === null) {
            self::$instance = new MailConfiguration();
        }
        return self::$instance;
    }

    private ?string $from = null;
    private ?string $from_name = null;
    private ?array $to = null;
    private ?array $cc = null;
    private ?array $bcc = null;
    private ?string $subject = null;
    private ?string $body = null;
    private ?bool $body_is_html = null;
    private ?string $charset = null;

    private function __construct() {
        $this->from = $_POST[self::POST_FROM_PARAM] ?? null;
        $this->from_name = $_POST[self::POST_FROM_NAME_PARAM] ?? null;
        $this->to = isset($_POST[self::POST_TO_PARAM]) ? json_decode($_POST[self::POST_TO_PARAM]) : null;
        $this->cc = isset($_POST[self::POST_CC_PARAM]) ? json_decode($_POST[self::POST_CC_PARAM]) : null;
        $this->bcc = isset($_POST[self::POST_BCC_PARAM]) ? json_decode($_POST[self::POST_BCC_PARAM]) : null;
        $this->subject = $_POST[self::POST_SUBJECT_PARAM] ?? null;
        $this->body = $_POST[self::POST_BODY_PARAM] ?? null;
        $this->body_is_html = isset($_POST[self::POST_BODY_IS_HTML_PARAM]) ? $_POST[self::POST_BODY_IS_HTML_PARAM] == 1 : null;
        $this->charset = $_POST[self::POST_CHARSET_PARAM] ?? null;
    }

    public function apply(PHPMailer $phpMailer) {
        if ($this->from !== null) {
            $phpMailer->setFrom($this->from, $this->from_name);
        }
        if ($this->to !== null) {
            foreach ($this->to as $to) {
                $phpMailer->addAddress($to);
            }
        }
        if ($this->cc !== null) {
            foreach ($this->cc as $cc) {
                $phpMailer->addCC($cc);
            }
        }
        if ($this->bcc !== null) {
            foreach ($this->bcc as $bcc) {
                $phpMailer->addBCC($bcc);
            }
        }
        if ($this->subject !== null) {
            $phpMailer->Subject = $this->subject;
        }
        if ($this->body !== null) {
            $phpMailer->Body = $this->body;
        }
        if ($this->body_is_html !== null) {
            $phpMailer->isHTML($this->body_is_html);
        }
        if ($this->charset !== null) {
            $phpMailer->CharSet = $this->charset;
        }
    }
}

?>