<?php

require '../bootstrap.php';

//$_SESSION=[]; //

//$_SESSION['user'] = [];
//$_SESSION['user']['id'] = 12345; //

$meta=[];
$meta['title']='Christa';
$meta['description']='Christa\'s PHP page';



$content=<<<EOT
            <h1>Hello, this is my PHP homepage.</h1>
            <img class="avatar" 
            src="PipeCleanerArtSm.jpg" alt="Creative with pipe cleaners">
            <p>Under construction here. Check out my resume link. 
            </p>  
EOT;

require '../core/layout.php';