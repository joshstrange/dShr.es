<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your applications using Laravel's RESTful routing, and it
| is perfectly suited for building both large applications and simple APIs.
| Enjoy the fresh air and simplicity of the framework.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post('hello, world', function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/


Route::get('/, are', function()
{
	session_start();
	if(isset($_SESSION['dropbox_api']) && is_array($_SESSION['dropbox_api']))
	{
		$dropbox = requireDropbox();
		$loggedIn =true;
	}
	else
	{
		$dropbox = null;
		$loggedIn =false;
	}
	return View::make('home.index')->with('loggedIn', $loggedIn)->with('dropbox', $dropbox);
});


Route::get('dbtest', function()
{
	$dropbox = requireDropbox();
	return print_r($dropbox->accountInfo(),1);
});

Route::get('linkdropbox', function()
{
	$dropbox = requireDropbox();
	$dbid = Input::get('uid');
	$oauth_token = Input::get('oauth_token');
	DB::table('users')->insert(array('dbid' => $dbid, 'accessKey'=>$oauth_token));
	Redirect::to('are')->send();
});



Route::get('session_debug', function()
{
	session_start();
	echo "<pre>";
	return print_r($_SESSION,1);
});
/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in "before" and "after" filters are called before and
| after every request to your application, and you may even create other
| filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

function requireDropbox()
{
	// Register a simple autoload function
	spl_autoload_register(function($class){
		$class = str_replace('\\', '/', $class);
		require_once('lib/' . $class . '.php');
	});
	// Check whether to use HTTPS and set the callback URL
	$protocol = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';
	$callback = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// Instantiate the required Dropbox objects
	$encrypter = new \Dropbox\OAuth\Storage\Encrypter('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
	$storage = new \Dropbox\OAuth\Storage\Session($encrypter);
	$OAuth = new \Dropbox\OAuth\Consumer\Curl(getenv('HTTP_DROPBOX_KEY'), getenv('HTTP_DROPBOX_SECRET'), $storage, $callback);
	$dropbox = new \Dropbox\API($OAuth);
	return $dropbox;

}