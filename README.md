# php-mail-interface

Extending https://github.com/PHPMailer/PHPMailer by some basic HTTP-Method handling.

SMTP only (for now).

## accepted post params (call mail.php to send)

- *file_path_{0...i}*   Path of attachment {0...i} (has to be on the same server)
- *file_name_{0...i}*   Name of attachment {0...i}
- *smtp_host*
- *smtp_port*   (int)
- *smtp_username*
- *smtp_password*
- *smtp_encryption*
- *smtp_auth*   (bool)
- *from*                Displayed mail address of sender
- *from_name*           Displayed name of sender
- *to*          (json)  Array of all receivers
- *cc*          (json)  ...
- *bcc*         (json)  ...
- *subject*
- *body*
- 

