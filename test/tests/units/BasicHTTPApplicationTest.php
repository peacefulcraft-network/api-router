<?php
use ncsa\phpmvj\router\Response;

class BasicHTTPApplicationTest extends ControllerTest{

    public function testApplicationShould200OnIndex(){

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

        $expected = new Response(200, array("message" => "Hello World!"));
        $expected = json_encode($expected);

        $this->assertEquals(curl_errno($curl), 0);
        $this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $this->assertEquals($expected, $result);

        curl_close($curl);
    }

    public function testApplicationShould404OnNonExistentController() {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/path/that/does/not/exist');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

        $expected = new Response(404, [], 0, 'Resource not found');
        $expected = json_encode($expected);

        $this->assertEquals(curl_errno($curl), 0);
        $this->assertEquals(404, curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $this->assertEquals($expected, $result);
        curl_close($curl);
    }
}
?>