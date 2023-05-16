<?php

declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Include the dependencies we need.
 * In a bare-bones app, you will probably always need those three.
 * Socarrat\Serve\App is the 'glue' that brings all parts of the Socarrat serve library together.
 * The other dependency's names speak for themselves.
 */
use Socarrat\Serve\App;
use Socarrat\Serve\HttpRequest;
use Socarrat\Serve\HttpResponder;

/** Create an App instance. */
$app = new App();

/**
 * Register a route with the app router.
 *
 * This route only listens to GET requests. To change this, you could change `router->get` to use another method.
 */
$app->router->get("/greet/:name", function(HttpRequest $req, HttpResponder $res) {
	/**
	 * Set the status code to teapot.
	 * See: https://en.wikipedia.org/wiki/Hyper_Text_Coffee_Pot_Control_Protocol
	 */
	$res->status(418);

	/**
	 * Send some JSON.
	 * `$req->params["name"]` gets the `"name"` parameter from the request's param array.
	 */
	$res->json([
		"hello" => $req->params["name"],
		"headers" => $req->headers,
	]);

	/**
	 * We could write this whole function body as a one-liner:
	 *
	 *   $res->status(418)->json([ "hello" => $req->params["name"], "headers" => $req->headers ]);
	 *
	 * The method that writes the response is always the last in the chain.
	 */
});

/**
 * This route listens to POST request. It just sends the request body back to the client.
 */
$app->router->post("/echo", function (HttpRequest $req, HttpResponder $res) {
	$res->file("php://input");
});

/**
 * Run the app!
 */
$app->run();
