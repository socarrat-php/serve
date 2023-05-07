# `class Socarrat\Serve\HttpRequest`

Represents an HTTP request.

| Public property name | Type                                | Description                                          |
|----------------------|-------------------------------------|------------------------------------------------------|
| `$route`             | [`RouteHandler`](./routehandler.md) | The route handler that handles the requested route.  |
| `$uri`               | [`URI`](./uri.md)                   | The request URI.                                     |
| `$params`            | `array`                             | Associative array with route params (NOT the query). |
| `$method`            | `string`                            | The request method.                                  |
| `$contentType`       | `string`                            | The content type.                                    |

## Constructor

| Parameter name | Type                                | Default value | Description                                          |
|----------------|-------------------------------------|---------------|------------------------------------------------------|
| `$route`       | [`RouteHandler`](./routehandler.md) | -             | The route handler that handles the requested route.  |
| `$uri`         | [`URI`](./uri.md)                   | -             | The request URI.                                     |
| `$method`      | `callable` | -                      | -             | The request method.                                  |
| `$contentType` | `callable` | -                      | -             | The content type.                                    |
| `$params`      | `callable` | -                      | -             | Associative array with route params (NOT the query). |

## `public function body()`

Returns the request body.
// @todo ??

## `public function json()`

Returns the request body parsed as JSON.
