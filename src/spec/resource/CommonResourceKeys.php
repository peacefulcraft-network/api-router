<?php namespace net\peacefulcraft\apirouter\spec\resource;

/**
 * A collection of standard resource keys that should be preferred
 * for registering resources of the given type.
 * 
 * If there is a database not listed here let us know and we can add it.
 * The convention should be pretty clear. This system can be a bit verbose,
 * but it is less overhead than requiring full DBMS solutions for every database,
 * while still being flexible enough that you can use heavy DBMS solutions
 * where they are valuable.
 */
interface CommonResourceKeys {
	CONST PHP_AMQP = 'php-amqp';
	CONST COUCHDB = 'couchdb';
	CONST ELASTIC = 'elastic';
	CONST MEMCACHED = 'memcached';
	CONST MONGO = 'mongodb';
	CONST MYSQLI = 'mysqli';
	CONST PDO = 'pdo';
	CONST POSTGRESQL = 'postgresql';
	CONST PHP_MQTT = 'php_mqtt';
	CONST REDIS = 'redis';
}

?>