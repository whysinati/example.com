<?php
require '../../bootstrap.php';
require '../../core/functions.php';
require '../../config/keys.php';
require '../../core/db_connect.php';
require '../../core/About/src/Validation/Validate.php';

checkSession();

use About\Validation;

$valid = new About\Validation\Validate();

$meta=[];
$meta['title']="Add a blog user";
$meta['description']="A form to add blog users";
$meta['keywords']=false;

$message=null;

$args = [
    'email'=>FILTER_SANITIZE_EMAIL, //strips email(?)
    'first_name'=>FILTER_SANITIZE_STRING, //strips HTML
    'last_name'=>FILTER_SANITIZE_STRING, //strips HTML
#    'body'=>FILTER_UNSAFE_RAW //NULL FILTER
];

$input = filter_input_array(INPUT_POST, $args);

//1. First validate

if(!empty($input)){

    $valid->validation = [
        'email'=>[[
                'rule'=>'email',
                'message'=>'Please enter a valid email.'
            ],[
                'rule'=>'notEmpty',
                'message'=>'Please user\'s email.'
            ]],
        'first_name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your first name.'
            ]],
        'last_name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your last name.'
            ]]
    ];

    $valid->check($input);

    if(empty($valid->errors)){
        //2. Only process if we pass validation

        //Strip white space, begining and end
        $input = array_map('trim', $input);

        //Allow only whitelisted HTML
        #$input['body'] = cleanHTML($input['body']);

        //Create the slug
        #$slug = slug($input['title']);

        //Sanitized insert
        #$sql = 'INSERT INTO users SET id=uuid(), title=?, slug=?, body=?';
        $sql = 'INSERT INTO users SET id=uuid(), email=:email, first_name=:first_name, last_name=:last_name';
    
        if($pdo->prepare($sql)->execute([
            $input['email'],
            $input['first_name'],
            $input['last_name']
        ])){
        header('LOCATION:/users');
        }else{
            $message = 'Something bad happened';
        }
}   else{
        $message = "<div class=\"alert alert-danger\">Your form has errors!</div>";
    }
}

$content = <<<EOT
<h1>Add a New User</h1>
{$message}
<form method="post">

<div class="form-group">
    <label for="email">Email</label>
    <input id="email" name="email" type="text" class="form-control" value="{$valid->userInput('email')}">
    <div class="text-danger">{$valid->error('email')}</div>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="first_name">First Name</label>
        <textarea id="first_name" name="first_name" rows="1" class="form-control">{$valid->userInput('first_name')}</textarea>
        <div class="text-danger">{$valid->error('first_name')}</div>
        </div>

    <div class="form-group col-md-6">
        <label for="last_name">Last Name</label>
        <textarea id="last_name" name="last_name" rows="1" class="form-control">{$valid->userInput('last_name')}</textarea>
        <div class="text-danger">{$valid->error('last_name')}</div>
    </div>
</div>

<div class="form-group">
    <input type="submit" value="Submit" class="btn btn-primary">
</div>
</form>
EOT;

include '../../core/layout.php';