<?php
require '../core/session.php';

$_SESSION=[]; //wipe out session
header('LOCATION: /');
