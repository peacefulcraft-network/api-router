<?php
namespace ncsa\phpmvj\console\directives;

use \ncsa\phpmvj\console\Console;
use \ncsa\phpmvj\console\Directive;

class Help implements Directive {
  public function getName():string { return "help"; }

  public function getDescription(): string { return "Prints help messages for other directives"; }

  public function printHelpMessage(): void { Console::printLine("[light_blue]help [normal][directive]"); }

  public function getArgs():array { return []; }

  public function execute(Console $console, array $args):int {
    if (count($args) > 0) {
      if (isset($console->getActiveDirectives()[$args[0]])) {
        $console->getActiveDirectives()[$args[0]]->printHelpMessage();
      } else {
        Console::printLine("[red]Unknown directive [light_blue]" . $args[0]);
      }
    } else {
      foreach($console->getActiveDirectives() as $directive) {
        Console::printLine("[light_blue]" . $directive->getName() . "[normal] " . $directive->getDescription());
      }
    }
    return 0;
  }
}
?>