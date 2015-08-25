<?php // application/hooks/error404.php

Event::clear('system.404');
Event::add('system.404', 'my_404');

function my_404() 
{
//	global $site_id;
//	$page = Router::$current_uri.Router::$url_suffix.Router::$query_string;
	header('HTTP/1.1 404 Not Found');
	url::redirect('error404');
}
