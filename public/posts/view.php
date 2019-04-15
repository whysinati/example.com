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
$content .= "<h1>{$row['title']}</h1>";
$content .= $row['body'];

#echo $content;
require '../../core/layout.php';