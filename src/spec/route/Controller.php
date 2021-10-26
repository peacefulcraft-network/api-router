<?php namespace net\peacefulcraft\apirouter\spec\route;

interface Controller {
    /**
     * Perform the logic, operation, query, etc which this route is designed to execute.
     * handle() will be called when a route which this controller is registered to handle
     * is matched by the router. Routes are reigstered with Router::registerRoute().
     * A single controller can be registered to several different routes.
		 * 
		 * @param array $config Application Configuration
		 * @param IRequest $Request Object containing Request parameters as interpreted by the framework
		 *                         and transformed by Middleware.
		 * 
		 * @throws 
     */
    public function handle(array $config, IRequest $Request): IResponse;
}

?>
