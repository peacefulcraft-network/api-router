<?php

use ncsa\phpmvj\util\cors\ResolveAllowedOrigin;
use PHPUnit\Framework\TestCase;

class ResolveAllowedOriginTest extends TestCase {
	use ResolveAllowedOrigin;

	public function testAllowedOriginStar() {
		// Try wildcard all with no port specified
		$result = $this->_resolveAllowedOrigin('*', 'http://ncsa.tech');
		$this->assertEquals('*', $result);

		// Try wildcard with port specified
		$result = $this->_resolveAllowedOrigin('*', 'http://ncsa.tech:456');
		$this->assertEquals('*', $result);

		// Try wildcard with full uri
		$result = $this->_resolveAllowedOrigin('*', 'http://ncsa.tech:456/530ipjget/af4209?test=notgonnabreak');
		$this->assertEquals('*', $result);

		// Try wildcard all with ip origin, no request port
		$result = $this->_resolveAllowedOrigin('*', 'http://192.168.1.1');
		$this->assertEquals('*', $result);

		// Try wildcard all with ip origin, no request port
		$result = $this->_resolveAllowedOrigin('*', 'http://192.168.1.1:1489');
		$this->assertEquals('*', $result);

		// Try wildcard all with ip origin, no request port
		$result = $this->_resolveAllowedOrigin('*', 'http://192.168.1.1:1489/635490et?test=canitdoit');
		$this->assertEquals('*', $result);
	}

	public function testAllowedSingleFixedFqdnOriginWithWildcardProtocol() {
		$allowed_origns = [
			[
				'protocol'=> '*',
				'origin'=> 'ncsa.tech',
				'port'=> '80'
			]
		];

		// Try wildcard protocol with specific request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:80');
		$this->assertEquals('http://ncsa.tech:80', $result);

		// Try wildcard protocol with full uri
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:80/530ipjget/af4209?test=notgonnabreak');
		$this->assertEquals('http://ncsa.tech:80', $result);

		// Try wildcard protocol with no port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try wildcard protocol with wrong port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:443');
		$this->assertEquals('https://ncsa.tech:80', $result);
	}

	public function testAllowedSingleFixedIpOriginWithWildcardProtocol() {
		$allowed_origns = [
			[
				'protocol'=> '*',
				'origin'=> '192.168.1.1',
				'port'=> '80'
			]
		];

		// Try wildcard protocol with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:80');
		$this->assertEquals('http://192.168.1.1:80', $result);

		// Try wildcard protocol with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:80/635490et?test=canitdoit');
		$this->assertEquals('http://192.168.1.1:80', $result);

		// Try wildcard protocol with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1');
		$this->assertEquals('http://192.168.1.1', $result);

		// Try wildcard protocol with wrong port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://192.168.1.1:443');
		$this->assertEquals('https://192.168.1.1:80', $result);
	}

	public function testAllowedSingleFixedOriginWithWildcardFqdn() {
		$allowed_origns = [
			[
				'protocol'=> 'http',
				'origin'=> '*',
				'port'=> '80'
			]
		];

		// Try wildcard protocol with specific request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ctf-dev.ncsa.tech:80');
		$this->assertEquals('http://ctf-dev.ncsa.tech:80', $result);

		// Try wildcard protocol with full uri
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://www.ncsa.tech:80/530ipjget/af4209?test=notgonnabreak');
		$this->assertEquals('http://www.ncsa.tech:80', $result);

		// Try wildcard origin with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:80');
		$this->assertEquals('http://192.168.1.1:80', $result);

		// Try wildcard origin with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://10.8.0.1:80/635490et?test=canitdoit');
		$this->assertEquals('http://10.8.0.1:80', $result);

		// Try wildcard protocol with no port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try wildcard protocol with wrong port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:443');
		$this->assertEquals('http://ncsa.tech:80', $result);

		// Try wildcard origin with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1');
		$this->assertEquals('http://192.168.1.1', $result);

		// Try wildcard origin with wrong port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:443');
		$this->assertEquals('http://192.168.1.1:80', $result);
	}

	public function testAllowedSingleFixedFqdnOriginWithWildcardPort() {
		$allowed_origns = [
			[
				'protocol'=> 'http',
				'origin'=> 'ncsa.tech',
				'port'=> '*'
			]
		];

		// Try wildcard port with no port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try wildcard port with specific request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:123');
		$this->assertEquals('http://ncsa.tech:123', $result);

		// Try wildcard port with full uri on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:456/530ipjget/af4209?test=notgonnabreak');
		$this->assertEquals('http://ncsa.tech:456', $result);

		// Try wildcard port with invalid origin on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://www.ncsa.tech:80');
		$this->assertEquals('http://ncsa.tech:80', $result);

		// Try wildcard port with invalid protocol on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:80');
		$this->assertEquals('http://ncsa.tech:80', $result);
	}

	public function testAllowedSingleFixedIpOriginWithWildcardPort() {
		$allowed_origns = [
			[
				'protocol'=> 'http',
				'origin'=> '192.168.1.1',
				'port'=> '*'
			]
		];

		// Try wildcard port with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1');
		$this->assertEquals('http://192.168.1.1', $result);

		// Try wildcard port with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:1489');
		$this->assertEquals('http://192.168.1.1:1489', $result);

		// Try wildcard port with full uri on ip origin
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:1489/635490et?test=canitdoit');
		$this->assertEquals('http://192.168.1.1:1489', $result);

		// Try wildcard port with ip invalid origin
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.2:80');
		$this->assertEquals('http://192.168.1.1:80', $result);

		// Try wildcard port with ip invalid protocol
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://192.168.1.1:80');
		$this->assertEquals('http://192.168.1.1:80', $result);
	}

	/**
	 * These next four should be transitive with the current implementation, but if we ever change
	 * how the function works, they will be useful if that transitivity is no more.
	 */

	public function testAllowedSingleFixedFqdnOriginWithWildcardProtocolAndWildcardPort() {
		$allowed_origns = [
			[
				'protocol'=> '*',
				'origin'=> 'ncsa.tech',
				'port'=> '*'
			]
		];

		// Try on valid fqdn, no port specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try on valid fqdn with port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:8080');
		$this->assertEquals('http://ncsa.tech:8080', $result);

		// Try on valid fqdn with another protocl and another port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:9200');
		$this->assertEquals('https://ncsa.tech:9200', $result);

		// Try on invalid fqdn, no port specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://register.ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try on invalid fqdn with port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://register.ncsa.tech:11200');
		$this->assertEquals('http://ncsa.tech:11200', $result);
	}

	public function testAllowedSingleFixedIpOriginWithWildcardProtocolAndWildcardPort() {
		$allowed_origns = [
			[
				'protocol'=> '*',
				'origin'=> '172.16.6.6',
				'port'=> '*'
			]
		];

		// Try on valid fqdn, no port specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://172.16.6.6');
		$this->assertEquals('http://172.16.6.6', $result);

		// Try on valid fqdn with port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://172.16.6.6:8080');
		$this->assertEquals('http://172.16.6.6:8080', $result);

		// Try on valid fqdn with another protocl and another port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://172.16.6.6:9200');
		$this->assertEquals('https://172.16.6.6:9200', $result);

		// Try on invalid fqdn, no port specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://register.ncsa.tech');
		$this->assertEquals('http://172.16.6.6', $result);

		// Try on invalid fqdn with port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://register.ncsa.tech:11200');
		$this->assertEquals('https://172.16.6.6:11200', $result);
	}

	/**
	 * This configuration is stupid and should be $allowed_origns='*', but is technical valid and should work
	 */
	public function testAllowedSingleFixedWildCardOriginWithWildcardProtocolAndWildcardPort() {
		$allowed_origns = [
			[
				'protocol'=> '*',
				'origin'=> '*',
				'port'=> '*'
			]
		];

		// Try on protocl, fqdn, no port specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try on protocl, fqdn, port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:8080');
		$this->assertEquals('http://ncsa.tech:8080', $result);

		// Try on fqdn with another protocl and another port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:9200');
		$this->assertEquals('https://ncsa.tech:9200', $result);

		// Try on protocl, ip, no port specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://13.14.15.16');
		$this->assertEquals('http://13.14.15.16', $result);

		// Try on protocl, ip, port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://13.14.15.16:8080');
		$this->assertEquals('http://13.14.15.16:8080', $result);

		// Try on ip with another protocl and another port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://13.14.15.16:9200');
		$this->assertEquals('https://13.14.15.16:9200', $result);
	}

	public function testAllowedSeveralAllowedOrigins() {
		$allowed_origns = [
			[
				'protocol'=> 'http',
				'origin'=> 'ncsa.tech',
				'port'=> '*'
			],
			[
				'protocol'=> 'http',
				'origin'=> '192.168.1.1',
				'port'=> '*'
			]
		];

		// Try wildcard port with no port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try wildcard port with specific request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:123');
		$this->assertEquals('http://ncsa.tech:123', $result);

		// Try wildcard port with full uri on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:456/530ipjget/af4209?test=notgonnabreak');
		$this->assertEquals('http://ncsa.tech:456', $result);

		// Try wildcard port with invalid protocol on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:80');
		$this->assertEquals('http://ncsa.tech:80', $result);

		// Try wildcard port with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1');
		$this->assertEquals('http://192.168.1.1', $result);

		// Try wildcard port with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:1489');
		$this->assertEquals('http://192.168.1.1:1489', $result);

		// Try wildcard port with full uri on ip origin
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:1489/635490et?test=canitdoit');
		$this->assertEquals('http://192.168.1.1:1489', $result);

		// Try wildcard port with ip invalid origin
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.2:80');
		$this->assertEquals('http://ncsa.tech:80', $result);
	}

	public function testAllowedSeveralOriginsInOneDefinition() {
		$allowed_origns = [
			[
				'protocol'=> ['http', 'https'],
				'origin'=> ['ncsa.tech', '192.168.1.1'],
				'port'=> '*'
			],
		];

		// Try wildcard port with no port request specified
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech');
		$this->assertEquals('http://ncsa.tech', $result);

		// Try wildcard port with specific request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:123');
		$this->assertEquals('https://ncsa.tech:123', $result);

		// Try wildcard port with full uri on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://ncsa.tech:456/530ipjget/af4209?test=notgonnabreak');
		$this->assertEquals('http://ncsa.tech:456', $result);

		// Try wildcard port with invalid protocol on fqdn
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://ncsa.tech:80');
		$this->assertEquals('https://ncsa.tech:80', $result);

		// Try wildcard port with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'https://192.168.1.1');
		$this->assertEquals('https://192.168.1.1', $result);

		// Try wildcard port with ip origin, no request port
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:1489');
		$this->assertEquals('http://192.168.1.1:1489', $result);

		// Try wildcard port with full uri on ip origin
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.1:1489/635490et?test=canitdoit');
		$this->assertEquals('http://192.168.1.1:1489', $result);

		// Try wildcard port with ip invalid origin
		$result = $this->_resolveAllowedOrigin($allowed_origns, 'http://192.168.1.2:80');
		$this->assertEquals('http://ncsa.tech:80', $result);
	}
}
?>
