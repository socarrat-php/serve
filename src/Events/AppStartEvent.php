<?php

namespace Socarrat\Events\Events;
use Socarrat\Core\App;
use Socarrat\Events\Event;

/** This event is dispatched when App::run is called. */
class AppStartEvent extends Event {
	static protected array $listeners;
}
