<?php
require '../../bootstrap.php';
include '../../core/db_connect.php';

checkSession();

$args = [
    'id'=>FILTER_SANITIZE_STRING
];

$input = filter_input_array(INPUT_GET, $args);

$stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');

if($stmt->execute(['id'=>$input['id']])){

    header('LOCATION: /users'); //the "LOCATION" header redirects to the users page
}
#echo $content;
#require '../../core/layout.php'; //we never actually stop on this page, so not needed