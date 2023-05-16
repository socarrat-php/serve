<?php

namespace Socarrat\Serve;

class HttpRequest {
	public readonly RouteHandler $route;
	public readonly URI $uri;
	public readonly array $params;
	public readonly array $headers;
	public readonly string $method;
	public readonly string $contentType;

	public function __construct(RouteHandler $route, URI $uri, string $method, string $contentType, $headers = array(), $params = array()) {
		$this->route = $route;
		$this->uri = $uri;
		$this->params = $params;
		$this->method = $method;
		$this->contentType = $contentType;

		$headers = array();
		foreach ($_SERVER as $key => $value) {
			// https://www.php.net/manual/en/function.getallheaders.php#84262
			if (substr($key, 0, 5) == 'HTTP_') {
				$name = str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($key, 5))));
				$headers[$name] = $value;
			}
		}
		$this->headers = $headers;
	}

	public function isAjaxRequest(): bool {
		return strcasecmp($this->headers["x-requested-with"], "XMLHttpRequest") === 0;
	}

	public function body() {
		// @todo ??
		if ($this->method == 'GET') {
			return '';
		}

		$body = [];
		foreach ($_POST as $key => $value) {
			$body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
		}

		return $body;
	}

	public function json() {
		if ($this->method == 'GET') {
			return [];
		}

		if (strcasecmp($this->contentType, 'application/json') !== 0) {
			return [];
		}

		// Receive the RAW post data.
		$content = trim(file_get_contents("php://input"));
		$decoded = json_decode($content);

		return $decoded;
	}
}
