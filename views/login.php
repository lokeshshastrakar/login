<?php

require_once '../core/init.php';

if(input::exists())
{
    if(token::check(input::get('token')))
    {
        $validate = new validate();

        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if($validation->passed())
        {
            $remeber = (input::get('remember') === 'on') ? true : false;
            $user = new user();

            $login = $user->login(input::get('username'), input::get('password'), $remeber);

            if($login)
            {
                Redirect::to('index.php');
            }
            else
            {
                echo "username or password is incorrect";
            }
            
        }
        else{
            foreach($validation->errors() as $error)
            {
                echo $error , '</br>';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <div class="field">
            <label for="username">username</label>
            <input type="text" name="username" id="username" value="" autocomplete="off">
        </div>

        <div class="field">
            <label for="password">password</label>
            <input type="password" name="password" id="password" autocomplete="off" >
        </div>

        <div class="field">
            <label for="remember">
                <input type="checkbox" name="remember" id="remember">Remember me
            </label>
        </div>

        <input type="hidden" name="token" value="<?php echo token::generate() ?>">
        <input type="submit" value="Login">
    </form>
</body>
</html>