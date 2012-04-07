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


Route::get('/', function()
{
	session_start();
	if(isLoggedIn())
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

Route::get('s/(:any)', function($hash)
{
	if(DB::table('shares')->where('urlHash', '=', $hash)->count() !=1)
		Redirect::to('404')->send();
	$share = DB::table('shares')->where('urlHash', '=', $hash)->first();
	if(!file_exists('/img/48x48/'.$share->icon.'.gif'))
		$share->icon = 'page_white48';
	return View::make('home.view')->with('share', $share);
});

Route::get('s/(:any)/dl', function($hash)
{
	if(DB::table('shares')->where('urlHash', '=', $hash)->count() !=1)
		Redirect::to('404')->send();
	$share = DB::table('shares')->where('urlHash', '=', $hash)->first();
	DB::table('shares')->where('id', '=', $share->id)->update(array('downloads' => $share->downloads+1));
	Redirect::to($share->publiclink)->send();
});

Route::get('linkdropbox', function()
{
	$dropbox = requireDropbox();
	//I might log users later but for now I want complete transparency
	/*$dbid = Input::get('uid');
	$oauth_token = Input::get('oauth_token');
	DB::table('users')->insert(array('dbid' => $dbid, 'accessKey'=>$oauth_token));*/
	Redirect::to('/')->send();
});

Route::get('linkdropbox/(:any)', function($hash)
{
	
	$dropbox = requireDropbox();
	//I might log users later but for now I want complete transparency
	/*$dbid = Input::get('uid');
	$oauth_token = Input::get('oauth_token');
	DB::table('users')->insert(array('dbid' => $dbid, 'accessKey'=>$oauth_token));*/
	Redirect::to('/s/'.$hash)->send();
});

Route::get('getFileList', function (){
	$dropbox = requireDropbox();
	printData('/',0,$dropbox);
});


Route::get('logout', function()
{
	session_start();
	session_destroy();
	Redirect::to('/')->send();
});

Route::post('fileupload', function()
{
	session_start();
	if(!isLoggedIn())
		die(json_encode(array('error' => 'Dropbox is not linked!')));
	$dropbox = requireDropbox();
	/**
	 * upload.php
	 *
	 * Copyright 2009, Moxiecode Systems AB
	 * Released under GPL License.
	 *
	 * License: http://www.plupload.com/license
	 * Contributing: http://www.plupload.com/contributing
	 */

	// HTTP headers for no cache etc
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	// Settings
	$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
	//$targetDir = 'uploads';

	$cleanupTargetDir = true; // Remove old files
	$maxFileAge = 5 * 3600; // Temp file age in seconds

	// 5 minutes execution time
	@set_time_limit(5 * 60);

	// Uncomment this one to fake upload time
	// usleep(5000);

	// Get parameters
	$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

	// Clean the fileName for security reasons
	$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

	// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
		$ext = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);

		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
			$count++;

		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}

	$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

	// Create target dir
	if (!file_exists($targetDir))
		@mkdir($targetDir);

	// Remove old temp files	
	if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
		while (($file = readdir($dir)) !== false) {
			$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

			// Remove temp file if it is older than the max age and is not the current file
			if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
				@unlink($tmpfilePath);
			}
		}

		closedir($dir);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
		

	// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

	if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];

	// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos($contentType, "multipart") !== false) {
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen($_FILES['file']['tmp_name'], "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				fclose($in);
				fclose($out);
				@unlink($_FILES['file']['tmp_name']);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	} else {
		// Open temp file
		$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen("php://input", "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

			fclose($in);
			fclose($out);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	}

	// Check if file has been uploaded
	if (!$chunks || $chunk == $chunks - 1) {
		// Strip the temp .part suffix off 
		rename("{$filePath}.part", $filePath);
		$fileArray= explode('/', $filePath);
		$dropbox->putFile($filePath, $fileArray[count($fileArray)-1], '/', false);
		unlink($filePath);
	}

	// Return JSON-RPC response
	die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
});

Route::get('getDSLink', function()
{
	session_start();
	if(!isLoggedIn())
		die(json_encode(array('error' => 'Dropbox is not linked!')));
	$dropbox = requireDropbox();
	$path = Input::get('path');
	$size = Input::get('size');
	$icon = Input::get('icon');
	$pathArray = explode('/', $path);
	$file = $pathArray[count($pathArray)-1];
	$copyRef = $dropbox->copyRef($path);
	$copyRef = $copyRef['body']->copy_ref;
	$shareLink = $dropbox->media($path);
	$shareLink = $shareLink['body']->url;
	$hash = substr(md5($path.time()), 0, 10);

	while(DB::table('shares')->where('urlHash', '=', $hash)->count() !=0)
	{
		$hash = substr(md5($path.time()), 0, 10);
	}
	DB::table('shares')->insert(array(
		'filename' 		=> $file,
		'publicLink' 	=> $shareLink,
		'copyRef'		=> $copyRef,
		'urlHash'		=> $hash,
		'icon'			=> $icon,
		'size'			=> $size
	));
	echo json_encode(array('url' => "http://".$_SERVER['SERVER_NAME']."/s/".$hash));
});

Route::get('addToDB/(:any)', function($hash)
{
	session_start();
	if(!isLoggedIn())
		die(json_encode(array('message' => 'Your Dropbox is not linked, Click the button above to link your accout so you can copy the file','error'=> true,'code'=>-1)));
	$dropbox = requireDropbox();
	
	$hash = preg_replace("/^A-Za-z0-9/i", "", $hash);
	if(DB::table('shares')->where('urlHash', '=', $hash)->count() !=1)
		die("Hash not found");
	$share = DB::table('shares')->where('urlHash', '=', $hash)->first();
	try {
		$dropbox->copy(null,'/'.$share->filename,$share->copyref); // Turned off while testing
		DB::table('shares')->where('id', '=', $share->id)->update(array('copies' => $share->copies+1));
		echo json_encode(array('message' => "File Copied!", 'error'=>false));
	} catch (Exception $e) {
		//echo json_encode(array('message' => print_r($e,1), 'error'=>true));
		if($e->getMessage() == 'the file at u\''.$share->filename.'\' already exists. (Status Code: 403)')
			echo json_encode(array('message' => $share->filename.' already exists in your Dropbox folder!', 'error'=>true));
		else
			echo json_encode(array('message' => 'There was an error tyring to copy this file', 'error'=>true));
	}
	
	
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
function isLoggedIn()
{
	if(isset($_SESSION['dropbox_api']) && is_array($_SESSION['dropbox_api']))
		return true;
	else
		return false;
}

function requireDropbox($callback =null )
{
	// Register a simple autoload function
	spl_autoload_register(function($class){
		$class = str_replace('\\', '/', $class);
		require_once('lib/' . $class . '.php');
	});
	// Check whether to use HTTPS and set the callback URL
	$protocol = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';
	if($callback==null)
		$callback = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	// Instantiate the required Dropbox objects
	$encrypter = new \Dropbox\OAuth\Storage\Encrypter('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
	$storage = new \Dropbox\OAuth\Storage\Session($encrypter);
	$OAuth = new \Dropbox\OAuth\Consumer\Curl(getenv('HTTP_DROPBOX_KEY'), getenv('HTTP_DROPBOX_SECRET'), $storage, $callback);
	$dropbox = new \Dropbox\API($OAuth);
	return $dropbox;

}

function printData($path,$level=0,$dropbox)
{
	$level++;
	$count=0;
	$metaData = $dropbox->metaData($path);
	$files = $metaData['body']->contents;
	if($level>1)
		echo '<ul style="display:none;">';
	else
		echo "<ul>";
	foreach($files as $file)
	{
		$count++;
		$path = $file->path;
		$filePathArray = explode('/',$path);
		$filename = $filePathArray[count($filePathArray)-1];
		//print_r($file);
		if($file->is_dir)
		{
			echo '<li class="parent">
					<span class="fileIcon">
						<img src="/img/16x16/'.$file->icon.'.gif">
					</span>
					<span class="filename">'
					.$filename.
					'</span>
				  ';
			printData($file->path,$level,$dropbox);
			echo '</li>';
		}
		else
			echo '<li>
					<span class="fileIcon">
						<img src="/img/16x16/'.$file->icon.'.gif">
					</span>
					<span class="filename">'
					.$filename.
					'</span>
					<span class="getLinkSpan" id="dbshare_'.$level.'-'.$count.'">
						<a href="javascript:getLink(\''.$path.'\',\''.$file->icon.'\',\''.$file->size.'\',\''.$level.'-'.$count.'\')" class="getLink"></a>
					</span>
				  </li>';
	}
	echo "</ul>";

}