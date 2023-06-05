<?php
//$_SESSION使うよ！
session_start();
include "funcs.php";
// sschk();

//1. POSTデータ取得
$name       = filter_input( INPUT_POST, "name" );      //もう一つのPOSTの受け取り方法
$lid            = filter_input( INPUT_POST, "lid" );       //もう一つのPOSTの受け取り方法
$sex = isset($_POST['sex']) ? $_POST['sex'] : "";     //もう一つのPOSTの受け取り方法
$height  = filter_input( INPUT_POST, "height" ); 
$T_Weight  = filter_input( INPUT_POST, "T_Weight" ); 
$T_Fat  = filter_input( INPUT_POST, "T_Fat" ); 
$lpw           = filter_input( INPUT_POST, "lpw" );       //もう一つのPOSTの受け取り方法
$kanri_flg   = filter_input( INPUT_POST, "kanri_flg" ); //もう一つのPOSTの受け取り方法
$lpw          = password_hash($lpw, PASSWORD_DEFAULT);   //パスワードハッシュ化

//2. DB接続します
$pdo = db_conn();

//３．データ登録SQL作成
$sql = "INSERT INTO gs_user_table(name,lid,sex,height,T_Weight,T_Fat,lpw,kanri_flg,life_flg)VALUES(:name,:lid,:sex,:height,:T_Weight,:T_Fat,:lpw,:kanri_flg,0)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':sex', $sex, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':height', $height , PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':T_Weight', $T_Weight , PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':T_Fat', $T_Fat, PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT); //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if ($status == false) {
    sql_error($stmt);
} else {
    // セッション変数にユーザーIDを保存
    $_SESSION["lid"] = $lid; 
    redirect("login.php");
}
?>