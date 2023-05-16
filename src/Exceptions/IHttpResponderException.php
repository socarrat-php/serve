<?php

namespace Socarrat\Serve\Exceptions;
use Socarrat\Serve\HttpRequest;
use Socarrat\Serve\HttpResponder;

interface IHttpResponderException {
	function getResponse(HttpRequest $req): HttpResponder;
}
