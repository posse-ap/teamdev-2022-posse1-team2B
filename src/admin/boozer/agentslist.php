<?php
require("../../dbconnect.php");


$stmt = $db->prepare('select * from agents');
// $stmt->bindValue(':agent_id', $agent['id']);
  // bindevalueの１が？の１個めってこと。これがあれば何個でもはてなつけられる！1,2とかだとわかりにくいから、「:agent_id」を設定する
  $stmt->execute();
  $agents = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>agentslist</title>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
  <?php
  ?>
  <h2>掲載企業一覧</h2>
  <?php foreach ($agents as $index => $agent) : ?>
  <div>
    <?php
      $stmt = $db->prepare('SELECT agent_name FROM agents WHERE id = :id');
      $stmt->bindValue(':id', $index+1);
      $stmt->execute();
      $agent_id = $stmt->fetchAll();
      var_dump($agent_id[0]["agent_name"]);
    ?>
    <!-- <a href="./edit.php?agent_name=<?php //echo $index + 1; ?>"> -->
    <form method="GET" action="edit.php">
      <img src="" alt="">
      <h3><?=$agent['agent_name'] ?></h3>
      <!-- <a href="edit.php">編集</a> -->
      <input type="hidden" name="id" value="<?php Echo $index+1; ?>">
      <input type="submit" name="edit" value="編集">
      <button>削除</button>
    <!-- </a> -->
    </form>
  </div>
<?php endforeach; ?>

  <?php 
  

  ?>

</body>
</html>

<!-- Ajax
編集をクリックしたらモーダルを出す
編集というボタンにIDをもたせて、非同期処理でエージェント情報を引っ張ってくる
＜もって着方＞
phpをhtmlで出すってことを今まではしていた
代わりに、phpでジェイソン(編集にあるIDに紐づくエージェント情報を整形して、ジェイソン形式で返す)を返すようにしてあげる

それをAPIで呼び出せるようにしておいて、それに対してクリックしたらFetchを使って呼び出して、モーダルで表示してみる

Slackでも！！！！！！！！！！！！ -->

