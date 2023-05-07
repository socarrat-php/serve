# `class Socarrat\Serve\RouteHandler`

`RouteHandler`s represent [`Router`](./router.md)-registered routes.

## Constructor

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$path`        | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `array`    | -             | An array of allowed HTTP methods, e.g. `["GET", "DELETE"]                                          |
| `$callback`    | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function getPath()`

Returns the route handler path.

## `public function getPathSegments()`

Returns the route handler path segments.

## `public function getMethods()`

Returns the array of methods the route handler accepts.

## `public function canHandleMethod(string $method)`

Returns true if this handler can handle the given method, false otherwise.

| Parameter name | Type     | Default value | Description                      |
|----------------|----------|---------------|----------------------------------|
| `$method`      | `string` | -             | The method name, e.g. `"POST"`.  |

## `public function execute(HttpRequest $req): ?HttpResponse`

Executes the route handler.

| Parameter name | Type                              | Default value | Description  |
|----------------|-----------------------------------|---------------|--------------|
| `$req`         | [`HttpRequest`](./httprequest.md) | -             | The request. |

## `public function parseParams(string $path): array`

Parses path params using the given path and returns them in the form of an associative array.

| Parameter name | Type     | Default value | Description |
|----------------|----------|---------------|-------------|
| `$path`        | `string` | -             | The path.   |

## `public static function getRouteParamMap(array $pathSegments): array`

Creates an associative (segment index => param name) array containing all parameters in the defined route path.

| Parameter name  | Type    | Default value | Description                  |
|-----------------|---------|---------------|------------------------------|
| `$pathSegments` | `array` | -             | An array with path segments. |
