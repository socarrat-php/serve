<?php
/**
 * AppStartEvent.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core\Events;
use Socarrat\Core\App;
use Socarrat\Core\Event;

/** This event is dispatched when App::run is called. */
class AppStartEvent extends Event {
	static protected array $listeners;
}
