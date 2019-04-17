<?php
require '../core/session.php';
require '../core/db_connect.php';

#use About\Validation;

$input = filter_input_array(INPUT_POST, [
    'password'=>FILTER_UNSAFE_RAW,
    'email'=>FILTER_SANITIZE_EMAIL,
]);

if(!empty($input)){

    $hash = password_hash($input['password'], PASSWORD_DEFAULT);
    
    $sql='SELECT id, hash FROM users WHERE email=:email';

    $stmt=$pdo->prepare($sql);
    $stmt->execute([
        'email'=>$input['email']
    ]);
        #var_dump($stmt->fetch());
        $row=$stmt->fetch();
    
    #var_dump($row);
    
    if($row){
        #var_dump(password_verify($input['password'], $row['hash']));
        $match = password_verify($input['password'], $row['hash']);
        if($match){
            $_SESSION['user'] = [];
            $_SESSION['user']['id'] = $row['id'];
            header('LOCATION: ' . $_POST['goto']);
        }
    }

}

#$hash = password_hash('12345', PASSWORD_DEFAULT);
#var_dump(password_verify('12345g', $hash)); //returns boolean true/false
#var_dump(strlen(password_hash('12345', PASSWORD_DEFAULT))); //60 character hash revealed
#var_dump(password_hash('12345', PASSWORD_DEFAULT)); //60 character hash revealed
/*
if(!empty($_POST)){
    $_SESSION['user'] = [];
    $_SESSION['user']['id'] = 12345; //
    header('LOCATION: ' . $_POST['goto']);
}
*/

$meta=[];
$meta['title']="Login";
$goto=!empty($_GET['goto'])?$_GET['goto']:'/';

$content=<<<EOT

<h1>{$meta['title']}</h1>
<form method="post" autocomplete="off">

    <div class="form-group">
    <label for="email">Email</label>
    <input 
        class="form-control"
        id="email" 
        name="email" 
        type="email"

    >
    </div>
    

    <div class="form-group">
    <label for="password">Password</label>
    <input 
        class="form-control"
        id="password" 
        name="password" 
        type="password"
     
    >
    </div>

    <input name="goto" value="{$goto}" type="hidden">
    <input type="submit" value="Login" class="btn btn-primary">

</form>
EOT;

require '../core/layout.php';