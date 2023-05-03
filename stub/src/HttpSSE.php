<?php

namespace Socarrat\Core;

/**
 * Server-Sent Events server.
 *
 * @todo Work in Progress!
 *
 * If you use this in a loop, be sure to break check if the connection is still open:
 * ```php
 * if (connection_aborted()) break;
 * ```
 */
class HttpSseServer {
	function __construct() {}

	function start(): HttpSseServer {
		header("Content-Type: text/event-stream");
		return $this;
	}

	function sendText(string $text, string $eventName = ""): HttpSseServer {
		if (!isset($text) or ($text == "")) {
			return $this;
		}
		if (!isset($eventName) or ($eventName == "")) {
			if (str_contains($eventName, "\n")) {
				// @todo: error. $eventName cannot contain newline
				return $this;
			}
			echo "event: ".$eventName."\n";
		}
		echo "data: ".str_replace("\n", "\ndata: ", $text)."\n\n";

		while (ob_get_level() > 0) {
			ob_end_flush();
		}
		flush();

		return $this;
	}
}
