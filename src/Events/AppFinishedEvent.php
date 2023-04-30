<?php
/**
 * AppFinishedEvent.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core\Events;
use Socarrat\Core\App;
use Socarrat\Core\Event;

class AppFinishedEvent extends Event {
	static protected array $listeners;
}

/** Send 404 if nothing has been rendered after execution. */
AppFinishedEvent::on(0, function(App $app) {
	if (!$app->router->responseSent()) {
		http_response_code(404);
		header("Content-Type: text/plain");
	}
});
