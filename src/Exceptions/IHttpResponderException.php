<?php
/**
 * IHttpResponderException.php
 *
 * @author Romein van Buren
 * @license MIT
 */

namespace Socarrat\Core\Exceptions;
use Socarrat\Core\HttpRequest;
use Socarrat\Core\HttpResponse;

interface IHttpResponderException {
	function getResponse(HttpRequest $req): HttpResponse;
}
