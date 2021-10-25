<?php namespace net\peacefulcraft\apirouter\spec\render;

use net\peacefulcraft\apirouter\spec\route\IResponse;

interface RenderEngine {

	/**
	 * When invoked, this method should return plaintext output that should be sent back to the user. The
	 * provided $Response object can be used to program any needed HTTP headers. It is NOT necessary to
	 * call $Response->setResponseBody();
	 * 
	 * @param Response $Response The HTTP Response object that is handling the Application response to the user.
	 * 
	 * @return string Plaintext content that should be sent to client. (HTML, XML, JSON, binary, etc)
	 * 
	 * @throws RenderingEngineException If an error occurs while attempting to render the content.
	 */
	public function render(IResponse $Response): string;
}

?>