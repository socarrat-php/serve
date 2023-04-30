<?php
/**
 * Router.php: Contains the HTTP router.
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core;
use Socarrat\Core\Events\HttpRequestEvent;
use Socarrat\Core\Events\HttpResponseEvent;

/** Router. */
class Router {
	/**
	 * Tree array of request handlers.
	 * @todo explain how this works
	 */
	protected $handlers;

	/** True if a response has been rendered, false otherwise. */
	protected bool $responseRendered;

	function __construct() {
		$this->handlers = array();
		$this->responseRendered = false;

		HttpResponseEvent::on(0, function() {
			$this->responseRendered = true;
		});
	}

	/**
	 * Registers a route.
	 */
	public function on(string $route, array $methods, callable $callback) {
		$segments = Router::splitPath($route);
		$branch =& $this->handlers;

		foreach ($segments as $segment) {
			$branch =& $branch[$segment];
		}

		$branch[""] = new RouteHandler($route, $methods, $callback);
	}

	/**
	 * Shorthand method to register a GET route.
	 *
	 * Equivalent to `Router::on($route, array("GET"), $callback)`
	 */
	public function get($route, $callback) {
		return $this->on($route, array("GET"), $callback);
	}

	/**
	 * Shorthand method to register a GET route.
	 *
	 * Equivalent to `Router::on($route, array("POST"), $callback)`
	 */
	public function post($route, $callback) {
		return $this->on($route, array("POST"), $callback);
	}

	/**
	 * Shorthand method to register a GET route.
	 *
	 * Equivalent to `Router::on($route, array("PUT"), $callback)`
	 */
	public function put($route, $callback) {
		return $this->on($route, array("PUT"), $callback);
	}

	/**
	 * Shorthand method to register a GET route.
	 *
	 * Equivalent to `Router::on($route, array("PATCH"), $callback)`
	 */
	public function patch($route, $callback) {
		return $this->on($route, array("PATCH"), $callback);
	}

	/**
	 * Shorthand method to register a GET route.
	 *
	 * Equivalent to `Router::on($route, array("DELETE"), $callback)`
	 */
	public function delete($route, $callback) {
		return $this->on($route, array("DELETE"), $callback);
	}

	/**
	 * Finds a route using the given path.
	 *
	 * @param string $route The requested path.
	 * @return ?RouteHandler The route callback if the route has been found.
	 */
	public function find(string $route): ?RouteHandler {
		$segments = Router::splitPath($route);
		$branch =& $this->handlers;

		foreach ($segments as $segment) {
			if (isset($branch[$segment])) {
				$branch =& $branch[$segment];
			}
			else if (($segment == "/") or ($segment == "/")) {
				// Could never be a match. The segment would be an empty parameter otherwise.
				return null;
			}
			else {
				$routeWithParam = "";
				foreach ($branch as $key => $twig) {
					if (!str_starts_with($key, "/:")) {
						continue;
					}
					$routeWithParam = $key;
					break;
				}

				if ($routeWithParam != "") {
					$branch =& $branch[$routeWithParam];
				}
			}
		}

		return $branch[""] ?? null;
	}

	public function handleRequest(): ?HttpResponse {
		$handler = $this->find($_SERVER["REQUEST_URI"]);

		if (isset($handler)) {
			$req = new HttpRequest(
				/* Handler */ $handler,
				/* Method  */ trim($_SERVER["REQUEST_METHOD"]),
				/* Mime    */ !empty($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : "",
				/* Params  */ $handler->parseParams($_SERVER["REQUEST_URI"])
			);

			HttpRequestEvent::dispatch($req);
			$res = $handler->execute($req);

			if (isset($res)) {
				HttpResponseEvent::dispatch($req, $res);
			}

			return $res;
		}

		return null;
	}

	/** Returns true if a response has been rendered, false otherwise. */
	public function responseSent(): bool {
		return $this->responseRendered;
	}

	/**
	 * Splits a path using slashes.
	 *
	 * Examples:
	 * * `"/admin/blogs/new"` becomes `array("/admin", "/blogs", "/new")`
	 * * `"/about-us/"` becomes `array("/about-us", "/")`
	 * * `"/"` becomes `array()`
	 */
	public static function splitPath(string $route): array {
		if ($route == "/") {
			return array();
		}

		$split = str_split($route);
		$path = array();
		$slashIndexes = array_keys($split, "/");

		foreach ($slashIndexes as $i => $idx) {
			$nextIdx = $slashIndexes[$i + 1] ?? strlen($route);
			$size = $nextIdx - $idx;
			array_push($path, join(array_slice($split, $idx, $size)));
		}

		return $path;
	}
}
