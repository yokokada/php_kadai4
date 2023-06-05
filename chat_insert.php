<?php
//$_SESSION使うよ！
session_start();

//１．関数群の読み込み
include_once "funcs.php";

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

//1. POSTデータ取得
if (isset($_POST["lid"])) {
    $lid = $_POST["lid"];
} else {
    exit("ログインユーザーのIDが取得できませんでした。");
}

$coment = $_POST["coment"];
if (empty($coment)) {
  $coment = " ";
}

//2. DB接続します
$pdo = db_conn();

//5．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO chat_table (coment, indate, lid) VALUES (:coment, sysdate(), :lid);");
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR); 
$stmt->bindValue(':coment', $coment, PDO::PARAM_STR);
$status = $stmt->execute(); //実行

// コメント送信できたか確認
if ($status) {
    // コメントの保存が成功した場合の処理（任意のメッセージなど）
    echo "コメントが送信されました。";
} else {
    // コメントの保存が失敗した場合の処理（任意のエラーメッセージなど）
    echo "コメントの送信に失敗しました。";
}

//４．データ登録処理後
if ($status == false) {
    sql_error($stmt); //関数sql_errorを実行
} else {
    redirect("chat.php?lid=$lid");
}
?>
