<?php
namespace ncsa\phpmcj\util;

use RuntimeException;

class LDAP {

	private $_dc;
	private $_ldap_resource;

	/**
	 * @param ldapServers An array of hostnames or ip addresses. Servers are pinged sequentially.
	 * 										The first server to respond sucesfully to a ping (fsockopen host:389) is used for queries.
	 */
	public function __construct(array $ldapServers) {
		if (!$this->findOnlineDC($ldapServers)) {
			throw new RuntimeException('Unable to contact domain controller.');
		}
	}
	/**
	 * Loop through the DC list and return the first one that responds to a ping.
	 * @return {Boolean} True if at least one DC was contacted
	 *                   False if no DCs responded to pings
	 */
	private function findOnlineDC(array $ldapServers):bool {
		foreach($ldapServers as $dc) {
			if ($this->_pingDC(($dc))) {
				$this->_dc = $dc;
				return true;
			}
		}

		return false;
	}

		/**
		 * Ping a domain controller to see if it is online.
		 * @param {String} $host The hostname of a server to ping
		 * @return {Boolean} True if server responds
		 *                   False if server does not respond
		 */
		private function _pingDC(string $host):bool {
			$socket = fsockopen($host, 389, $errno, $error, 3);
			if (!$socket) { return false; }

			fclose($socket);
			return true;
		}

	/**
	 * Authenticate with LDAP server using the provided identification and password
	 * @param {String} $dn Distinguished name (username)
	 * @param {String} $password Password
	 * @return {Boolean} True if binding was succesful
	 *                   False if bind failed
	 */
	public function connect(string $dn, string $password):bool {
		$this->_ldap_resource = ldap_connect("ldap://" . $this->_dc, 636);
		ldap_set_option($this->_ldap_resource, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($this->_ldap_resource, LDAP_OPT_REFERRALS, 0);
		if (!$this->_ldap_resource) { return false; }

		if (ldap_bind($this->_ldap_resource, $dn, $password)) {
			return true;
		}
		return false;
	}

	public function getAccountInfo($user):?array {
		if (!$this->_ldap_resource) { throw new RuntimeException('Illegal state - ldap resource is not bound'); }

		$dn = "dc=ncsa,dc=tech";
		$filter = "(mail=" . $user . ")";
		$results = ldap_search($this->_ldap_resource, $dn, $filter, ['givenName', 'sn']);
		$results = ldap_get_entries($this->_ldap_resource, $results);

		if (count($results) < 1) {
			return null;
		} 

		$results = [
			'first_name' => $results[0]['givenname'][0], 'last_name',
			'last_name' => $results[0]['sn'][0],
		];
		return $results;
	}
}