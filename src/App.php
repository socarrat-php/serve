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
use Socarrat\Core\Log\Logger;

/** The application. */
class App {
	/** Application router. */
	public Router $router;

	/** Application logger. */
	public Logger $logger;

	function __construct() {
		$this->router = new Router();
	}

	public function registerPlugin() {

	}

	/** Run the app. */
	public function run() {
		AppStartEvent::dispatch($this);
		AppFinishedEvent::dispatch($this);
	}
}
