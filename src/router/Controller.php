<?php
namespace net\peacefulcraft\apirouter\router;

interface Controller {
    /**
     * Perform the logic, operation, query, etc which this route is designed to execute.
     * handle() will be called when a route which this controller is registered to handle
     * is matched by the router. Routes are reigstered with Router::registerRoute().
     * A single controller can be registered to several different routes.
     */
    public function handle(array $config, Request $request, Response $response): void;
}

?>
