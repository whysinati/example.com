<?php

require '../core/session.php';

//$_SESSION=[]; //

//$_SESSION['user'] = [];
//$_SESSION['user']['id'] = 12345; //

$meta=[];
$meta['title']='Bob Smith';
$meta['description']='Bob page';



$content=<<<EOT
            <h1>Hello, I am Zyris Shakir</h1>
            <img class="avatar" 
            src="https://zeyeland.com/images/major.gif" alt="My Robot">
            <p>What's up! I love to build robots. Actually my best-friend
                is a robot named Clovis. Would you like to meet him? Nah your
                not cool enough. You'd have to beat me in a dance off first.
            </p>  
EOT;

require '../core/layout.php';