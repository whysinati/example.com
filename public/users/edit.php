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
    'title'=>FILTER_SANITIZE_STRING, //strips HTML
    'meta_description'=>FILTER_SANITIZE_STRING, //strips HTML
    'meta_keywords'=>FILTER_SANITIZE_STRING, //strips HTML
    'body'=>FILTER_UNSAFE_RAW //NULL FILTER
];

$input = filter_input_array(INPUT_POST, $args);

//1. First validate
if(!empty($input)){

    $valid->$validation = [
        'title'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter a title.'
        ]],
        'body'=>[[
            'rule'=>'notEmpty',
            'body'=>'Please enter blog text.'
        ]]
    ];

    $valid->check($input);

    if(empty($valid->errors)){
        //2. Only process if we pass validation

        //Strip white space, begining and end
        $input = array_map('trim', $input);

        //Allow only whitelisted HTML
        $input['body'] = cleanHTML($input['body']);

        //Create the slug
        $slug = slug($input['title']);

        //Sanitized insert
        $sql = 'UPDATE users SET title=:title, slug=:slug, body=:body, meta_description=:meta_description, meta_keywords=:meta_keywords, WHERE id=:id';
        
        if($pdo->prepare($sql)->execute([
            'id'=>$input['id'],
            'title'=>$input['title'],
            'slug'=>$slug,
            'body'=>$input['body'],
            'meta_description'=>$input['meta_description'],
            'meta_keywords'=>$input['meta_keywords']
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
$fields['title']=$row['title'];
$fields['body']=$row['body'];
$fields['meta_description']=$row['meta_description'];
$fields['meta_keywords']=$row['meta_keywords'];

$meta['title']="Edit: " . $fields['title'];

if(!empty($input)){
    $fields['id']=$valid->userInput('id');
    $fields['title']=$valid->userInput('title');
    $fields['body']=$valid->userInput('body');
    $fields['meta_description']=$valid->userInput('meta_description');
    $fields['meta_keywords']=$valid->userInput('meta_keywords');
}



$content = <<<EOT
<h1>{$meta['title']}</h1>
{$message}
<form method="post">

<input name="id" type="hidden" class="form-control" value="{$fields['id']}">

<div class="form-group">
    <label for="title">Title</label>
    <input id="title" name="title" type="text" class="form-control" value="{$fields['title']}">
    <div class="text-danger">{$valid->error('title')}</div>
</div>

<div class="form-group">
    <label for="body">Body</label>
    <textarea id="body" name="body" rows="8" class="form-control">{$fields['body']}</textarea>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="meta_description">Description</label>
        <textarea id="meta_description" name="meta_description" rows="2" class="form-control">{$fields['meta_description']}</textarea>
    </div>

    <div class="form-group col-md-6">
        <label for="meta_keywords">Keywords</label>
        <textarea id="meta_keywords" name="meta_keywords" rows="2" class="form-control">{$fields['meta_keywords']}</textarea>
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