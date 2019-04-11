<?php

$fibList = [0, 1];
$itm = null;
$i = 2;
#while ($items[$i] < 144) {$li .= "<li>{$items[$i]}</li>";
#    $items[$i]+$i;}

while ($fibList[$i-2] < 144) {
    $fibList[]=$fibList[$i-1]+$fibList[$i-2];
    print_r($fibList);
    echo "<br>";
    $i++;
}