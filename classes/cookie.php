<?php


class cookie{
    public static function exist($name)
    {
        return (isset($_COOKIE[$name]) ? true : false);
    }

    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    public static function put($name, $value, $expiry)
    {
        if(setcookie($name,$value, (int)(time() + $expiry), '/'))
        {
            return true;
        }
        return false;
    }

    public static function delete($name)
    {
        // die(time());
        $tset = self::put($name, '', time() - 1);
    }
}