<?php
//0. SESSION開始！！
session_start();
include_once "funcs.php";
// sschk();

//1．データベース接続
$pdo = db_conn();

//2. gs_user_tableからkanri_flgが0のメンバーを取得
$sql = "SELECT * FROM gs_user_table WHERE kanri_flg = 0";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//3. データ取得・表示
if ($status == false) {
    sql_error($stmt);
} 

?>
    

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>メンバーリスト表示</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
  <!-- Chart.jsの読み込み -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="memberlist.php">メンバー選択と進捗状況</a>
      <a class="navbar-brand" href="logout.php">LOGOUT</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->


<!-- Main[Start] -->
<div>
<div><?=$_SESSION["name"]?>さん、こんにちは　メンバーを選んで進捗を確認してください</div>
    <div class="container jumbotron">
      <table>
      <tr style="color: #dc4813;">
        <th>メンバー</th>
        <th>　　目標<br>　　体重</th>
        <th>　　現在の<br>　　体重</th>
        <th>　　現在の<br>　　BMI</th>
        <th>　　目標<br>　　体脂肪率</th>
        <th>　　現在の<br>　　体脂肪率</th>
        <th>　　コメント</th>
      </tr>
      <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $memberName = $row['name'];
                    $T_Weight = $row['T_Weight'];
                    $T_Fat = $row['T_Fat'];
                    $lid = $row['lid'];

                    // 最新の体重と体脂肪率の取得
                    $sql_bm = "SELECT * FROM gs_bm_table WHERE lid = :lid ORDER BY indate DESC LIMIT 1";
                    $stmt_bm = $pdo->prepare($sql_bm);
                    $stmt_bm->bindValue(':lid', $lid, PDO::PARAM_STR);
                    $stmt_bm->execute();
                    $row_bm = $stmt_bm->fetch(PDO::FETCH_ASSOC);
                    $weight = ($row_bm) ? $row_bm['weight'] : 'データなし';
                    $fat = ($row_bm) ? $row_bm['fat'] : 'データなし';
                    $BMI = ($row_bm) ? $row_bm['BMI'] : 'データなし';

                    echo '<tr>';
                    echo '<td>' . '<a href="select.php?lid=' .  $lid  . '">' . $memberName . '</a>'. '</td>';
                    echo '<td>' .'　　'. $T_Weight . '</td>';
                    echo '<td>' .'　　'. $weight . '</td>';
                    echo '<td>' .'　　'. $BMI . '</td>';
                    echo '<td>' .'　　'.$T_Fat . '</td>';
                    echo '<td>' .'　　'.$fat . '</td>';
                    // コメント入力フォームと送信ボタンの表示
                    echo '<td>'.'<form method="post" action="coment_insert.php" style="display: flex; gap:10px; margin-left: 20px; margin-bottom: 10px;"><input type="hidden" name="lid" style="border-radius: 5px; " "value="' . $lid . '"><textArea name="coment" rows="1" cols="40"></textArea><input type="submit" value="送信"></form>'.'</td>';
                    echo '<td>' .'　　'. '<a href="chat.php?lid=' .  $lid  . '">' .'CHAT'. '</a>'.'</td>';
                    echo '</tr>';
                }
         ?>
      </table>
    </div>
</div>

</body>
</html>

