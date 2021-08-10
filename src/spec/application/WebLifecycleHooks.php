<?php namespace net\peacefulcraft\apirouter\spec\application;

interface WebLifecycleHooks {

	/**
	 * The Application has been booted, but not yet been asked to resolve
	 * a URI. All plugins have been setup and framework resources
	 * created.
	 */
	CONST BEFORE_REQUEST_ROUTE = 'before_request_route';

	/**
	 * The Application and it's plugins have been booted.
	 * The Application has resolved a URI and parsed body/uri parameters.
	 * The Application will execute any defined middleware after this hook-set.
	 */
	CONST BEFORE_MIDDLEWARE_EXEC = 'before_middleware_exec';

	/**
	 * The Application and it's plugins have been booted.
	 * The Application has resolved a URI and parsed body/uri parameters.
	 * The Application has finished middleware execution and the request
	 * and no middleware indicated the request should be terminated.
	 * The Application will execute the matched controller after this hook-set.
	 */
	CONST BEFORE_CONTROLLER_EXEC = 'before_controller_exec';

	/**
	 * The Application and it's plugins have been booted.
	 * The Application has attempted to resolve a URI and parse body/uri parmaters,
	 * however it may have failed. The Application may have matched a controller,
	 * but been told to stop execution my a piece of middleware.
	 * A Response object exists and is configured for output, but has not yet
	 * been printed to output buffers, UNLESS Response output has been disabled.
	 * If Response output was disabled, then the server may have already written
	 * content back to the user. This state will be available on the Response object.
	 */
	CONST BEFORE_RESPONSE_FLUSH = 'before_response_print';

	/**
	 * The Application and it's plugins have been booted.
	 * The Application has completed controller matching and handled
	 * output appropriatly (including 404, middleware terminate, or good controller match),
	 * and the Response object has been printed and ob_flushed() if output buffering was in use.
	 * 
	 * This is a good place to register things like binary file write-outs, while still being able to use
	 * built-in Response object header handling. If the file is small, then a hook is not needed, but for
	 * larger files you may run into memory exhaustion issues if trying to buffer the whole file
	 * with the framework Response object.
	 */
	CONST AFTER_RESPONSE_FLUSH = 'after_response_print';

	/**
	 * The Application has completed request handling. 
	 * No output should be sent to the client at this point.
	 * This hook is called right before shuting down plugins
	 * and releasing framework resources.
	 */
	CONST BEFORE_TEARDOWN = 'before_teardown';
}

?>