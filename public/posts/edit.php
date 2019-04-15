<?php
require '../../core/functions.php';
require '../../config/keys.php';
require '../../core/db_connect.php';
#require '../../core/processContactForm.php'; //make a copy and edit to meet needs of add.php
require '../../core/About/src/Validation/Validate.php'; //added this

$meta=[];
$meta['title']="Edit: " . $fields['title'];
$meta['description']="My add PHP file";
$meta['keywords']=false;

$message=null;

$args = [
    'title'=>FILTER_SANITIZE_STRING, //strips HTML
    'meta_description'=>FILTER_SANITIZE_STRING, //strips HTML
    'meta_keywords'=>FILTER_SANITIZE_STRING, //strips HTML
    'body'=>FILTER_UNSAFE_RAW //NULL FILTER
];

$input = filter_input_array(INPUT_POST, $args);

if(!empty($input)){

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
    $sql = 'INSERT INTO posts SET id=uuid(), title=?, slug=?, body=?';
    
    if($pdo->prepare($sql)->execute([
        $input['title'],
        $slug,
        $input['body']
    ])){
       header('LOCATION:/posts');
    }else{
        $message = 'Something bad happened';
    }
}

$content = <<<EOT
<h1>{$meta['title']}</h1>
{$message}
<form method="post">
<!-- <form action="add.php" method="POST"> -->
<div class="form-group">
    <label for="title">Title</label>
    <input id="title" name="title" type="text" class="form-control" value="{$valid->userInput('title')}">
    <div class="text-danger">{$valid->error('title')}</div>
</div>

<div class="form-group">
    <label for="body">Body</label>
    <textarea id="body" name="body" rows="8" class="form-control"></textarea>
</div>

<div class="row">
    <div class="form-group col-md-6">
        <label for="meta_description">Description</label>
        <textarea id="meta_description" name="meta_description" rows="2" class="form-control"></textarea>
    </div>

    <div class="form-group col-md-6">
        <label for="meta_keywords">Keywords</label>
        <textarea id="meta_keywords" name="meta_keywords" rows="2" class="form-control"></textarea>
    </div>
</div>

<div class="form-group">
    <input type="submit" value="Submit" class="btn btn-primary">
</div>
</form>

<hr>
<div>
    <a 
        onclick="return confirm('Are you sure?')"
        href="/posts/delete.php?id={$fields['id]}">
        Delete
    </a>
</div>
EOT;

include '../../core/layout.php';