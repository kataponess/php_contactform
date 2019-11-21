<?php include('./index.db.php'); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="main.css">
  <title>ひと言掲示板</title>
</head>

<body>
  <h1>ひと言掲示板</h1>
  <?php if (empty($_POST['btn_submit']) && !empty($_SESSION['success_message'])) : ?>
    <p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

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
      <input id="view_name" type="text" name="view_name" value="<?php if (!empty($_SESSION['view_name'])) {
                                                                  echo $_SESSION['view_name'];
                                                                } ?>">
    </div>
    <div>
      <label for="message">ひと言メッセージ</label>
      <textarea id="message" name="message"></textarea>
    </div>
    <input type="submit" name="btn_submit" value="書き込む">
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
          <p><?php echo nl2br($value['message']); ?></p>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <script src="main.js"></script>
</body>

</html>
