<?php
namespace net\peacefulcraft\apirouter\console;

interface Command {
  public function getName(): string;

  public function getDescription(): string;

  public function printHelpMessage(): void;

  public function getArgs(): array;

  public function execute(Console $console, array $args): int;
}
?>