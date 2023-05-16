# `class Socarrat\Serve\HttpResponder`

Contains methods used to respond to an HTTP request.

## `public function status(int $statusCode)`

Sets the status code for the response.

| Parameter name | Type  | Default value | Description             |
|----------------|-------|---------------|-------------------------|
| `$statusCode`  | `int` | -             | The status code to set. |

## `public function header(string $name, string $value)`

Sets a header key/value pair for the response.

| Parameter name | Type     | Default value | Description       |
|----------------|----------|---------------|-------------------|
| `$name`        | `string` | -             | The header name.  |
| `$value`       | `string` | -             | The header value. |

## `public function echo(mixed $body)`

Sends the given value to the client.

| Parameter name | Type    | Default value | Description         |
|----------------|---------|---------------|---------------------|
| `$body`        | `mixed` | -             | The body to render. |

## `public function text(string $text = "")`

Sends the given string to the client and sets the content type to `text/plain`.

| Parameter name | Type     | Default value | Description           |
|----------------|----------|---------------|-----------------------|
| `$text`        | `string` | `""`          | The string to render. |

## `public function json(mixed $data = [])`

Sends the given JSON to the client and sets the content type to `application/json`.

| Parameter name | Type    | Default value | Description                 |
|----------------|---------|---------------|-----------------------------|
| `$data`        | `mixed` | `[]`          | The data to render as JSON. |

## `public function html(string $html = "")`

Sends the given HTML to the client and sets the content type to `text/html`.

| Parameter name | Type     | Default value | Description         |
|----------------|----------|---------------|---------------------|
| `$html`        | `string` | `""`          | The HTML to render. |

## `public function file(string $fname)`

Reads the file using the given file name, sends it to the client, and tries to guess the value for the `Content-Type` header if not set yet.

| Parameter name | Type     | Default value | Description                     |
|----------------|----------|---------------|---------------------------------|
| `$fname`       | `string` | -             | The name of the file to render. |
