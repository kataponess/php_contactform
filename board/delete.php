<?php

// データベースの接続情報
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'php_sample');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$message_id = null;
$mysqli = null;
$sql = null;
$res = null;
$error_message = array();
$message_data = array();

session_start();

// 管理者としてログインしているか確認
if (empty($_SESSION['admin_login']) || $_SESSION['admin_login'] !== true) {

  // ログインページへリダイレクト
  header("Location: ./admin.php");
}

// GETの投稿IDが存在している場合
if (!empty($_GET['message_id']) && empty($_POST['message_id'])) {

  $message_id = (int) htmlspecialchars($_GET['message_id'], ENT_QUOTES);

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // 接続エラーの確認
  if ($mysqli->connect_errno) {
    $error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  } else {

    // データの読み込み
    $sql = "SELECT * FROM message WHERE id = $message_id";
    $res = $mysqli->query($sql);

    if ($res) {
      $message_data = $res->fetch_assoc();
    } else {

      // データが読み込めなかったら一覧に戻る
      header("Location: ./admin.php");
    }

    $mysqli->close();
  }
  // POSTの投稿IDが存在している場合
} elseif (!empty($_POST['message_id'])) {

  $message_id = (int) htmlspecialchars($_POST['message_id'], ENT_QUOTES);

  // データベースに接続
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // 接続エラーの確認
  if ($mysqli->connect_errno) {
    $error_message[] = 'データベースの接続に失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
  } else {
    $sql = "DELETE FROM message WHERE id = $message_id";
    $res = $mysqli->query($sql);
  }

  $mysqli->close();

  // 更新に成功したら一覧に戻る
  if ($res) {
    header("Location: ./admin.php");
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="main.css">
  <title>ひと言掲示板 管理ページ（投稿の削除）</title>
</head>

<body>
  <h1>ひと言掲示板 管理ページ（投稿の削除）</h1>

  <?php if (!empty($error_message)) : ?>
    <ul class="error_message">
      <?php foreach ($error_message as $value) : ?>
        <li>・<?php echo $value; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post">
    <div>
      <label for="view_name">表示名</label>
      <input id="view_name" type="text" name="view_name" value="<?php if (!empty($message_data['view_name'])) {
                                                                  echo $message_data['view_name'];
                                                                } ?>" disabled>
    </div>
    <div>
      <label for="message">ひと言メッセージ</label>
      <textarea id="message" name="message" disabled><?php if (!empty($message_data['message'])) {
                                                        echo $message_data['message'];
                                                      } ?></textarea>
    </div>
    <a class="btn_cancel" href="admin.php">キャンセル</a>
    <input type="submit" name="btn_submit" value="削除">
    <input type="hidden" name="message_id" value="<?php echo $message_data['id']; ?>">
  </form>

  <hr>
  <section>
    <?php if (!empty($message_array)) : ?>
      <?php foreach ($message_array as $value) : ?>
        <article>
          <div class="info">
            <h2><?php echo $value['view_name']; ?></h2>
            <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
          </div>
          <p><?php echo $value['message']; ?></p>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <script src="main.js"></script>
</body>

</html>
