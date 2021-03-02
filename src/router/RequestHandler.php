<?php
namespace ncsa\phpmvj\router;

interface RequestHandler {
    /**
     * Responds to HTTP/OPTIONS (CORS) requests by setting the Access-Control-*
     * headers appriopriatly. See \ncsa\phpmvj\util for traits for common header
     * configurations.
     * For more information on CORS and HTTP/OPTIONS, see
     * https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
     */
    public function options(array $config, Request $request, Response $response): void;

    /**
     * Perform the logic, operation, query, etc which this route is designed to execute.
     * handle() will be called when a route which this controller is registered to handle
     * is matched by the router. Routes are reigstered with Router::registerRoute().
     * A single controller can be registered to several different routes.
     */
    public function handle(array $config, Request $request, Response $response): void;
}

?>
