<?php
require '../../core/functions.php';
require '../../config/keys.php';
require '../../core/db_connect.php';
require '../../core/About/src/Validation/Validate.php';

use About\Validation;

$valid = new About\Validation\Validate();


$meta=[];

#$meta['description']="My edit my blog file";
#$meta['keywords']=false;

$message=null;

$args = [
    'id'=>FILTER_SANITIZE_STRING, //strips HTML
    'email'=>FILTER_SANITIZE_EMAIL,
    'first_name'=>FILTER_SANITIZE_STRING, //strips HTML
    'last_name'=>FILTER_SANITIZE_STRING, //strips HTML
    #'body'=>FILTER_UNSAFE_RAW //NULL FILTER
];

$input = filter_input_array(INPUT_POST, $args);

//1. First validate
if(!empty($input)){

    $valid->$validation = [
        'email'=>[[
            'rule'=>'email',
            'message'=>'Please enter a valid email.'
        ],[
            'rule'=>'notEmpty',
            'message'=>'Please enter your email.'
        ]],
        'first_name'=>[[
            'rule'=>'notEmpty',
            'first_name'=>'Please enter your first name.'
        ]],
        'last_name'=>[[
            'rule'=>'notEmpty',
            'last_name'=>'Please enter your last name.'
        ]]
    ];

    $valid->check($input);

    if(empty($valid->errors)){
        //2. Only process if we pass validation

        //Strip white space, begining and end
        $input = array_map('trim', $input);

        //Allow only whitelisted HTML
        #$input['last_name'] = cleanHTML($input['last_name']);

        //Create the slug
        #$slug = slug($input['email']);

        //Sanitized insert
        $sql = 'UPDATE users SET email=:email, last_name=:last_name, first_name=:first_name WHERE id=:id';
        
        if($pdo->prepare($sql)->execute([
            'id'=>$input['id'],
            'email'=>$input['email'],
            #'slug'=>$slug,
            'first_name'=>$input['first_name'],
            'last_name'=>$input['last_name'],
            #'meta_keywords'=>$input['meta_keywords']
        ])){
            header('LOCATION:/users');
        }else{
            $message = 'Something bad happened';
        }
    }else{
        //3. If validation fails, create a message, DO NOT forget to add the validation
        //methods to the form.
        $message = "<div class=\alert alert-danger\">Your form has errors!</div>";
    }
}

/* Preload the page */

$args = [
    'id'=>FILTER_SANITIZE_STRING, //strips HTML
];

$getParams = filter_input_array(INPUT_GET, $args);

$sql = 'SELECT * FROM users WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id'=>$getParams['id']
]);

$row = $stmt->fetch();

$fields=[];
$fields['id']=$row['id'];
$fields['email']=$row['email'];
$fields['first_name']=$row['first_name'];
$fields['last_name']=$row['last_name'];
#$fields['meta_keywords']=$row['meta_keywords'];


if(!empty($input)){
    #$fields['id']=$valid->userInput('id');
    $fields['email']=$valid->userInput('email');
    $fields['first_name']=$valid->userInput('first_name');
    $fields['last_name']=$valid->userInput('last_name');
    #$fields['meta_keywords']=$valid->userInput('meta_keywords');
}

$meta['title']='Edit:' .$fields['email'];
#$meta['title']="Edit: " . $fields['title'];

$content = <<<EOT
<h1>{$meta['title']}</h1>
{$message}
<form method="post">

<input name="id" type="hidden" class="form-control" value="{$fields['id']}">

<div class="form-group">
    <label for="email">Email</label>
    <input id="email" name="email" type="text" class="form-control" value="{$fields['email']}">
    <div class="text-danger">{$valid->error('email')}</div>
</div>

<div class="form-group">
    <label for="first_name">First Name</label>
    <textarea id="first_name" name="first_name" rows="1" class="form-control">{$fields['first_name']}</textarea>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="last_name">Last Name</label>
        <textarea id="last_name" name="last_name" rows="1" class="form-control">{$fields['last_name']}</textarea>
    </div>

</div>

<div class="form-group">
    <input type="submit" value="Submit" class="btn btn-primary">
</div>


<hr>
<div>
    <a
        class="text-danger" 
        onclick="return confirm('Are you sure?')"
        href="/users/delete.php?id={$fields['id']}">
        <i class="fas fa-trash-alt"></i>
        Delete
    </a>
</div>
</form>
EOT;

include '../../core/layout.php';