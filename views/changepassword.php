<?php

require_once '../core/init.php';
$user = new user();

if(!$user->isLoggedIn())
{
    redirect::to('index.php');
}

if(input::exists())
{
    if(token::check(input::get('token')))
    {
        $validate = new validate();

        $validation = $validate->check($_POST, array(
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'new_password' => array(
                'required' =>true,
                'min' => 6
            ),
            'new_password_again' => array(
                'required' => true,
                'min' => 6,
                'matches' => 'new_password'
            )
            ));
            if($validation->passed()){
                if(hash::make(input::get('password'), $user->data()->salt) != $user->data()->password)
                {
                    echo "your current password is wrong";
                }
                else{
                    try{
                    $salt = hash::salt(32);
                    $user->update(array(
                        'password' => hash::make(input::get('new_password'), $salt),
                        'salt' => $salt
                    ));

                    session::flash('home', 'Your password has been changed');
                    redirect::to('index.php');

                }
                catch(Exception $e){
                    echo $e->getMessage();
                }
            }
        }
            else{
                foreach($validation->errors() as $err)
                {
                    echo $err , '</br>';
                }
            }
    }
}

?>

<form action="" method="post">
    <div class="field">
        <label for="password">current password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
    <label for="new_password">New password</label>
    <input type="password" name="new_password" id="new_password">
    </div>

    <div class="field">
        <label for="new_password_again">Enter new Password Again</label>
        <input type="password" name="new_password_again" id="new_password_again">
    </div>

    <input type="hidden" name="token" value="<?php echo token::generate() ?>">
    <input type="submit" value="Change Password">

</form>