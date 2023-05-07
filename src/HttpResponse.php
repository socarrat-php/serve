<?php

namespace Socarrat\Serve;

class HttpResponse {
	private bool $sent = false;
	protected int $statusCode = 200;
	protected array $headers = array();
	protected bool $bodySet = false;
	protected $body;

	public function setStatusCode(int $statusCode) {
		if (($statusCode < 100) or ($statusCode > 999)) {
			// @todo: warn
			$statusCode = 200;
		}
		$this->statusCode = $statusCode;
		return $this;
	}

	public function setHeader(string $name, string $value) {
		$this->headers[$name] = $value;
		return $this;
	}

	public function text(string $text = "") {
		$this->body = $text;
		$this->bodySet = true;
		$this->setHeader("Content-Type", "text/plain");
		return $this;
	}

	public function json(mixed $data = []) {
		$this->body = json_encode($data);
		$this->bodySet = true;
		$this->setHeader("Content-Type", "application/json");
		return $this;
	}

	public function html(string $html = "") {
		$this->body = $html;
		$this->bodySet = true;
		$this->setHeader("Content-Type", "text/html");
		return $this;
	}

	// public function sse(): HttpSseServer {
	// 	$this->bodySet = true;
	// 	return (new HttpSseServer())->start();
	// }

	/**
	 * Sends the response.
	 * **Warning:** do not call this in your route definitions!
	 */
	public function send() {
		http_response_code($this->statusCode);

		foreach ($this->headers as $name => $value) {
			header("$name: $value");
		}

		if (isset($this->body)) {
			echo $this->body;
		}

		$this->sent = true;
	}
}
