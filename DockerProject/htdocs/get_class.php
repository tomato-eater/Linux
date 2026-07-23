<?php
// データベースへ接続するために必要な情報
// ホストはDBコンテナ
$host = 'mysql';
// mysql接続用のユーザー
$username = 'data_user';
$password = 'data';
$database = 'data_master';

try{
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);

    //URLパラメーターを受け取る
    $id = 0;
    if(isset($_GET['class_id'])){
        $id = $_GET['class_id'];
    }

    //SQLクエリ
    if($id){
        $sql = "SELECT * FROM classes where class_id = ".$id;
    }
    else{
        $sql = "SELECT * FROM classes";
    }
    
    // クエリの実行
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
    // 取得したデータをセッションに保存
    session_start();
    $_SESSION['data'] = $results;

    // リダイレクト
    header("Location: display_class.php");
    exit();
}
catch(PDOException $e){
    // エラー処理
    echo "データベースエラー: " . $e->getMessage();
}

?>
