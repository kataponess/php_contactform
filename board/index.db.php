<?php

// データベースの接続情報
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'php_sample');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$error_message = array();
$clean = array();

session_start();

if (!empty($_POST['btn_submit'])) {

  // 表示名の入力チェック
  if (empty($_POST['view_name'])) {
    $error_message[] = '表示名を入力してください。';
  } else { // サニタイズ
    $clean['view_name'] = htmlspecialchars($_POST['view_name'], ENT_QUOTES);
    $clean['view_name'] = preg_replace('/\\r\\n|\\n|\\r/', '', $clean['view_name']);

    // セッションに表示名を保存
    $_SESSION['view_name'] = $clean['view_name'];
  }

  // メッセージの入力チェック
  if (empty($_POST['message'])) {
    $error_message[] = 'ひと言メッセージを入力してください。';
  } else { // サニタイズ
    $clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
  }

  if (empty($error_message)) {

    // データベースに接続（ mysqli(ホスト名, ユーザー名, パスワード, データベース名) ）
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // 接続エラーの確認
    if ($mysqli->connect_errno) {
      $error_message[] = '書き込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
    } else {
      // 文字コード設定
      $mysqli->set_charset('utf8');

      // 書き込み日時を取得
      $now_date = date("Y-m-d H:i:s");

      // データを登録するSQL作成
      $sql = "INSERT INTO message (view_name, message, post_date) VALUES ( '$clean[view_name]', '$clean[message]', '$now_date')";

      // データを登録
      $res = $mysqli->query($sql);

      if ($res) {
        $_SESSION['success_message'] = 'メッセージを書き込みました。';
      } else {
        $error_message[] = '書き込みに失敗しました。';
      }

      // データベースの接続を閉じる
      $mysqli->close();
    }

    header('Location: ./');
  }
}

// データベースに接続
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if ($mysqli->connect_errno) {
  $error_message[] = 'データの読み込みに失敗しました。 エラー番号 ' . $mysqli->connect_errno . ' : ' . $mysqli->connect_error;
} else {
  $sql = "SELECT view_name, message, post_date FROM message ORDER BY post_date DESC";
  $res = $mysqli->query($sql);

  if ($res) {
    $message_array = $res->fetch_all(MYSQLI_ASSOC);
  }

  $mysqli->close();
}
