## Designli technical test

This is a php test using laravel using mail-parser to parse the content of an email with attachments.

Here is an example to execute the end point:

```php
curl -X POST -F 'email_path=/path/to/email.eml' http://localhost:8000/parse-email
```

