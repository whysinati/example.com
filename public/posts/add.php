<?php
require '../../core/session.php';
require '../../core/functions.php';
require '../../config/keys.php';
require '../../core/db_connect.php';
#require '../../core/processContactForm.php'; //make a copy and edit to meet needs of add.php
require '../../core/About/src/Validation/Validate.php'; //added this

checkSession();

use About\Validation;

$valid = new About\Validation\Validate();

$meta=[];
$meta['title']="Add a blog post";
$meta['description']="A form to add blog posts";
$meta['keywords']=false;

$message=null;

$args = [
    'title'=>FILTER_SANITIZE_STRING, //strips HTML
    'meta_description'=>FILTER_SANITIZE_STRING, //strips HTML
    'meta_keywords'=>FILTER_SANITIZE_STRING, //strips HTML
    'body'=>FILTER_UNSAFE_RAW //NULL FILTER
];

$input = filter_input_array(INPUT_POST, $args);

//1. First validate

if(!empty($input)){

    $valid->validation = [
        'title'=>[[
                'rule'=>'notEmpty',
                'message'=>'Please enter a title'
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

    /*
        $slug = preg_replace(
            "/[^a-z0-9-]+/",
            "-",
            strtolower($input['title'])
        );
    */
        //Sanitized insert
        #$sql = 'INSERT INTO posts SET id=uuid(), title=?, slug=?, body=?';
        $sql = 'INSERT INTO posts SET id=uuid(), title=:title, slug=:slug, body=:body';
    
        if($pdo->prepare($sql)->execute([
            $input['title'],
            $slug,
            $input['body']
        ])){
        header('LOCATION:/posts');
        }else{
            $message = 'Something bad happened';
        }
}   else{
        $message = "<div class=\"alert alert-danger\">Your form has errors!</div>";
    }
}

$content = <<<EOT
<h1>Add a New Post</h1>
{$message}
<form method="post">

<div class="form-group">
    <label for="title">Title</label>
    <input id="title" name="title" type="text" class="form-control" value="{$valid->userInput('title')}">
    <div class="text-danger">{$valid->error('title')}</div>
</div>

<div class="form-group">
    <label for="body">Body</label>
    <textarea id="body" name="body" rows="8" class="form-control">{$valid->userInput('body')}</textarea>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="meta_description">Description</label>
        <textarea id="meta_description" name="meta_description" rows="2" class="form-control">{$valid->userInput('meta_description')}</textarea>
    </div>

    <div class="form-group col-md-6">
        <label for="meta_keywords">Keywords</label>
        <textarea id="meta_keywords" name="meta_keywords" rows="2" class="form-control">{$valid->userInput('meta_keywords')}</textarea>
    </div>
</div>

<div class="form-group">
    <input type="submit" value="Submit" class="btn btn-primary">
</div>
</form>
EOT;

include '../../core/layout.php';