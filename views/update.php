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
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 20
            )
            ));

            if($validation->passed())
            {
                //update data
                try
                {
                    $user->update(array(
                        'name' => input::get('name')
                    ));

                    session::flash('home', 'your details have been updted');
                    redirect::to('index.php');
                }
                catch(Exception $e)
                {
                    echo $e->getMessage();
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
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo escape($user->data()->name) ?>">
    </div>

    <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
    <input type="submit" value="Update">
</form>