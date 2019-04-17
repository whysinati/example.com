<?php
require '../core/session.php';

$meta=[];
$meta['title']='Thank you!';
$meta['description']='form submit';

$content=<<<EOT

<p>Thank you for sending an email. I will reply shortly.</p>
       
EOT;

require '../core/layout.php';