<?php

require_once '../core/init.php';

if(!$username = input::get('user'))
{
    redirect::to('index.php');
}
else{
    $user = new user($username);

    if($user->exists())
    {
        $data = $user->data();
        ?>

        <h3> <?php echo escape($data->username) ?></h3>
        <p>Name:  <?php echo escape($data->name) ?></p>
        <?php
    }
    else{
        redirect::to(404);
    }
}