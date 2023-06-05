<?php
//$_SESSION使うよ！
session_start();

//１．関数群の読み込み
include_once "funcs.php";

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

// ログインユーザーのIDを取得
if (isset($_SESSION["lid"])) {
  $lid = $_SESSION["lid"];
} else {
  exit("ログインユーザーのIDが取得できませんでした。");
}

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

//2. DB接続します
$pdo = db_conn();

// 4. ユーザーの身長を取得
$sql = "SELECT height FROM gs_user_table WHERE lid = :lid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

$height = "";
if ($status == false) {
  sql_error($stmt);
} else {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $height = $row['height'];
  }
}

//5．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO gs_bm_table(weight,fat,bmi,food,cond,indate,lid)VALUES(:weight, :fat,:bmi,:food, :cond,sysdate(),:lid);");
$stmt->bindValue(':weight', $weight,                                    PDO::PARAM_STR);
$stmt->bindValue(':fat',       $fat,                                          PDO::PARAM_STR); 
$stmt->bindValue(':bmi',     calculateBMI($weight, $height),  PDO::PARAM_STR); 
$stmt->bindValue(':food',    $food,                                       PDO::PARAM_INT); 
$stmt->bindValue(':cond',   $cond,                                        PDO::PARAM_INT); 
$stmt->bindValue(':lid',       $lid,                                            PDO::PARAM_STR); 
$status = $stmt->execute(); //実行

//４．データ登録処理後
if($status==false){
  sql_error($stmt); //関数sql_errorを実行
}else{
  redirect("select.php");
}
?>
