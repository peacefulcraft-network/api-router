# api-router
A simple PHP framework providing utilities for
- Routing
- Middlware
- Field validation
- Enumerated types

## Usage
This framework is most easily integrated into an environment using the PSR-4 naming standard with class auto-loading.

Add this repo to the `repositories` section of your `composer.json`.
```json
"repositories": [
   {
     "type": "vcs",
     "url": "https://github.com/peacefulcraft-network/api-router"
   }
 ]
 ```
 
 Install the library
 ```
 composer require pcn/api-router
 ```

### Routing
```php
ob_start();
/*
$config is an associtive array of settings for your application
Right now there are no requisite values you. It is primarly intended for implementer usage.
$config is exposed to your controllers and middleware.
*/
use \net\peacefulcraft\apirouter\Application;
$Application = new Application($config);

$router = $Application->getRouter();
/*
Registers the controller \net\peacefulcraft\apirouter\test\controllers\Index
as being responsible for any HTTP/GET requests to '/'.

Technically a single controller can handle multiple routes just include multiple registerRoute() calls that point
to that controller. Sometimes this is useful for re-using code on similar routes like entity create and entity update routes.
*/
$router->registerRoute(RequestMethod::GET, '/', [], '\net\peacefulcraft\apirouter\test\controllers\Index');

// Registering the index controller for another route
$router->registerRoute(RequestMethod::POST, '/copycat', [], '\net\peacefulcraft\apirouter\test\controllers\Index');

/*
Registers the controller \net\peacefulcraft\apirouter\test\controllers\Index
as being responsible for any HTTP/GET requests to '/:id', where ':id' is a varable value that will be
extracted to an associative array of URI paramters.

IE HTTP/GET youapplication.com/Hello%20World would be stored under $Request->getURIParmaters()['id'] as 'Hello World'
*/
$router->registerRoute(RequestMethod::GET, '/:id', [], '\net\peacefulcraft\apirouter\test\controllers\Index');

/*
Registers the controller \net\peacefulcraft\apirouter\test\controllers\Index
as being responsible for any HTTP/Get requests to '/', but also inserts the dummy middleware class 'Alwaysware'
to run before the controller's handle() method is executed.

Alwaysware is a dummy class that will always allow a request to pass by returning true.
Requests can be stoppped by middleware which return false.
Middlware can also be used to do things like retrieve session information and transform requests paramters / bodies.

Middleware is executed in the order which is it listed in the array. 0 to n middleware classes can be presented. Technically a single middleware class can be included several times in that list and it will be executed several distinct times.
*/
$router->registerRoute(RequestMethod::GET, '/profile', [
	'\net\peacefulcraft\apirouter\test\middleware\Alwaysware'
], '\net\peacefulcraft\apirouter\test\controllers\Index');


/*
This tells the framework to actually do stuff. Registering routes builds out the routing tree, but doesn't actually cause anything to happen.
*/
$Application->handle();
ob_flush();

/*
How is a response actually sent back to the user?
The $Response->setData() call can be used to set what data is sent back by the application. This value is automatically printed out by the $Application after the controller's handle() method finishes executing.

Alternativly, $Response->setResponseTypeRaw(true); will disable all framework output and a response can be crafted using standard PHP echo / print calls. Appropriate headers may need to be set for 'Content-Type' by hand. Technically anything can be 'echo'ed at anytime, regardless of setResponseTypeRaw(), but this output will also contain the framework output. Generally this is only useful for 'echo'ing out debug information and in production you'll just want to use the Router with your own templating system, or utilize the built in setData() method.
*/
```
