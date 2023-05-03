<?php
/**
 * App.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core;
use Socarrat\Core\Events\AppFinishedEvent;
use Socarrat\Core\Events\AppStartEvent;
use Socarrat\Core\Events\HttpResponseEvent;
use Socarrat\Core\Log\Logger;

/** The application. */
class App {
	/** Application router. */
	public Router $router;

	/** Application logger. */
	public Logger $logger;

	/**
	 * Whether to send the Server-Timing header for testing purposes.
	 *
	 * If true, a Server-Timing header will be sent with each request, e.g. `Server-Timing: exec;dur=2.775166`.
	 */
	public bool $serverTiming = true;

	/**
	 * The value of the X-Powered-By header.
	 *
	 * If you set this to `""`, no custom X-Powered-By will be sent: instead something like `PHP/8.2.0`.
	 */
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

	/**
	 * Run the app.
	 *
	 * Just before an HTTP response has been sent, {@link App::$hrtStop} is set to calculate the execution time using {@link App::$hrtStart}.
	 */
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

	/**
	 * Returns the time in milliseconds that the application took to send a response.
	 *
	 * This value is calculated by subtracting {@link App::$hrtStop} from {@link App::$hrtStart}.
	 *
	 * @return float Execution time in milliseconds, or -1 if the execution has not yet terminated.
	 */
	public function getExecTime(): float {
		if (!isset($this->hrtStop)) {
			return -1;
		}
		return ($this->hrtStop - $this->hrtStart) / 1_000_000;
	}
}
