<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>キープ中のエージェンシー企業</title>
</head>
<body>
  <!-- 
    ・キープを押した時点でエージェントさんのIDをセッションとかに保存する
→IDを元に企業の情報をデータベースから持ってくる
    ・セッションストレージ→ブラウザにデータを保存
  →Aさんがキープしたっていう情報は個々のブラウザに保存される
→キャッシュみたいなもの
・ブラウザ側に情報を持ってもらって、その人が何の情報を保存したかを種痘できる
→セッションで保存されたエージェントのIDを利用してエージェントの情報を出力する
・クッキーとかもある

・セッションについての記事
→https://developer.mozilla.org/ja/docs/Web/API/Window/sessionStorage
→情報を取得SET,GET,REMOVE

-->
  <?php include (dirname(__FILE__) . "/student_header.php");?>
  <h1>キープ中のエージェンシー企業</h1>
    <div>
      <div>
        <p><?php print_r($keep_agent["name"]);?></p>
        <dl>
          <dt>得意な業種</dt>
          <dd><?php print_r($keep_agent["industry"]);?></dd>
          <dt>対応エリア</dt>
          <dd><?php print_r($keep_agent["supported_area"]);?></dd>
          <dt>対象学生</dt>
          <dd><?php print_r($keep_agent["target_student"]);?></dd>
          <dt>対応企業の規模</dt>
          <dd><?php print_r($keep_agent["corporate_scale"]);?></dd>
          <dt>備考</dt>
          <dd><?php print_r($keep_agent["remarks"]);?></dd>
        </dl>
        
      </div>
      <form action="./contact.php" method="POST">
        <button type="button">戻る</button>
        <input type="hidden" name="agent_id" value="<?php print($agent['agent_id']);?>">
        <button type="submit">エージェンシー企業に問い合わせる</button>
      </form>
    </div>
  <?php include (dirname(__FILE__) . "/student_footer.php");?>
</body>
</html>