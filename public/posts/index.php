<?php

require '../../core/db_connect.php';

$content=null;
$stmt = $pdo->query("SELECT * FROM posts");

$meta=[];
$meta['title']="My Blog";

$content="<h1>My Blog</h1>";
while($row = $stmt->fetch()){
#    var_dump($row);
    $content.="<a href=\"view.php?slug={$row['slug']}\">"
        . "{$row['title']}</a>";
}

#echo $content;
require '../../core/layout.php';
