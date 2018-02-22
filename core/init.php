<?php
session_start();


$GLOBALS['config'] = array (
    'mysql' => array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'jays',
        'database' => 'ooplr',
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => ''
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 3600
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )

);


spl_autoload_register(function($class){
    require_once '../classes/' . $class . '.php';
});

require_once '../functions/sanitize.php';

if(cookie::exist(config::get('remember/cookie_name')) && !session::exist(config::get('session/session_name')))
{
    $hash =  cookie::get(config::get('remember/cookie_name'));

    $hashcheck = DB::getInstance()->get('sessions', array('hash', '=', $hash));

    if($hashcheck->count())
    {
       $user = new user($hashcheck->first()->user_id);
       $user->login();
    }
}