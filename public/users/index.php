<?php
require '../../core/session.php';
require '../../core/db_connect.php';

checkSession();

$content=null;
$stmt = $pdo->query("SELECT * FROM users");

$meta=[];
$meta['title']="Users";

$items=null;

$content="<h1>My Blog</h1>";
while($row = $stmt->fetch()){
    #var_dump($row);
    $items.=
        "<a href=\"view.php?id={$row['id']}\" class=\"list-group-item\">".
        "{$row['last_name']}, {$row['first_name']}</a>";
}

$content=<<<EOT
<h1>Users</h1>
<div class=\"list-group\">{$items}</div>
<hr>
<div>
    <a class="btn btn-primary" href="\users\add.php">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Add
    </a>
</div>

EOT;

require '../../core/layout.php';
