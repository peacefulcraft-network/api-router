<?php
use net\peacefulcraft\apirouter\router\Response;

class BasicHTTPApplicationTest extends ControllerTest{

    public function testApplicationShould200OnIndex(){

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

        $this->assertEquals(curl_errno($curl), 0);
        $this->assertEquals(200, curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $this->assertEquals('<h1>Hello World!</h1>', $result);

        curl_close($curl);
    }

    public function testApplicationShould404OnNonExistentController() {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://localhost:8081/path/that/does/not/exist');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);

        $expected = json_encode([ 'error_no'=>404, 'error_message'=>'Resource not found.' ]);

        $this->assertEquals(curl_errno($curl), 0);
        $this->assertEquals(404, curl_getinfo($curl, CURLINFO_HTTP_CODE));
        $this->assertEquals($expected, $result);
        curl_close($curl);
    }
}
?>