<?php
session_start();
require('../../dbconnect.php');
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();
} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/login.php');
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

$stmt = $db->prepare('select * from intermediate left join students on intermediate.student_id = students.id right join agents on intermediate.agent_id = agents.id where agent_id = :agent_id');
$stmt->bindValue(':agent_id', $agent_id);
$stmt->execute();
$matched_students = $stmt->fetchAll();


$month_total = 0;
foreach($matched_students as $matched_student) {
  if(strpos($matched_student['created_at'], date('Y-m')) !== false) {
    $month_total++;
  }else {
    $month_total += 0;
  }
}

$error_total = 0;
foreach($matched_students as $matched_student) {
  if($matched_student['valid'] !== 0) {
    $error_total++;
  }else {
    $error_total += 0;
  }
}

$payment = ($month_total - $error_total) * 3000;
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
  <?php include (dirname(__FILE__) . "/boozer_header.php");?>
  <div class="main">
    <div class="months">
      <?php 
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        foreach ($months as $key => $month) : 
        ?>
        <button class="monthcircle"><?= $month;?></button>
      <?php endforeach;?>
    </div>

    <div class="paymentboxouter">
      <div class="agencyname">
        <span><?=$agent['agent_name']?></span>
        <span>??<?= $payment ?></span>
      </div>
      <div class="paymentmanager">
        <span><?php print_r($managers['manager_last_name']);?></span>
        <span><?php print_r($managers['manager_first_name']);?></span><br>
        <span><?php print_r($managers['agent_department']);?></span><br>
        <span><?=$agent['notification_email'] ?></span>
      </div>
      <div class="paymentdetailbox">
        <div class="paymentbox">
          <h4>????????????</h4>
          <span><?php      
          echo"($month_total - $error_total) * 3000 = $payment ???"; ?></span>
        </div>
        <div class="paymentbox">
          <h4>??????????????????</h4>
          <span>???????????????????????? <?= $month_total ?></span><br>
          <span>??????????????? <?= $error_total ?></span>
        </div>
        <div class="paymentbox">
          <h4>?????????</h4>
          <p>???????????? ????????????<br>????????????:?????? 1234567<br>?????????:????????????????????????????????????</p>
          <span>??????????????????: </span>
        </div>
      </div>
    </div>
    <div class="pageendbuttons flexdirectionreverse">
      <a href="payment.php" class="inquirybtn endbtn" onclick="
      <?php
        $notification_email = $agent['notification_email'];
        $addresses = ['test@posse-ap.com', $notification_email];

        foreach ($addresses as $address) {
          $from = 'boozer@craft.com';
          $to   = $address;
          $subject = 'payment from boozer';
          $body = $payment;

          $ret = mb_send_mail($to, $subject, $body, "From: {$from} \r\n");
          var_dump($ret);
        }
        ?>
      ">????????????</a>
      <a href='javascript:history.back()' class="returnbtn endbtn">??????</a>
    </div>
  </div>
  <?php include (dirname(__FILE__) . "/boozer_footer.php");?>
  <script src="./boozer.js"></script>
</body>
</html>