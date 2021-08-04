<?php namespace net\peacefulcraft\apirouter\api;

interface ExtensibleApplication {

	public function getActivePlugins(): array;

	public function usePlugin(ApplicationPlugin $Plugin): void;
}