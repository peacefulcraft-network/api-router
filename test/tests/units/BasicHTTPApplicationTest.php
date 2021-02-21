<?php
use ncsa\phpmvj\router\Response;

class BasicHTTPApplicationTest extends ControllerTest{

    public function testApplicationShould200OnIndex(){

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

				Response::setHTTPResponseCode(200);
        $expected = new Response(array("message" => "Hello World!"));
        $expected = json_encode($expected);

        $this->assertEquals(curl_errno($curl), 0);
        $this->assertEquals($expected, $result);

        curl_close($curl);
    }

    public function testApplicationShould404OnNonExistentController() {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/path/that/does/not/exist');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

				Response::setHTTPResponseCode(200);
        $expected = new Response([], 404, 'Resource not found');
        $expected = json_encode($expected);

        $this->assertEquals(curl_errno($curl), 0);
        $this->assertEquals($expected, $result);
        curl_close($curl);
    }
}
?>