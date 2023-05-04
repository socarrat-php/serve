<?php

namespace Socarrat\Serve\Events;
use Socarrat\Serve\App;
use Socarrat\Events\Event;

/** This event is dispatched when App::run is called. */
class AppStartEvent extends Event {
	static protected array $listeners;
}
