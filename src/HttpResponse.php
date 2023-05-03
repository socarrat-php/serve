<?php

namespace Socarrat\Core;

/** Represents an HTTP response. */
class HttpResponse {

	/** True if a response has been sent, false otherwise. */
	private bool $sent = false;

	/** The status code. */
	protected int $statusCode = 200;

	/** Response headers */
	protected array $headers = array();

	/** Is the response body set? */
	protected bool $bodySet = false;

	/** Response body */
	protected $body;

	/**
	 * Sets the status code for the response.
	 *
	 * @param int $statusCode The status code
	 */
	public function setStatusCode(int $statusCode) {
		if (($statusCode < 100) or ($statusCode > 999)) {
			// @todo: warn
			$statusCode = 200;
		}
		$this->statusCode = $statusCode;
		return $this;
	}

	/**
	 * Sets a header key/value pair.
	 *
	 * @param string $name Header name
	 * @param string $value Header value
	 */
	public function setHeader(string $name, string $value) {
		$this->headers[$name] = $value;
		return $this;
	}

	/**
	 * Sends the given string to the client and sets the content type to `text/plain`.
	 *
	 * @param string $text The string to render
	 */
	public function text(string $text = "") {
		$this->body = $text;
		$this->bodySet = true;
		$this->setHeader("Content-Type", "text/plain");
		return $this;
	}

	/**
	 * Sends the given JSON to the client and sets the content type to `application/json`.
	 *
	 * @param mixed $json The data to render as JSON
	 */
	public function json(mixed $data = []) {
		$this->body = json_encode($data);
		$this->bodySet = true;
		$this->setHeader("Content-Type", "application/json");
		return $this;
	}

	/**
	 * Sends the given HTML to the client and sets the content type to `text/html`.
	 *
	 * @param string $html The HTML to render
	 */
	public function html(string $html = "") {
		$this->body = $html;
		$this->bodySet = true;
		$this->setHeader("Content-Type", "text/html");
		return $this;
	}

	/** Starts an SSE connection. */
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
