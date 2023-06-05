<?php
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}

//DB接続関数：db_conn()
function db_conn(){
    try {
        $db_name = "p_k2";       //データベース名
        $db_id   = "root";           //アカウント名
        $db_pw   = "";               //パスワード：XAMPPはパスワード無し
        $db_host = "localhost"; //DBホスト
        return  new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:'.$e->getMessage());
    }
 }

//SQLエラー関数：sql_error($stmt)
  //*** function化する！*****************
  function sql_error($stmt){
    $error = $stmt->errorInfo();
    exit("SQLError:".$error[2]);
}

//リダイレクト関数: redirect($file_name)
 //*** function化する！*****************
 function redirect($page){
    header("Location: ".$page);
    exit();
}

//SessionCheck(スケルトン)
function sschk(){
    if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]!=session_id()){
      exit("Login Error");
    }else{
      session_regenerate_id(true);
      $_SESSION["chk_ssid"] = session_id();
    }
  }

  // 食事量のバリューを文字に直す関数
  function FoodV($value) {
    switch ($value) {
      case 1: return '極小';
      case 2: return '少なめ';
      case 3: return '普通';
      case 4: return '少し多め';
      case 3: return '多め';
      default: return '';
    }
  }
  // 体調のバリューを文字に直す関数
  function CondV($value) {
    switch ($value) {
      case 1: return '悪い';
      case 2: return '少し悪い';
      case 3: return '普通';
      case 4: return '良い';
      case 3: return 'とても良い';
      default: return '';
    }
  }

  // 関数: BMIの計算
function calculateBMI($weight, $height)
{
  if ($weight !== "" && $height !== "") {
    $heightInMeter = $height / 100; // cmをmに変換
    $bmi = $weight / ($heightInMeter * $heightInMeter);
    return round($bmi, 1); // BMIを小数点以下1桁で丸める
  } else {
    return ""; // 体重または身長が未入力の場合は空文字を返す
  }
}

// 管理者とメンバーのページ表示の切替
function getLid() {
  if ($_SESSION["kanri_flg"] == 1) {
      return isset($_GET['lid']) ? $_GET['lid'] : "";
  } else {
      return isset($_SESSION['lid']) ? $_SESSION['lid'] : "";
  }
}





