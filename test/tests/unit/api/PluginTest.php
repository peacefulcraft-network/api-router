<?php

use net\peacefulcraft\apirouter\Application;
use net\peacefulcraft\apirouter\router\Response;
use net\peacefulcraft\apirouter\test\api\DummyPlugin;

class PluginTest extends ControllerTest {

	private static Application $Application;

	/**
	 * @beforeClass
	 */
	public static function prep() {
		SELF::$Application = new Application([]);
		SELF::$Application->usePlugin(new DummyPlugin());
	}

	public function testPluginCommandsRegister() {
		$this->assertEquals(418, SELF::$Application->runConsoleCommand('dummyplugin:foobar'), 'Failed to execute plugin registered command.');
		$this->assertEquals(1, SELF::$Application->runConsoleCommand('dummyplugin:fakefake'), 'Executed command when no command should have been found.');
	}

	public function testPluginRoutesResolve() {
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/dummyplugin/plugintest');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($curl);

		$expected = new Response(200, ['OK'], 0, '');
		$expected = json_encode($expected);

		$this->assertEquals(curl_errno($curl), 0);
		$this->assertEquals($expected, $result);
		curl_close($curl);
	}
}
?>