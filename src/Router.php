<?php

namespace Socarrat\Serve;
use Socarrat\Serve\Events\HttpRequestEvent;
use Socarrat\Serve\Events\HttpResponseEvent;

class Router {
	protected $handlers;
	protected bool $responseRendered;

	function __construct() {
		$this->handlers = array();
		$this->responseRendered = false;

		HttpResponseEvent::on(0, function() {
			$this->responseRendered = true;
		});
	}

	public function on(string $route, array $methods, callable $callback) {
		$segments = Router::splitPath($route);
		$branch =& $this->handlers;

		foreach ($segments as $segment) {
			$branch =& $branch[$segment];
		}

		$branch[""] = new RouteHandler($route, $methods, $callback);
	}

	public function get($route, $callback) {
		return $this->on($route, array("GET"), $callback);
	}

	public function post($route, $callback) {
		return $this->on($route, array("POST"), $callback);
	}

	public function put($route, $callback) {
		return $this->on($route, array("PUT"), $callback);
	}

	public function patch($route, $callback) {
		return $this->on($route, array("PATCH"), $callback);
	}

	public function delete($route, $callback) {
		return $this->on($route, array("DELETE"), $callback);
	}

	public function find(URI $uri): ?RouteHandler {
		if ($uri === null) {
			return null;
		}

		$segments = Router::splitPath($uri->path);
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
		$uri = URI::fromRequest();
		$handler = $this->find($uri);

		if (isset($handler)) {
			$req = new HttpRequest(
				/* Handler */ $handler,
				/* URI     */ $uri,
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

	public function responseSent(): bool {
		return $this->responseRendered;
	}

	public static function splitPath(string $path): array {
		if ($path == "/") {
			// Shortcut
			return array("/");
		}

		$pathLen = strlen($path);

		// Split the path based on slashes.
		// Parse path using parse_url to be sure that we only have the URL path
		$split = str_split(parse_url($path, PHP_URL_PATH));

		$path = array();
		$slashIndexes = array_keys($split, "/");

		foreach ($slashIndexes as $i => $idx) {
			$nextIdx = $slashIndexes[$i + 1] ?? $pathLen;
			$size = $nextIdx - $idx;
			array_push($path, join(array_slice($split, $idx, $size)));
		}

		// Decode each part
		foreach ($path as $i => $segment) {
			$path[$i] = urldecode($segment);
		}

		return $path;
	}
}
