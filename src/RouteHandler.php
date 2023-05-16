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

	public function getPath() {
		return $this->path;
	}

	public function getPathSegments() {
		return $this->pathSegments;
	}

	public function getMethods() {
		return $this->methods;
	}

	public function canHandleMethod(string $method) {
		foreach ($this->methods as $m) {
			if (strcasecmp($method, $m) == 0) {
				return true;
			}
		}
		return false;
	}

	public function execute(HttpRequest $req, HttpResponder $res): bool {
		if (!is_callable($this->callback)) {
			return false;
		}
		if (!$this->canHandleMethod($req->method)) {
			return false;
		}

		try {
			($this->callback)($req, $res);
			return true;
		}
		catch (\Exception $e) {
			if (in_array("IHttpResponderException", class_implements($e))) {
				$res = $e->getResponse($req);
				return $res;
			}
			// @todo: error
			return $res->setStatusCode(500)->json([ "ok" => false ]);
		}
	}

	public function parseParams(string $path): array {
		$pathSegments = Router::splitPath($path);
		$parsedParams = array();
		foreach ($this->paramMap as $idx => $name) {
			$parsedParams[$name] = substr($pathSegments[$idx], 1);
		}
		return $parsedParams;
	}

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
