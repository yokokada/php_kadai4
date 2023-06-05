<?php
session_start();
include "funcs.php";
// sschk();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>USERデータ登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
      <div class="navbar-header">
      <p class="navbar-brand">ユーザー登録</p>
      </div>
    </div>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<form method="post" action="user_insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>ユーザー登録</legend>
     <label>名前：<input type="text" name="name"></label><br>
     <label>Login ID：<input type="text" name="lid"></label><br>
     <label>Login PW：<input type="text" name="lpw"></label><br>
     <label>性別：
      男性<input type="radio" name="sex" value="男性">
      　女性<input type="radio" name="sex" value="女性">
      　どちらでもない<input type="radio" name="sex" value="どちらでもない">
    </label><br>
     <input type="hidden" name="kanri_flg" value="0"> <!-- 一般の値（0）を送信 -->
     <label>身長：<input type="text" name="height"></label>cm<br>
     <label>目標体重：<input type="text" name="T_Weight"></label>kg<br>
     <label>目標体脂肪率：<input type="text" name="T_Fat"></label>％<br>
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>
