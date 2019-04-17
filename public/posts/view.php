<?php
require '../../core/session.php';
include '../../core/db_connect.php';

//$slug = "'{$_GET['slug']}'";
//$slug="(SELECT slug FROM posts WHERE slug = 'hello')";

$args = [
    'slug'=>FILTER_SANITIZE_STRING,
];

$input = filter_input_array(INPUT_GET, $args);
$slug = preg_replace("/[^a-z0-9-]+/", "", $input['slug']);

$content=null;
$stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = ?');
$stmt->execute([$slug]);

$row = $stmt->fetch();

$meta=[];
$meta['title']=$row['title'];
$meta['description']="blog posts";#$row['meta-description'];
$meta['keywords']=false;

$hide=empty($_SESSION['user']['id'])?"hidden":NULL;

$content=<<<EOT
<h1>{$row['title']}</h1>
{$row['body']}

<hr>
<div {$hide} class="row">
    <div>
        <a class="btn btn-primary" href="/posts/edit.php?id={$row['id']}">
            <i class="fas fa-pen-square"></i>
            Edit
        </a>
    </div>

    <div>
        <a class="btn btn-primary" href="\posts\add.php">
            <i class="fa fa-plus" aria-hidden="true"></i>
            Add New
        </a>
    </div>
</div>

EOT;

#echo $content;
require '../../core/layout.php';