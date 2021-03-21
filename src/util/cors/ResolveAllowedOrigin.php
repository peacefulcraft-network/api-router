<?php
namespace ncsa\phpmcj\util\cors;

use ncsa\phpmcj\exceptions\InvalidConfigurationException;

trait ResolveAllowedOrigin {
	/**
	* @param allowed_origins Allowed origin is expected to be a string containing '*' for all origins,
	* or a list of allowed-origin objects in the following form
	*	[
	*		'protocol': 'http', 'https', '*'...
	*		'origin': 'ip', 'fqdn', '*'...
	*		'port': '*', 'port'...
	*	].
	* @param request_origin The origin which the client is issueing the current request from.
	* @return string Request is authorized from the returned origin doamin. Return this value to
	* the client if this is in response to an HTTP/OPTIONS request. If the request is authorized,
	* the returned value will somehow match the request origin (*, or the request origin). If the
	* request origin is not authorized, a value from the authorized origins list will be returned
	* which result in a CORS error for the client.
	*/
	private function _resolveAllowedOrigin(string|array $allowed_origins, string $request_origin): string {
		if (is_string($allowed_origins) && $allowed_origins === '*') {
			return $allowed_origins;
		}

		/**
		 * Takes a request url in the form protocol://origin:port/any/path, where :port is optional.
		 * $matches[0] = Matched strings (protocl, origin, port, path) without :// or : between. (useless here)
		 * $matches[1] = Matched protocl without trailing :// (IE: http or https)
		 * $matches[2] = Request origin (IE ncsa.tech or www.ncsa.tech)
		 * $matches[3] = Port (if there was one) (IE 80, 443)
		 * Any capture groups which failed to match (IE if there is no port the port group fails to match), "" is returned at $matches[n]
		 */
		$matches = [];
		$request_origin = strtolower($request_origin);
		preg_match('/^(.*):\/\/([A-Za-z0-9\-\.]+):?([0-9]{0,5})?(.*)$/', $request_origin, $matches);

		$req_protcol = $matches[1];
		$req_origin = $matches[2];
		$req_port = $matches[3];

		foreach($allowed_origins as $alwd_origin) {
			/***************************************************************
			 * Protocol matching 
			 ***************************************************************/
			if (
				// If allowed protocl is a string, check for wild card, or exact match
				(
					is_string($alwd_origin['protocol'])
					&& ($alwd_origin['protocol'] === '*' || $req_protcol === strtolower($alwd_origin['protocol']))
				)
				||
				// If allowed_protocol is an aray, check for request protocl in allowed protcol list
				(
					is_array($alwd_origin['protocol']) && in_array($req_protcol, $alwd_origin['protocol'])
				)
			) {

				/***************************************************************
				 * Origin matching (FQDN or IP) 
			 	 ***************************************************************/
				if (
					// If allowed origin is a string, check for wild card, or exact match
					(
						is_string($alwd_origin['origin'])
						&& ($alwd_origin['origin'] === '*' || $req_origin === strtolower($alwd_origin['origin']))
					)
					||
					// If allowed origin is an aray, check for request origin in allowed origin list
					(
						is_array($alwd_origin['origin']) && in_array($req_origin, $alwd_origin['origin'])
					)
				) {

				 /***************************************************************
				  * Port matching
				  * This conditional is flipped because alwd_origin[port] can be a string or an int
				  * and it is ok and easier to let PHP type cast internally when comparing it
			 	  ***************************************************************/
					if (
						// If allowed port is an aray, check for request port in allowed port list
						(
							is_array($alwd_origin['port']) && in_array($req_port, $alwd_origin['port'])
						)
						||
						// If allowed port is not an array (string or int), check for wild card, or exact match
						(
							$alwd_origin['port'] === '*' || $req_port === strtolower($alwd_origin['port'])
						)
						||
							// If no discernable request port, don't include a port and fallback to
							// browser which should reject if the port actually mattered.
							strlen($req_port) === 0
					) {
						/*
							Protocl, origin, and port if it was included matched one of the request origins.
							Reconsturct the full allowed-origin (protocol, origin, optinal port) to return.
						*/
						/* If there was a port, append :port, otherwise don't */
						return $req_protcol . '://' . $req_origin . ((strlen($req_port) === 0)? '' : ':' . $req_port);
					}
				}

			}
		}

		/*
			If no allowed origins were matched, return one of our valid origins to indicate the browser is
			not authorized to issue requests to this endpoint from $request_origin origin.
		*/
		return $this->_getAnAllowedOrigin($allowed_origins, $request_origin);
	}

	private function _getAnAllowedOrigin(string|array $allowed_origins, $request_origin): string {
		if (is_string($allowed_origins) && $allowed_origins === '*') {
			return '*';
		}

		$default_origin = $allowed_origins[0];
		$protocol = $default_origin['protocol'];
		$origin = $default_origin['origin'];
		$port = $default_origin['port'];

		$matches = [];
		$request_origin = strtolower($request_origin);
		preg_match('/^(.*):\/\/([A-Za-z0-9\-\.]+):?([0-9]{0,5})?(.*)$/', $request_origin, $matches);
		$req_protcol = $matches[1];
		$req_origin = $matches[2];
		$req_port = $matches[3];

		if (is_string($protocol)) {
			// If any protcol is allowed, try to match what the user requested
			if ($protocol === '*') {
				if (strlen($req_protcol) === 0) {
					// If we couldn't find a protcol, default to https
					$protocol = 'https';
				} else {
					// Otherwise, match the request protcol
					$protocol = $req_protcol;
				}
			// Else, $protocol is a string from config and we need to a
			}
		// If protocols is a list of protcol, select the first one as the default
		} else if (is_array($protocol)) {
			$protocol = $protocol[0];
		} else {
			throw new InvalidConfigurationException('config.cors.[].protocol', 'is expected to be a string or an array of strings.');
		}

		if (is_string($origin)) {
			// If any origin is allowed, try to match what the user requested
			if ($origin === '*') {
				if (strlen($req_origin) === 0) {
					// If we couldn't find an origin, return the server's default name
					$origin = $_SERVER['SERVER_NAME'];
				} else {
					// Otherwise, match the request origin
					$origin = $req_origin;
				}
			// Else, origin is a specified origin that should have been assigned to $origin above
			}
		// If origin is a list of origins, select the first one as the default
		} else if (is_array($origin)) {
			$origin = $origin[0];
		} else {
			throw new InvalidConfigurationException('config.cors.[].origin', 'is expected to be a string or an array of strings.');
		}

		if (is_string($port)) {
			// If any port is allowed, try to match what the user requested
			if ($port === '*') {
				if (strlen($req_port) === 0) {
					// If we couldn't return a port, leave it off
					$port = "";
				} else {
					// Otherwise, match the request port
					$port = $req_port;
				}
			// Else, port is a specified port that should have been assigned to $port above
			}
		// If port is a list of port, select the first one as the default
		} else if (is_array($port)) {
			$port = $port[0];
		} else {
			throw new InvalidConfigurationException('config.cors.[].port', 'is expected to be a string or an array of strings.');
		}

		return $protocol . '://' . $origin . ((strlen($port) === 0)? '' : ':' . $port);
	}
}