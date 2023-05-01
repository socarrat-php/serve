<?php
/**
 * HttpRequest.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core;

class HttpRequest {
	public readonly RouteHandler $route;
	public readonly array $params;
	public readonly string $method;
	public readonly string $contentType;

	public function __construct(RouteHandler $route, string $method, string $contentType, $params = array()) {
		$this->route = $route;
		$this->params = $params;
		$this->method = $method;
		$this->contentType = $contentType;
	}

	public function body() {
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
