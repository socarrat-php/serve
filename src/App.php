<?php

namespace Socarrat\Serve;
use Socarrat\Serve\Events\AppFinishedEvent;
use Socarrat\Serve\Events\AppStartEvent;
use Socarrat\Serve\Events\HttpResponseEvent;

class App {
	public Router $router;
	public bool $serverTiming = true;
	public string $poweredBy = "Socarrat";

	/**
	 * High resolution time as reported when the application execution started.
	 * @link https://www.kernel.org/doc/html/latest/timers/hrtimers.html Linux hrtimers
	 */
	private float $hrtStart;

	/**
	 * High resolution time as reported when an HTTP response has been sent.
	 * @link https://www.kernel.org/doc/html/latest/timers/hrtimers.html Linux hrtimers
	 */
	private float $hrtStop;

	function __construct() {
		$this->hrtStart = hrtime(true);
		$this->router = new Router();
	}

	public function run() {
		AppStartEvent::on(0, function(App $app) {
			// Serve the client!
			$app->router->handleRequest();
		});

		HttpResponseEvent::on(9990, function(HttpRequest $req, HttpResponse $res) {
			$this->hrtStop = hrtime(true);

			// Set server timing header.
			if ($this->serverTiming) {
				$res->setHeader("Server-Timing", "exec;dur=".$this->getExecTime());
			}

			// Set X-Powered-By header
			if (($this->poweredBy ?? "") != "") {
				$res->setHeader("X-Powered-By", $this->poweredBy);
			}
		});

		AppFinishedEvent::on(9990, function() {
			if (!$this->router->responseSent()) {
				// Send 404 if nothing rendered.
				http_response_code(404);
				header("Content-Type: text/plain");

				// Set X-Powered-By header
				if (($this->poweredBy ?? "") != "") {
					header("X-Powered-By: $this->poweredBy");
				}
			}
		});

		// Ready...
		AppStartEvent::dispatch($this);
		AppFinishedEvent::dispatch($this);
	}

	public function getExecTime(): float {
		if (!isset($this->hrtStop)) {
			return -1;
		}
		return ($this->hrtStop - $this->hrtStart) / 1_000_000;
	}
}
