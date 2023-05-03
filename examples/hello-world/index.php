<?php

declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Include the dependencies we need.
 * In a bare-bones app, you will probably always need those three.
 * Socarrat\Core\App is the 'glue' that brings all parts of the Socarrat core library together.
 * The other dependency's names speak for themselves.
 */
use Socarrat\Core\App;
use Socarrat\Core\HttpRequest;
use Socarrat\Core\HttpResponse;

/** Create an App instance. */
$app = new App();

/**
 * Register a route with the app router.
 *
 * This route only listens to GET requests. To change this, you could change `router->get` to use another method.
 */
$app->router->get("/greet/:name", function(HttpRequest $req) {
	/**
	 * Create a new HTTP response
	 */
	$res = new HttpResponse();

	/**
	 * Set the status code to teapot.
	 * See: https://en.wikipedia.org/wiki/Hyper_Text_Coffee_Pot_Control_Protocol
	 */
	$res->setStatusCode(418);

	/**
	 * Send some JSON.
	 * `$req->params["name"]` gets the `"name"` parameter from the request's param array.
	 */
	$res->json([ "hello" => $req->params["name"] ]);

	/**
	 * Return the response.
	 */
	return $res;

	/**
	 * We could write this whole function body as a one-liner:
	 * `return (new HttpResponse())->setStatusCode(418)->json([ "hello" => $req->params["name"] ]);`
	 */
});

/**
 * Run the app!
 */
$app->run();
