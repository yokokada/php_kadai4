<?php
session_start();

//１．関数群の読み込み
include_once "funcs.php";

//LOGINチェック → funcs.phpへ関数化しましょう！
sschk();

//２．データ登録SQL作成
$pdo = db_conn();
if (isset($_SESSION["lid"])) {
  $lid = $_SESSION["lid"];
} else {
  exit("ログインユーザーのIDが取得できませんでした。");
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>データ登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="select.php">データ一覧</a></div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="post" action="insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>フリーアンケート</legend>
     <label>体重：　　　<input type="text" name="weight"></label><br>
     <label>体脂肪率：　<input type="text" name="fat"></label><br>
     <label>前日食事量：<input type="radio" name="food" value="1">極少
     <input type="radio" name="food" value="2">少
		<input type="radio" name="food" value="3">普通
		<input type="radio" name="food" value="4">少し多め
		<input type="radio" name="food" value="5">多め
    </label><br>
     <label>体調：　　　<input type="radio" name="cond" value="1">悪い
		<input type="radio" name="cond" value="2">少し悪い
		<input type="radio" name="cond" value="3">普通
		<input type="radio" name="cond" value="4">良い
		<input type="radio" name="cond" value="5">とても良い
    </label><br>
    <label>ログインID：<input type="hidden" name="lid"></label>

     <input type="submit" value="送信" id="sendbtn">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->

</body>
</html>

