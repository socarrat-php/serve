<?php
/**
 * AppFinishedEvent.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core\Events;
use Socarrat\Core\Event;

class AppFinishedEvent extends Event {
	static protected array $listeners;
}
