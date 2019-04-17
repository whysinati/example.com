<?php
require '../core/session.php';

$hash = password_hash('12345', PASSWORD_DEFAULT);
var_dump(password_verify('12345g', $hash)); //returns boolean true/false
#var_dump(strlen(password_hash('12345', PASSWORD_DEFAULT))); //60 character hash revealed
#var_dump(password_hash('12345', PASSWORD_DEFAULT)); //60 character hash revealed

if(!empty($_POST)){
    $_SESSION['user'] = [];
    $_SESSION['user']['id'] = 12345; //
    header('LOCATION: ' . $_POST['goto']);
}

$meta=[];
$meta['title']="Login";
$goto=!empty($_GET['goto'])?$_GET['goto']:'/';

$content=<<<EOT
<form method="post">
    <input name="goto" value="{$goto}">
    <input type="submit" value="Login" class="btn btn-primary">
</form>
EOT;

require '../core/layout.php';