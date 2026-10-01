<?php

$host = 'mysql';

$username = 'data_user';
$password = 'ppp';
$database = 'data_master';

$redisHost = 'redis';
$redisPort = 6379;

$redisKey = 'all_students_data';

$redis = new Redis();


$redis->connect($redisHost, $redisPort);

$redis->hMSet($redisKey)



?>