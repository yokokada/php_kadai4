<?php
//PHP:コード記述/修正の流れ
//1. insert.phpの処理をマルっとコピー。
//   POSTデータ受信 → DB接続 → SQL実行 → 前ページへ戻る
//2. $id = POST["id"]を追加
//3. SQL修正
//   "UPDATE テーブル名 SET 変更したいカラムを並べる WHERE 条件"
//   bindValueにも「id」の項目を追加
//4. header関数"Location"を「select.php」に変更

//1. POSTデータ取得
$weight = $_POST["weight"];
if (empty($weight)) {
  $weight = 0.0; // デフォルトの値を設定する（例として 0.0 を使用）
}
$fat = $_POST["fat"];
if (empty($fat)) {
  $fat = 0.0; // デフォルトの値を設定する（例として 0.0 を使用）
}
$food = $_POST["food"];
if (empty($food)) {
  $food = 0.0; // デフォルトの値を設定する（例として 0.0 を使用）
}
$cond = $_POST["cond"];
if (empty($cond)) {
  $cond = 0.0; // デフォルトの値を設定する（例として 0.0 を使用）
}
$id    = $_POST["id"]; //id追加

//2. DB接続します
include("funcs.php");
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "UPDATE gs_bm_table SET weight =:weight, fat=:fat, food=:food, cond=:cond  WHERE id =:id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':weight', $weight,    PDO::PARAM_STR);//Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':fat', $fat,     PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':food', $food,          PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':cond', $cond, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':id',         $id,         PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  sql_error($stmt); //関数sql_errorを実行
}else{
  redirect("select.php");
}

?>
