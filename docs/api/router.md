# `class Socarrat\Serve\Router`

The application router that handles incoming HTTP request by looking up the registered route that is requested.

## `public function on(string $route, array $methods, callable $callback)`

Registers a route.

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$route`       | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `array`    | -             | An array of allowed HTTP methods, e.g. `["GET", "DELETE"]                                          |
| `$callback`    | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function get($route, $callback)`

Shorthand method to register a GET route. Equivalent to `Router::on($route, array("GET"), $callback)`.

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$route`       | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function post($route, $callback)`

Shorthand method to register a POST route. Equivalent to `Router::on($route, array("POST"), $callback)`.

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$route`       | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function put($route, $callback)`

Shorthand method to register a PUT route. Equivalent to `Router::on($route, array("PUT"), $callback)`.

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$route`       | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function patch($route, $callback)`

Shorthand method to register a PATCH route. Equivalent to `Router::on($route, array("PATCH"), $callback)`.

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$route`       | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function delete($route, $callback)`

Shorthand method to register a DELETE route. Equivalent to `Router::on($route, array("DELETE"), $callback)`.

| Parameter name | Type       | Default value | Description                                                                                        |
|----------------|------------|---------------|----------------------------------------------------------------------------------------------------|
| `$route`       | `string`   | -             | The route name to register, possibly with one or more route parameters.                            |
| `$methods`     | `callable` | -             | The callback that is called when the route is requested. It receives the HttpRequest as parameter. |

## `public function find(URI $uri): ?RouteHandler`

Finds a route using the given URI. Returns the route callback if the route has been found.

| Parameter name | Type                             | Default value | Description                            |
|----------------|----------------------------------|---------------|----------------------------------------|
| `$uri`         | [`URI`](#class-socarratserveuri) | -             | The request URI to find the route for. |

## `public function handleRequest(): ?HttpResponder`

Handles the current request.

## `public function responseSent(): bool`

Returns true if a response has been rendered, false otherwise.

## `public static function splitPath(string $path): array`

Splits a path using slashes. If the path contains a querystring and/or a fragment, they'll be ignored. URL-encoded values will be decoded.

Returns an array with path segments.

Examples:

* `"/admin/blogs/new"` => `array("/admin", "/blogs", "/new")`
* `"/about-us/"` => `array("/about-us", "/")`
* `"/greet/Alice?query=ignored#fragment"` => `array("/greet", "/Alice")`
* `"/greet/Alice?query=ignored#fragment"` => `array("/greet", "/Alice")`
* `"/"` => `array()`
