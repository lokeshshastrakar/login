<?php
    require_once '../core/init.php';
    // var_dump(input::exists());
    if(input::exists())
    {
        if(token::check(input::get('token')))
        {
                $validate = new validate();

                $validation = $validate->check($_POST, array(
                    'username' => array(
                        'required' => true,
                        'min' => 2,
                        'max' => 20,
                        'unique' => 'users' 
                    ),
                    'password' => array(
                        'required' => true,
                        'min' => 6
                    ),
                    'password_again' => array(
                        'required' => true,
                        'matches' => 'password'
                    ),
                    'name' => array(
                        'required' => true,
                        'min' => 2,
                        'max' => 20
                    )
                ));
            if($validation->passed())
            {
                $user = new user();

                $salt = hash::salt(32);
                // die(date('Y-m-d H:i:s'));

                try{
                    $user->create(array(
                        'username' => input::get('username'),
                        'password' => hash::make(input::get('password'), $salt),
                        'salt' => $salt,
                        'name' => input::get('name'),
                        'joined' => date('Y-m-d H:i:s'),
                        'groups' => 1
                    ));

                    session::flash('home', 'You have been registred succefully and can now log in');
                    Redirect::to("index.php");

                }catch(Exception $e)
                {

                    die($e->getMessage());
                }
            }
            else{
                //output errorse
                foreach($validation->errors() as $error)
                {
                    echo $error . '</br>';
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
    <title>OOP Authentication | Register</title>
</head>
<body>

<form action="" method="post">
<div class="field">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="<?php echo escape(input::get('username')); ?>" autocomplete="off">
    </div>


    <div class="field">
        <label for="password">Choose a password</label>
        <input type="password" name="password" id="password" value="" autocomplete="off" >
    </div>

    <div class="field">
        <label for="password_again">Enter password again</label>
        <input type="password" name="password_again" id="password_again" value="" autocomplete="off" >
    </div>

    <div class="field">
        <label for="name">name</label>
        <input type="text" name="name" id="name" value="<?php echo escape(input::get('name')); ?>" autocomplete="off">
    </div>
    <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
    <input type="submit" value="Register">
</form>
    
</body>
</html>