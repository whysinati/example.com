<?php
require '../../core/bootstrap.php';
include '../../core/db_connect.php';

checkSession();

$args = [
    'id'=>FILTER_SANITIZE_STRING
];

$input = filter_input_array(INPUT_GET, $args);
$slug = preg_replace("/[^a-z0-9-]+/", "", $input['id']);

#$content=null;
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$slug]);

$row = $stmt->fetch();

$meta=[];
$meta['title']=$row['first_name'];
$meta['description']=$row['email'];
$meta['keywords']=$row['last_name'];

$content=<<<EOT
<h1>{$row['first_name']} {$row['last_name']}</h1>
{$row['email']}

<hr>
<div>
    <a href="/users/edit.php?id={$row['id']}">Edit</a>
</div>
EOT;

#echo $content;
require '../../core/layout.php';