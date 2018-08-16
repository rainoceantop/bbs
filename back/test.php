<?php
require dirname(__FILE__).'/utils/timeToHuman.php';

$t = new TimeToHuman();
echo $t->init('2018-03-16 14:36:55')->format();
