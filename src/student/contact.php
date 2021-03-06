<?php
require('../dbconnect.php');

// キープされていた企業にお問い合わせする
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['agent_id'])) {
    $agent_id = $_POST['agent_id'];
    $_SESSION['time'] = time();
    $_SESSION['keep'][$agent_id] = $agent_id; //セッションにデータを格納
    if (isset($_POST['cancel_agency'])) {
      unset($_SESSION['keep'][$agent_id]);
      $_SESSION['time'] = time();
    }
  }
}
$keeps = array();
if (isset($_SESSION['keep']) && $_SESSION['time'] + 60 * 60 * 24  > time()) {
  $keeps = $_SESSION['keep'];
  $_SESSION['time'] = time();
} else {
  session_destroy();
}



if (isset(
  $_POST['student_last_name'],
  $_POST['student_first_name'],
  $_POST['student_last_name_kana'],
  $_POST['student_first_name_kana'],
  $_POST['post_number'],
  $_POST['prefecture'],
  $_POST['municipality'],
  $_POST['adress_number'],
  $_POST['tel_number'],
  $_POST['email'],
  $_POST['college_name'],
  $_POST['undergraduate'],
  $_POST['college_department'],
  $_POST['graduation_year']
)
) {
  $stmt = $db->prepare('insert into students 
  (
    student_last_name, 
    student_first_name, 
    student_last_name_kana, 
    student_first_name_kana, 
    post_number,
    prefecture,
    municipality,
    adress_number,
    tel_number,
    email,
    college_name,
    undergraduate,
    college_department,
    graduation_year
  ) 
  values
  (
    :student_last_name,
    :student_first_name,
    :student_last_name_kana,
    :student_first_name_kana,
    :post_number,
    :prefecture,
    :municipality,
    :adress_number,
    :tel_number,
    :email,
    :college_name,
    :undergraduate,
    :college_department,
    :graduation_year
  )');

  $student_last_name = $_POST['student_last_name'];
  $student_first_name = $_POST['student_first_name'];
  $student_last_name_kana = $_POST['student_last_name_kana'];
  $student_first_name_kana = $_POST['student_first_name_kana'];
  $post_number = $_POST['post_number'];
  $prefecture = $_POST['prefecture'];
  $municipality = $_POST['municipality'];
  $adress_number = $_POST['adress_number'];
  $tel_number = $_POST['tel_number'];
  $email = $_POST['email'];
  $college_name = $_POST['college_name'];
  $undergraduate = $_POST['undergraduate'];
  $college_department = $_POST['college_department'];
  $graduation_year = $_POST['graduation_year'];

  $param = array(
    ':student_last_name' => $student_last_name,
    ':student_first_name' => $student_first_name,
    ':student_last_name_kana' => $student_last_name_kana,
    ':student_first_name_kana' => $student_first_name_kana,
    ':post_number' => $post_number,
    ':prefecture' => $prefecture,
    ':municipality' => $municipality,
    ':adress_number' => $adress_number,
    ':tel_number' => $tel_number,
    ':email' => $email,
    ':college_name' => $college_name,
    ':undergraduate' => $undergraduate,
    ':college_department' => $college_department,
    ':graduation_year' => $graduation_year
  );

  $stmt->execute($param);
}


$page = 0;

if (isset($_POST['contact'])) {

  $page = 1;
  $_SESSION['contact'] = 'thanks';
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>お問い合わせ入力</title>
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/index.css">
</head>

<body>

  <?php include(dirname(__FILE__) . "/student_header.php");
  if ($page === 1) :
  ?>
    <!-- 確認画面 -->
    <div class="main">
      <h1 class="pagetitle">登録内容の確認</h1>
      <form method="POST" action="apply.php" class="inputform">
        <div class="half">
          <div>
            <label>氏</label>
            <p>
              <?php echo $_POST["student_last_name"]; ?>
            </p>
          </div>
          <div>
            <label>名</label>
            <p>
              <?php echo $_POST["student_first_name"]; ?>
            </p>
          </div>
        </div>
        <div class="half">
          <div>
            <label>氏（カナ）
            </label>
            <p>
              <?php echo $_POST["student_last_name_kana"]; ?>
            </p>
          </div>
          <div>
            <label>名（カナ）</label>
            <p>
              <?php echo $_POST["student_first_name_kana"]; ?>
            </p>
          </div>
        </div>
        <div>
          <label>郵便番号</label>
          <p>
            <?php echo $_POST["post_number"]; ?>
          </p>
        </div>
        <div>
          <label>都道府県</label>
          <p>
            <?php echo $_POST["prefecture"]; ?>
          </p>
        </div>
        <div>
          <label>市区町村</label>
          <p>
            <?php echo $_POST["municipality"]; ?>
          </p>
        </div>
        <div>
          <label>番地</label>
          <p>
            <?php echo $_POST["adress_number"]; ?>
          </p>
        </div>
        <div>
          <label>電話番号</label>
          <p>
            <?php echo $_POST["tel_number"]; ?>
          </p>
        </div>
        <div>
          <label>メールアドレス</label>
          <p>
            <?php echo $_POST["email"]; ?>
          </p>
        </div>
        <div>
          <label>出身大学</label>
          <p>
            <?php echo $_POST["college_name"]; ?>
          </p>
        </div>
        <div>
          <label>学部</label>
          <p>
            <?php echo $_POST["undergraduate"]; ?>
          </p>
        </div>
        <div>
          <label>学科</label>
          <p>
            <?php echo $_POST["college_department"]; ?>
          </p>
        </div>
        <div>
          <label>卒業年</label>
          <p>
            <?php echo $_POST["graduation_year"]; ?>
          </p>
        </div>
        <button type="submit" name="btn_back" formaction="index.php" class="returnbtn widebtn">やりなおす</button>
        <input type="hidden" name="student_last_name" value="<?php echo $_POST["student_last_name"]; ?>">
        <input type="hidden" name="student_last_name_kana" value="<?php echo $_POST["student_last_name_kana"]; ?>">
        <input type="hidden" name="student_first_name" value="<?php echo $_POST["student_first_name"]; ?>">
        <input type="hidden" name="student_first_name_kana" value="<?php echo $_POST["student_first_name_kana"]; ?>">
        <input type="hidden" name="post_number" value="<?php echo $_POST["post_number"]; ?>">
        <input type="hidden" name="prefecture" value="<?php echo $_POST["prefecture"]; ?>">
        <input type="hidden" name="municipality" value="<?php echo $_POST["municipality"]; ?>">
        <input type="hidden" name="adress_number" value="<?php echo $_POST["adress_number"]; ?>">
        <input type="hidden" name="tel_number" value="<?php echo $_POST["tel_number"]; ?>">
        <input type="hidden" name="email" value="<?php echo $_POST["email"]; ?>">
        <input type="hidden" name="college_name" value="<?php echo $_POST["college_name"]; ?>">
        <input type="hidden" name="undergraduate" value="<?php echo $_POST["undergraduate"]; ?>">
        <input type="hidden" name="college_department" value="<?php echo $_POST["college_department"]; ?>">
        <input type="hidden" name="graduation_year" value="<?php echo $_POST["graduation_year"]; ?>">
        <input type="hidden" name="notification_email" value="<?php echo $_POST["notification_email"]; ?>">
        <input type="hidden" name="contact_agent_id" value="<?php echo $_POST["contact_agent_id"]; ?>">
        <button type="submit" name="final_contact" class="inquirybtn" onclick="
      <?php
      // $_SESSION['contact'] = 'thanks';
      $addresses = ['test@posse-ap.com', $_POST['notification_email']];

      foreach ($addresses as $address) {
        $from = 'boozer@craft.com';
        $to   = $address;
        $subject = 'Hi, from craft';
        $body = 'student contacts to craft';

        $ret = mb_send_mail($to, $subject, $body, "From: {$from} \r\n");
        var_dump($ret);
      }
      ?>
      ">完了</button>

      </form>
    </div>
  <?PHP else : ?>
    <!-- 問い合わせ入力画面 -->
    <div class="main">
      <h1 class="pagetitle">企業にお問い合わせ</h1>
      <?php
            $stmt = $db->prepare('SELECT * FROM agents WHERE id = :id');
            $stmt->bindValue(':id', $_POST['agent_id']);
            $stmt->execute();
            $agencies = $stmt->fetch();
            ?>
      <div class="agencybtn">申し込み先企業：
        <?php
        if (isset($_POST['keep_agency_contact'])) {
          foreach ($keeps as $keep) {
            $stmt = $db->prepare('SELECT * FROM agents WHERE id = :id');
            $stmt->bindValue(':id', $keep);
            $stmt->execute();
            $agent = $stmt->fetch();
            echo '<br>・' . $agent['agent_name'];
          }
        } else {
          print_r($agencies['agent_name']);
        }
        ?>
      </div>
      <form action="contact.php" method="POST">
        <div class="inputform">
          <div class="half">
            <input type="hidden" name="notification_email" value="<?php print_r($agencies['notification_email']);?>">
            <div>
              <label for="familyName">氏</label><br>
              <input type="text" name="student_last_name" id="familyName" required>
            </div>
            <div>
              <label for="studentName">名</label><br>
              <input type="text" name="student_first_name" id="studentName" required>
            </div>
          </div>
          <div class="half">
            <div>
              <label for="familyNameKana">氏（カナ）</label><br>
              <input type="text" name="student_last_name_kana" id="familyNameKana" pattern="(?=.*?[\u30A1-\u30FA])[\u30A1-\u30FC]*" required>
            </div>
            <div>
              <label for="studentNameKana">名（カナ）</label><br>
              <input type="text" name="student_first_name_kana" id="studentNameKana" pattern="(?=.*?[\u30A1-\u30FA])[\u30A1-\u30FC]*" required>
            </div>
          </div>
          <div>
            <label for="postNumber">郵便番号</label><br>
            <input type="text" name="post_number" id="postNumber" maxlength="7" required>
          </div>
          <div>
            <label for="prefecture">都道府県</label><br>
            <input type="text" name="prefecture" id="prefecture" required>
          </div>
          <div>
            <label for="prefecture">市区町村</label><br>
            <input type="text" name="municipality" id="municipality" required>
          </div>
          <div>
            <label for="adressNumber">番地</label><br>
            <input type="text" name="adress_number" id="adress_number" required>
          </div>
          <div>
            <label for="telephoneNumber">電話番号</label><br>
            <input type="tel" name="tel_number" id="telephoneNumber" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" maxlength="11" required>
          </div>
          <div>
            <label for="emailAddress">メールアドレス</label><br>
            <input type="email" name="email" id="emailAddress" required>
          </div>
          <div class="half">
            <div>
              <label for="almaMater">出身大学</label><br>
              <input type="text" name="college_name" id="almaMater" required>
            </div>
            <div>
              <label for="faculty">学部</label><br>
              <input type="text" name="undergraduate" id="faculty" required>
            </div>
          </div>
          <div class="half">
            <div>
              <label for="department">学科</label><br>
              <input type="text" name="college_department" id="department" required>
            </div>
            <div>
              <label for="graduationYear">卒業年を選択</label><br>
              <input type="text" name="graduation_year" id="graduationYear" maxlength="4" required>
            </div>
          </div>
          <div>
          <input type="hidden" name="contact_agent_id" value="<?php echo $agencies['agent_name']; ?>">
            <input type="submit" name="contact" value="エージェンシーにお問い合わせ" class="ignore inquirybtn">
          </div>
      </form>
      <?php
      if (isset($_POST["btn_back"])) {
        // 戻るが押されたとき
        echo ('<form action="condition_selection.php" method="GET">
          <button type="submit" name="back" class="returnbtn">戻る</button>
          </form>');
      } else {
        echo ('<a href=' . '"javascript:history.back()"' . ' class="returnbtn">戻る</a>');
      }
      ?>
    </div>
  <?php endif;
  include(dirname(__FILE__) . "/student_footer.php");
  ?>
  <script src="./student.js"></script>

</body>

</html>