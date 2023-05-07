# `class Socarrat\Serve\HttpResponse`

Represents an HTTP response.

## `public function setStatusCode(int $statusCode)`

Sets the status code for the response.

| Parameter name | Type  | Default value | Description             |
|----------------|-------|---------------|-------------------------|
| `$statusCode`  | `int` | -             | The status code to set. |

## `public function setHeader(string $name, string $value)`

Sets a header key/value pair for the response.

| Parameter name | Type     | Default value | Description       |
|----------------|----------|---------------|-------------------|
| `$name`        | `string` | -             | The header name.  |
| `$value`       | `string` | -             | The header value. |

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

## `public function send()`

Sends the response. **Warning:** do not call this in your route definitions!
