<?php
session_start();
require('../../dbconnect.php');
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();
} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/login.php');
    exit();
}
$stmt = $db->prepare('SELECT * FROM agents where valid = 1');
$stmt->execute();
$agents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>menu</title>
  <link rel="stylesheet" href="../../css/reset.css">
  <link rel="stylesheet" href="../../css/index.css">
  <link rel="stylesheet" href="../../css/agency.css">
</head>
<body>
  <?php include (dirname(__FILE__) . "/boozer_header.php");?>
  <div class="main">
    <div class="boozerindexouter">
      <div>
        <h2 class="pagetitle">掲載企業一覧</h2>
        <?php 
        $counter = 0;
        foreach($agents as $agent): ?>
        <div>
          <a href='./agentslist.php?agent_id=<?php echo $agent['id'];?>'>
            <form action="delete.php" method="POST" class="indexagencybox">
              <img src="" alt="">
              <h3><?php echo $agent['agent_name']; ?></h3>
              <input type="hidden" name="agent_id" value="<?php echo $agent['id'];?>">
              <input type='submit' formaction='delete.php' name='delete' value='削除' class="deletebtn thinbtn buttonstatic">
            </form>
          </a>
        </div>
        <?php 
          if ($counter >= 2) {break;}
          $counter++;
          endforeach;
        ?>
        <a  class="inquirybtn" href="./agentslist.php">企業一覧をもっと見る</a>
      </div>
      <div class="boozerindexinner">
        <a class="loginbtn" href="./payment.php">明細確認</a>
        <a class="inquirybtn" href="./students.php">学生情報</a>
      </div>
    </div>
    <a href="./contact_from_agency.php" class="indexbtn">掲載依頼一覧・管理</a>
  </div>
  <?php include (dirname(__FILE__) . "/boozer_footer.php");?>
  <script src="boozer.js"></script>
</body>
</html>