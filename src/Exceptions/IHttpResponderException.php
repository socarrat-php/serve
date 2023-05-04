<?php

namespace Socarrat\Serve\Exceptions;
use Socarrat\Serve\HttpRequest;
use Socarrat\Serve\HttpResponse;

interface IHttpResponderException {
	function getResponse(HttpRequest $req): HttpResponse;
}
