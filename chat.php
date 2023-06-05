<?php
// 0. SESSION開始！！
session_start();

// １．関数群の読み込み
include_once "funcs.php";

// LOGINチェック → funcs.phpへ関数化しましょう！
// sschk();

// ２．データ登録SQL作成
$pdo = db_conn();

// 管理者であるかどうか判定しセッション切替
$isAdmin = ($_SESSION["kanri_flg"] == 1);
// ユーザー情報の取得方法の切り替え
$lid = getLid();

// ３．管理者のコメントを取得
$stmt = $pdo->prepare("SELECT * FROM manager_coments WHERE lid = :lid ORDER BY indate DESC");
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->execute();
$managerComments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ４．チャットのデータを取得
$stmt = $pdo->prepare("SELECT * FROM chat_table WHERE lid = :lid ORDER BY indate DESC");
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$status = $stmt->execute();

// チャットのデータ取得処理
$chats = [];
if ($status) {
    $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    sql_error($stmt); //関数sql_errorを実行
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>チャット</title>
    <link rel="stylesheet" href="css/range.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        .comment-container {
        display: flex;
        justify-content: flex-start;
        margin: 10px;
        /* background-color: gray; */
        }

        .comment-container.my-comment {
        justify-content: flex-end;
        /* background-color: #DCF8C6; */
        }

        .comment-body {
        width: 300px;
        background-color: #ffdddd;
        border-radius: 5px;
        padding: 10px;
        }

        .comment-date {
        /* font-weight: bold; */
        }

        .comment-text {
        margin-top: 5px;
        }

        div {
            padding: 5px;
            font-size: 16px;
            /* background-color: orange; */
        }

        .chat-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            /* background-color: greenyellow; */
        }

        .chat-box {
            margin-bottom: 10px;
            /* background-color: #fdcd1d; */
        }

        .chat-box .message {
            display: inline-block;
            padding: 10px;
            border-radius: 5px;
            background-color: palevioletred;
        }

        .chat-box .message.admin {
            background-color: pink;
        }

        .chat-box .message.user {
            background-color: #DCF8C6;
            width: 300px;
            text-align: left;
        }
    </style>
</head>

<body id="main">
    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                <?php if ($_SESSION["kanri_flg"] == 1) { ?>
                    <a class="navbar-brand" href="memberlist.php">戻る</a>
                <?php } else { ?>
                    <a class="navbar-brand" href="select.php">戻る</a>
                <?php } ?>
                    <a class="navbar-brand" href="logout.php">LOGOUT</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->


    <!-- Main[Start] -->
    <div class="chat-container">
        <div><?= $lid ?>さんとトレーナーのチャットです</div>
            <!-- 管理者のコメント表示 -->
        <?php
        foreach (array_reverse($managerComments) as $comment) {
            $isMyComment = ($comment['lid'] == $lid); // 自分のコメントかどうか判定
            $commentClass = $isMyComment ? 'my-comment' : 'other-comment'; // 自分のコメントか相手のコメントかに応じたクラスを設定

            echo '<div class="comment-container ' . $commentClass . '">';
            echo '<div class="comment-body">';
            echo '<span class="comment-date">' . $comment['indate'] . '</span>' . '<br>';
            echo '<span class="comment-text">' . $comment['coment'] . '</span>';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <!-- チャットのメッセージ表示 -->
        <?php
        foreach (array_reverse($chats) as $chat) {
            echo '<div class="chat-box">';
            echo '<div class="message ' . ($chat['lid'] == $lid ? 'user' : 'admin') . '">';
            echo '<span class="comment-date">' . $chat['indate'] . '</span><br>';
            echo '<span class="comment-text">' . $chat['coment'] . '</span>';
            echo '</div>';
            echo '</div>';
        }
        ?>
        <!-- メンバーのコメント入力フォーム -->
        <form method="post" action="chat_insert.php" style="display: flex;">
            <input type="hidden" name="lid" value="<?php echo $lid; ?>">
            <textarea name="coment" rows="1" cols="40" style="width: 400px;background-color:white; height: 50px; border-radius: 5px;"></textarea>
            <input type="submit" value="送信" style="border-radius: 5px; margin:10px">
        </form>
    </div>

</body>

</html>
