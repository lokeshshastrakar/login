<?php

require_once '../core/init.php';

if(session::exist('home'))
{
    echo session::flash('home');
}

$user = new user();

if($user->isLoggedIn())
{
?>

<p> hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a></p>


<ul>

<li>
    <a href="logout.php"> Log out</a>
</li>
<li><a href="update.php"> update details</a></li>
<li><a href="changepassword.php"> Change Password</a></li>
</ul>

<?php

    if($user->hasPermissions('admin'))
    {
        echo "you are a admin";
    }


}else{
    echo "<p> you need to <a href='login.php'> log in </a> or <a href='register.php'>register</a> ";
}