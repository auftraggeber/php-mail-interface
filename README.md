# php-mail-interface

Extending https://github.com/PHPMailer/PHPMailer by some basic HTTP-Method handling.

SMTP only (for now).

## available post params (call mail.php to send)

|param|type|description|
|--|--|--|
|*file_path_{0...i}*|       ||
|*file_name_{0...i}*|       |Name of attachment {0...i}                                 |
|*smtp_host*        |       |                                                           |
|*smtp_port*        |(int)  |                                                           |
|*smtp_username*    |       |                                                           |
|*smtp_password*    |       |                                                           |
|*smtp_encryption*  |       |                                                           |
|*smtp_auth*        |(bool) |                                                           |
|*from*             |       |Displayed mail address of sender                           |
|*from_name*        |       |Displayed name of sender                                   |
|*to*               |(json) |Array of all receivers                                     |
|*cc*               |(json) |...                                                        |
|*bcc*              |(json) |...                                                        |
|*subject*          |       |                                                           |
|*body*             |       |                                                           |
|*body_is_html*     |(bool) |                                                           |
|*charset*          |       |                                                           |
