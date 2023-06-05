<?php
//0. SESSION開始！！
session_start();

//１．関数群の読み込み
include_once "funcs.php";

//LOGINチェック → funcs.phpへ関数化しましょう！
// sschk();

//２．データ登録SQL作成
$pdo = db_conn();

// 管理者であるかどうか判定しセッション切替
$isAdmin = ($_SESSION["kanri_flg"]==1);
// ユーザー情報の取得方法の切り替え
if ($isAdmin) {
  // 管理者であればGETパラメーターから取得
  $lid = isset($_GET['lid']) ? $_GET['lid'] : "";
} 
else {
  // 管理者でなければセッションから取得
  $lid = isset($_SESSION['lid']) ? $_SESSION['lid'] : "";
}

// ログインユーザーの目標体重と目標体脂肪率をgs_user_tableから取得
$sql = "SELECT T_Weight, T_Fat, height FROM gs_user_table WHERE lid = :lid";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

$T_Weight = "";
$T_Fat = "";
$height = "";


if ($status == false) {
  sql_error($stmt);
} else {
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $T_Weight = $row['T_Weight'];
    $T_Fat = $row['T_Fat'];
    $height = $row['height'];
  }
}

//３．ログインユーザーの過去のデータをgs_bm_table から取得
$values = array();
if (!empty($lid)) {
  $sql = "SELECT * FROM gs_bm_table WHERE lid = :lid";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
  $status = $stmt->execute();

if($status==false) {
  sql_error($stmt);
}else {
  // 全データ取得
  $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $json = json_encode($values, JSON_UNESCAPED_UNICODE);
}
}
// //全データ取得
// $values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
// //JSONい値を渡す場合に使う
// $json = json_encode($values,JSON_UNESCAPED_UNICODE);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>フリーアンケート表示</title>
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
      <a class="navbar-brand" href="index2.php">データ入力</a>
      <a class="navbar-brand" href=<?="chat.php?lid=".$lid?>>コメント</a>
      <a class="navbar-brand" href="logout.php">LOGOUT</a>
      </div>
    
    </div>
  </nav>
</header>
<!-- Head[End] -->


<!-- Main[Start] -->
<div>
<div><?=$lid?>さん、こんにちは</div>
<div>あなたの目標体重は　<?=$T_Weight?>kg、目標体脂肪率は　<?=$T_Fat?>％です</div>
<div>あなたの身長は　<?=$height?>cm、現在の体重は　
<?php
    $currentWeight = "未入力"; // 初期値を設定
    $BMI = "未入力"; // 初期値を設定
    foreach($values as $v) {
        if (!empty($v["weight"])) {
            $currentWeight = $v["weight"];
            $BMI = $v["BMI"];
        }
    }
    ?>
    <?=$currentWeight?>kg、現在のBMIは　<?=$BMI?>です
  </div>

    <div class="container jumbotron">
      <table>
      <tr style="color: #dc4813;">
        <th>　日時</th>
        <th>　体重</th>
        <th>　体脂肪率</th>
        <th>　前日食事量</th>
        <th>　体調</th>
        <th>　</th>
        <th>　</th>
      </tr>
      <?php foreach($values as $v){?>
        <tr>
          <td>　<?=$v["indate"]?>　</td>
          <td>　<?=$v["weight"]?>kg　</td>
          <td>　<?=$v["fat"]?>%　</td>
          <td>　<?=FoodV($v["food"])?></td>
          <td>　<?=CondV($v["cond"])?>　　</td>
          <td> <a href="detail.php?id=<?=h($v["id"])?>"> 　　[編集] </a></td>
          <td> <a href="delete.php?id=<?=h($v["id"])?>"> 　[削除]</a></td>
        </tr>
      <?php }?>
      </table>
    </div>
</div>
<!-- 折れ線グラフの表示エリア -->
<div>
  <canvas id="myChart"></canvas>
</div>
<a href="index.php">入力画面に戻る</a>
<!-- Main[End] -->

<script>
  //JSON受け取り
  const json = JSON.parse('<?=$json?>');
  console.log(json);

  // テーブルのデータを配列に変換
  const chartData = <?= $json ?>;
  
  // ラベルとデータの配列を作成
  const labels = chartData.map(data => data.indate);
  const weightData = chartData.map(data => data.weight);
  const fatData = chartData.map(data => data.fat);
  const foodData = chartData.map(data => data.food * 10);
  const condData = chartData.map(data => data.cond * 10);

  // グラフの描画
  const ctx = document.getElementById('myChart').getContext('2d');
  const chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: '体重 (kg)',
          data: weightData,
          borderColor: 'rgba(75, 192, 192, 1)',
          fill: false
        },
        {
          label: '体脂肪率 (%)',
          data: fatData,
          borderColor: 'rgba(255, 99, 132, 1)',
          fill: false
        },
        {
          label: '前日食事量',
          data: foodData,
          borderColor: 'rgba(54, 162, 235, 1)',
          fill: false
        },
        {
          label: '体調',
          data: condData,
          borderColor: 'rgba(255, 206, 86, 1)',
          fill: false
        }
      ]
    },
    options: {
      scales: {
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 70,
                        stepSize: 5
                    }
                }
              }
        });
</script>
</body>
</html>

