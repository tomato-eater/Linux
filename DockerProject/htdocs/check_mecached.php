<?php

$host = 'mysql';

$username = 'data_user';
$password = 'ppp';
$database = 'data_master';

$memcachedHost = 'memcached_cache';
$memcachedPort = 11211;

$cacheKey = 'all_students_data';
$expireSeconds = 60;

$memcached = new Memcached();
$memcached->addServer($memcachedHost, $memcachedPort);

echo "<h1> MySQL Data Caching with Memcached</h1>";

$cacheData = $memcached->get($cacheKey);

if($cacheData){
    echo "<p>Data retrieved from Memcached (cache hit!).</p>";
    echo "<pre>";
    print_r(json_decode($cacheData, true));
    echo "</pre>";
}else{
    echo "<p>Data not found in Memcached (cache miss). Retrieving from MySQL...</p>";

    try{
        $pdo = new PDO("mysql:host = $host; dname = $database; charset = utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPRION);

        $stmt = $pdo->query("SELECT * FROM students");

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $memcached->set($cacheKey, json_encode($results), $expireSeconds);

        echo "<p>Data retrieved from MySQL and saved to Memcached.</p>";
        echo "<pre>";
        print_r($results);
        echo "</pre>";

    } catch (PDOException $e) {
        echo "<p>Error connecting to MySQL or fetching data: " . $e->getMessage() . "</p>";
    }

    echo "<p>Current time: " . date('Y-m-d H:i:s') . "</p>";
    echo "<p><a href = '?clear_cache=1'>Clear Cache (and reload to see cache miss)</a></p>";

    // キャッシュ削除機能
    if (isset($_GET['clear_cache']) && $_GET['clear_cache'] == 1) {
        if ($memcached->delete($cacheKey)) {
            echo "<p>Cache for '$cacheKey' cleared!</p>";
        } else {
            echo "<p>Failed to clear cache for '$cacheKey'.</p>";
        }
    }

}

?>