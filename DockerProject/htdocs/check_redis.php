<?php

$host = 'mysql';

$username = 'data_user';
$password = 'ppp';
$database = 'data_master';

$redisHost = 'redis';
$redisPort = 6379;

echo "--- データベースからデータを取得し、Redisに保存します ---\n";

try{
    $pdo = new PDO("mysql:host = $host; dbname = $database; charset = utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベース接続完了\n";
}catch (PDOException $e){
    die("データベース接続失敗: ".$e->getMessage()."\n");
}

try{
    $stmt = $pdo->query("SELECT student_id, student_name, class_id FROM students");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "学生データ" .count($students)."件取得\n";
} catch (PDOException $e){
    die("データ接続エラー: ".$e->getMessage(). "\n");
}

$redis = new Redis();

try{
    $redis->connect($redisHost, $redisPort);
    echo "Redisに接続した\n";
}catch(RedisException $e){
    dir("Redis接続エラー: ".$e->getMessage(). "\n");
}

$saverCount = 0;
foreach($students as $student){
    $redisKey = "student: ".$student[`student_id`];

    $redis->hMSet($redisKey, [
        `student_name` => $student[`student_name`],
        `class_id` => $student['class_id']
    ]);

    $redis->expire($redisKey, 60);

    $saverCount++;
    echo " ->RedisにKey: {$redisKey}を保存\n";
}

echo "合計{$saverCount}件のデータ保存\n";
// 接続を閉じる
$pdo = null;
$redis->close();
echo "処理が完了しました。\n";

?>