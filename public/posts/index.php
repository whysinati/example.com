<?php
require '../../core/session.php';
require '../../core/db_connect.php';

$content=null;
$stmt = $pdo->query("SELECT * FROM posts");

$meta=[];
$meta['title']="My Blog";

$items=null;

while($row = $stmt->fetch()){
#    var_dump($row);
    $items.=
        "<a href=\"view.php?slug={$row['slug']}\" class=\"list-group-item\">".
        "{$row['title']}</a>";
}

$hide=empty($_SESSION['user']['id'])?"hidden":NULL;

$content=<<<EOT
<h1>My Blog</h1>
<div class=\"list-group\">{$items}</div>
<hr>
<div>
    <a {$hide} class="btn btn-primary" href="\posts\add.php">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Add
    </a>
</div>

EOT;

require '../../core/layout.php';
