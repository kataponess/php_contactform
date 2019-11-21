<?php
if (mb_send_mail('m_kattan69@yahoo.co.jp', 'テスト送信', 'テスト本文')) {
  echo '送信成功';
} else {
  echo '送信失敗';
}
