<?php
include '../../core/db_connect.php';

//$slug = "'{$_GET['slug']}'";
//$slug="(SELECT slug FROM posts WHERE slug = 'hello')";

$args = [
    'id'=>FILTER_SANITIZE_STRING
];

$input = filter_input_array(INPUT_GET, $args);

$stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');

if($stmt->execute(['id'=>$input['id']])){

    header('LOCATION: /posts'); //the "LOCATION" header redirects to the posts page
}
#echo $content;
#require '../../core/layout.php'; //we never actually stop on this page, so not needed