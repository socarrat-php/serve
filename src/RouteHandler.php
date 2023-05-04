<?php

namespace Socarrat\Serve;
use Socarrat\Serve\Exceptions\IHttpResponderException;

class RouteHandler {
	protected readonly string $path;
	protected readonly array $pathSegments;
	protected readonly array $paramMap;
	protected readonly array $methods;
	protected readonly mixed $callback;

	public function __construct(string $path, array $methods, callable $callback) {
		$this->path = $path;
		$this->pathSegments = Router::splitPath($this->path);
		$this->paramMap = RouteHandler::getRouteParamMap($this->pathSegments);
		$this->methods = $methods;
		$this->callback = $callback;
	}

	/** Returns the route handler path. */
	public function getPath() {
		return $this->path;
	}

	/** Returns the route handler path segments. */
	public function getPathSegments() {
		return $this->pathSegments;
	}

	/** Returns the array of methods the route handler accepts. */
	public function getMethods() {
		return $this->methods;
	}

	/** Returns true if this handler can handle the given method, false otherwise. */
	public function canHandleMethod(string $method) {
		foreach ($this->methods as $m) {
			if (strcasecmp($method, $m) == 0) {
				return true;
			}
		}
		return false;
	}

	/** Executes the route handler. */
	public function execute(HttpRequest $req): ?HttpResponse {
		if (!is_callable($this->callback)) {
			return null;
		}
		if (!$this->canHandleMethod($req->method)) {
			return null;
		}

		try {
			$res = ($this->callback)($req);
			return $res;
		}
		catch (\Exception $e) {
			if (in_array("IHttpResponderException", class_implements($e))) {
				$res = $e->getResponse($req);
				return $res;
			}
			// @todo: error
			return (new HttpResponse())->setStatusCode(500)->json([ "ok" => false ]);
		}
	}

	/** Parses path params using the given path and returns them in the form of an associative array. */
	public function parseParams(string $path): array {
		$pathSegments = Router::splitPath($path);
		$parsedParams = array();
		foreach ($this->paramMap as $idx => $name) {
			$parsedParams[$name] = substr($pathSegments[$idx], 1);
		}
		return $parsedParams;
	}

	/**
	 * Creates an associative array containing all parameters in the defined route path.
	 * @return array segment index => param name
	 */
	public static function getRouteParamMap(array $pathSegments): array {
		$paramMap = array();
		foreach ($pathSegments as $idx => $segment) {
			if (str_starts_with($segment, "/:")) {
				// This segment is a parameter!
				$paramMap[$idx] = substr($segment, 2);
			}
			else if (str_starts_with($segment, ":")) {
				// This segment is a parameter!
				$paramMap[$idx] = substr($segment, 1);
			}
		}
		return $paramMap;
	}
}
