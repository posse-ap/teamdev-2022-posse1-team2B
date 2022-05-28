<?php
session_start();
require('../../dbconnect.php');
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    // SESSIONにuser_idカラムが設定されていて、SESSIONに登録されている時間から1日以内なら
    $_SESSION['time'] = time();
    // SESSIONの時間を現在時刻に更新
} else {
    // そうじゃないならログイン画面に飛んでね
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '../login.php');
    exit();
}
$agent_id = $_GET['agent_id'];

$stmt = $db->prepare('SELECT * FROM agents WHERE id = :id');
$stmt->bindValue(':id', $agent_id);
$stmt->execute();
$agent = $stmt->fetch();

$stmt = $db->prepare('SELECT * FROM managers WHERE agent_id = :agent_id');
$stmt->bindValue(':agent_id', $agent_id);
$stmt->execute();
$managers = $stmt->fetch();
?>



<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../../css/reset.css">
  <link rel="stylesheet" href="../../css/index.css">
</head>
<body>
  <!-- <?php include (dirname(__FILE__) . "/boozer_header.php");?> -->
  <div class="main">
    <?php 
      $months = [1,2,3,4,5,6,7,8,9,10,11,12];
      foreach ($months as $key => $month) : 
      ?>
      <button><?= $month;?></button>
    <?php endforeach;?>

    <div>
      <div>
        <!-- <span><?= $payment ?></span> -->
      </div>
      <div>
        <span><?=$agent['agent_name']?></span>
        <span><?=$agent['notification_email'] ?></span>
        <span><?php print_r($managers['manager_last_name']);?></span>
        <span><?php print_r($managers['manager_first_name']);?></span>
        <span><?php print_r($managers['agent_department']);?></span>
        <!-- <span><?php print_r($manager[0]['email']);?></span> -->
      </div>
      <div>
        <h4>請求詳細</h4>
        <span><?php 
        // $this_month = date('Y-m');
        // $stmt = $db->prepare("SELECT COUNT(*) FROM students where id = :id and created_at like :this_month");
        // $stmt->bindValue(':id', $agent_id);
        // $stmt->bindValue(':this_month', $this_month);
        // $stmt->execute();
        // $count = $stmt->fetchColumn();

        // echo $this_month;
        // echo $count;
      
        
        echo"($action_number - $error_number) * 3000 = $payment 円"; ?></span>
      </div>
      <div>
        <h4>問い合わせ数</h4>
        <span>学生問い合わせ数 <?= $action_number ?></span>
        <span>いたずら数 <?= $error_number ?></span>
      </div>
      <div>
        <h4>振込先</h4>
        <p>〇〇銀行 〇〇支店<br>口座番号:普通 1234567<br>口座名:カブシキガイシャブーザー</p>
        <span>お支払い期日: </span>
      </div>
    </div>
    <a href='javascript:history.back()'>戻る</a>
  </div>
  <?php include (dirname(__FILE__) . "/boozer_footer.php");?>
  <script src="./boozer.js"></script>
</body>
</html>