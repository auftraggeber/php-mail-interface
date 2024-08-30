# php-mail-interface

Extending https://github.com/PHPMailer/PHPMailer by some basic HTTP-Method handling.

SMTP only (for now).

## available post params (call mail.php to send)

|param|type (default: string)|description|
|--|--|--|
|*file_path_{0...i}*|       |Path of attachment {0..i} _has to be on the same server_|
|*file_name_{0...i}*|       |Name of attachment {0..i}                                 |
|*smtp_host*        |       |                                                           |
|*smtp_port*        |(int)  |                                                           |
|*smtp_username*    |       |                                                           |
|*smtp_password*    |       |                                                           |
|*smtp_encryption*  |       |                                                           |
|*smtp_auth*        |(bool **post as int 0/1)** |                                                           |
|*from*             |       |Displayed mail address of sender                           |
|*from_name*        |       |Displayed name of sender                                   |
|*to*               |(json) |Array of all receivers                                     |
|*cc*               |(json) |...                                                        |
|*bcc*              |(json) |...                                                        |
|*subject*          |       |                                                           |
|*body*             |       |                                                           |
|*body_is_html*     |(bool **post as int 0/1**) |                                                           |
|*charset*          |       |                                                           |

## body cache (call cache_body.php)

- used to post body in chunks
- param body contains the content
- param chunk is the chunk-identifier
- param cache_id is the cache-identifier. this var will be created after the first chunk was stored. this will be part of the response. you will have to pass this param with furter chunks
- to load a cached body into the mail you have to pass the cache_id when sending. the cache will be overwritten if you pass the "body" param

## settings and auth manager

- examples for settings and auth manager are located in examples/
- move them into setup/ to initialize them
