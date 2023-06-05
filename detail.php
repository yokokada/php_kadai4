<?php
//１．PHP
//select.phpのPHPコードをマルっとコピーしてきます。

// GETでidを取ってくる
$id = $_GET["id"];

// 以下select.phpのコピー
//1.  DB接続します
include("funcs.php");
$pdo = db_conn();

//２．データ登録SQL作成
$sql = "SELECT*FROM gs_bm_table WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id , PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
  sql_error($stmt);
}

//4. 1データ取得
$v = $stmt->fetch();

// 以下は全データなのでコメントアウト
// $values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
// $json = json_encode($values,JSON_UNESCAPED_UNICODE);
?>


<!--
２．HTML
以下にindex.phpのHTMLをまるっと貼り付ける！
理由：入力項目は「登録/更新」はほぼ同じになるからです。
※form要素 input type="hidden" name="id" を１項目追加（非表示項目）
※form要素 action="update.php"に変更
※input要素 value="ここに変数埋め込み"
-->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>データ編集</title>
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
<form method="post" action="update.php">
  <div class="jumbotron">
   <fieldset>
    <legend>編集画面</legend>
     <label>体重：　　　<input type="text" name="weight" value="<?=$v["weight"]?>"></label><br>
     <label>体脂肪率：　<input type="text" name="fat" value="<?=$v["fat"]?>"></label><br>
     <label>前日食事量：<input type="radio" name="food" value="1" <?php if ($v["food"] == "1") echo "checked"; ?>>極少
     <input type="radio" name="food" value="2"<?php if ($v["food"] == "2") echo "checked"; ?>>少
		<input type="radio" name="food" value="3" <?php if ($v["food"] == " 3") echo "checked"; ?>>普通
		<input type="radio" name="food" value="4" <?php if ($v["food"] == "4") echo "checked"; ?>>少し多め
		<input type="radio" name="food" value="5" <?php if ($v["food"] == "5") echo "checked"; ?>>多め
    </label><br>
     <label>体調：　　　<input type="radio" name="cond" value="1" <?php if ($v["food"] == "1") echo "checked"; ?>>悪い
		<input type="radio" name="cond" value="2" <?php if ($v["food"] == "2") echo "checked"; ?>>少し悪い
		<input type="radio" name="cond" value="3" <?php if ($v["food"] == "3") echo "checked"; ?>>普通
		<input type="radio" name="cond" value="4" <?php if ($v["food"] == "4") echo "checked"; ?>>まずまず良い
		<input type="radio" name="cond" value="5" <?php if ($v["food"] == "5") echo "checked"; ?>>良い
    </label><br>
    <input type="hidden" name="id" value="<?=$v["id"]?>">
     <input type="submit" value="登録し直す" id="sendbtn">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->

</body>
</html>





