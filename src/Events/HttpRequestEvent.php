<?php
/**
 * HttpRequestEvent.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core\Events;
use Socarrat\Core\Event;

class HttpRequestEvent extends Event {
	static protected array $listeners;
}
