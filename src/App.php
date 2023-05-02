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
		HttpResponseEvent::on(100, function(HttpRequest $req, HttpResponse $res) {
			// Set server timing header.
			$this->hrtStop = hrtime(true);
			$res->setHeader("Server-Timing", "exec;dur=".$this->getExecTime());
		});

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
