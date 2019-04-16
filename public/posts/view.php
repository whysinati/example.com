<?php
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

$content=<<<EOT
<h1>{$row['title']}</h1>
{$row['body']}

<hr>
<div>
    <a href="/posts/edit.php?id={$row['id']}">Edit</a>
</div>
EOT;

#echo $content;
require '../../core/layout.php';