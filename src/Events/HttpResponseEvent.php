<?php

namespace Socarrat\Core\Events;
use Socarrat\Core\Event;
use Socarrat\Core\HttpRequest;
use Socarrat\Core\HttpResponse;

class HttpResponseEvent extends Event {
	static protected array $listeners;
}

HttpResponseEvent::on(9999, function(HttpRequest $req, HttpResponse $res) {
	$res->send();
});
