<?php
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

abstract class ControllerTest extends TestCase {
  private static $proc;
  private static $pipes;

  /**
   * @beforeClass
   */
  public static function setupWebServer() {
    $descriptorspec = array (  
      1 => array("pipe", "w"), 
      2 => array("pipe", "w"), 
    );  

    echo "starting dev-server" . PHP_EOL;
    $doc_root = __DIR__  . "/../src/public";
    $test_server_entrypoint = $doc_root . "/test-server.php";
    echo $doc_root . PHP_EOL;
    SELF::$proc = new Process(["php", "-S", "localhost:8081", "-t", $doc_root, $test_server_entrypoint]);
    SELF::$proc->start();
    usleep(1000000);
  }

  /**
   * @afterClass
   */
  public static function tearDownWebServer() {
    SELF::$proc->stop();
    SELF::$proc = null;
  }
}

?>