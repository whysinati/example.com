<?php
require '../bootstrap.php';
require '../core/db_connect.php';
require '../core/About/src/Validation/Validate.php';
$message=NULL;

use About\Validation;
$valid = new About\Validation\Validate();

//$hash = password_hash('12345', PASSWORD_DEFAULT);
//var_dump(password_verify('12345g', $hash)); //returns boolean true/false
#var_dump(strlen(password_hash('12345', PASSWORD_DEFAULT))); //60 character hash revealed
#var_dump(password_hash('12345', PASSWORD_DEFAULT)); //60 character hash revealed

$args=[
    'password'=>FILTER_UNSAFE_RAW,
    'first_name'=>FILTER_SANITIZE_STRING,
    'last_name'=>FILTER_SANITIZE_STRING,
    'email'=>FILTER_SANITIZE_EMAIL,
    'confirm_password'=>FILTER_UNSAFE_RAW
];

$input = filter_input_array(INPUT_POST, $args);

if(!empty($input)){ // a listener for a post

    $valid->validation = [
        'email'=>[[
                'rule'=>'email',
                'message'=>'Please enter a valid email.'
            ],[
                'rule'=>'notEmpty',
                'message'=>'Please enter your email.'
            ]],
        'first_name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your first name.'
            ]],
        'last_name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your last name.'
            ]],
        'password'=>[[
                'rule'=>'notEmpty',
                'message'=>'Please enter a password.'
            ],[
                'rule'=>'strength',
                'message'=>'8 or more characters and 1 or more lowercase, uppercase, and special char.'
            ]],
        'confirm_password'=>[[
                'rule'=>'notEmpty',
                'message'=>'Please confirm your password.'
            ],[
                'rule'=>'matchPassword',
                'message'=>'Passwords do not match.'
            ]]
        
    ];

    $valid->check($input);

    if(empty($valid->errors)){
        //2. Only process if we pass validation

        //Strip white space, beginning and end
        $input = array_map('trim', $input);

        $hash = password_hash($input['password'], PASSWORD_DEFAULT);

        $sql='INSERT INTO
            users
        SET
            id=UUID(),
            email=:email,
            first_name=:first_name,
            last_name=:last_name,
            hash=:hash
        ';

        $stmt=$pdo->prepare($sql);
        try{

            $stmt->execute([
                'email'=>$input['email'],
                'first_name'=>$input['first_name'],
                'last_name'=>$input['last_name'],
                'hash'=>$hash
            ]);
                header('LOCATION: /login.php');
        }
        catch(PDOException $e) {
            #var_dump($e); //used this var_dump to find the index loc of the errorInfo msg for dup email
            $message="<div class=\"alert alert-danger\">{$e->errorInfo[2]}</div>";
            }

        }else{
            $message = "<div class=\"alert alert-danger\">Your form has errors!</div>";
        }
    }



    #var_dump($hash);
    //$_SESSION['user'] = [];
    //$_SESSION['user']['id'] = 12345; //
    //header('LOCATION: ' . $_POST['goto']);

$meta=[];
$meta['title']="Register";
//$goto=!empty($_GET['goto'])?$_GET['goto']:'/';

$content=<<<EOT
<h1>{$meta['title']}</h1>
{$message}
<form method="post" autocomplete="off">
    <div class="form-group">
        <label for="email">Email</label>
        <input 
            class="form-control"
            id="email" 
            name="email" 
            type="email"
            value="{$valid->userInput('email')}"
        >
        <div class="text text-danger">{$valid->error('email')}</div>
    </div>
    <div class="row">
        <div class="form-group">
        <label for="first_name">First Name</label>
        <input 
            class="form-control"
            id="first_name" 
            name="first_name" 
            type="text"
            value="{$valid->userInput('first_name')}"
        >
        <div class="text text-danger">{$valid->error('first_name')}</div>
        </div>
        <div class="form-group">
        <label for="last_name">Last Name</label>
        <input 
            class="form-control"
            id="last_name" 
            name="last_name" 
            type="text"
            value="{$valid->userInput('last_name')}"
        >
        <div class="text text-danger">{$valid->error('last_name')}</div>
        </div>
    </div>
    <div class="form-group">
    <label for="password">Password</label>
    <input 
        class="form-control"
        id="password" 
        name="password" 
        type="password"
        value="{$valid->userInput('password')}"
    >
    <div class="text text-danger">{$valid->error('password')}</div>
    </div>

    <div class="form-group">
    <label for="confirm_password">Confirm Password</label>
    <input 
        class="form-control"
        id="confirm_password" 
        name="confirm_password" 
        type="password"
        value="{$valid->userInput('confirm_password')}"
    >
    <div class="text text-danger">{$valid->error('confirm_password')}</div>
    </div>

    <input type="submit" value="Create Account" class="btn btn-primary">
</form>
EOT;

require '../core/layout.php';