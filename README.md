## Designli technical test

This is a php test using laravel using mail-parser to parse the content of an email with attachments, to run the project create on the root a `.env` file (you can copy the content of `.env.example`). then run:


```bash
composer install
```

```bash
php artisan serve
```


### Endpoint
`/parse-email`

### Method
`POST`

### Description
This endpoint processes an `.eml` file to extract a JSON attachment from the email. If a JSON file is found as an attachment, it returns the contents of that JSON file.

### Request

- **Form Data Parameter**:
  - `email_path`: The file path to the `.eml` file that needs to be parsed, you can put a .eml file on /public folder or use an URL that returns an .eml file.

### Example Request
```bash
curl -X POST -F 'email_path=mail.emil' http://localhost:8000/parse-email