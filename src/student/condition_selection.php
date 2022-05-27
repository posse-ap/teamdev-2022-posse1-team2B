<?php 
require("../dbconnect.php");
$page_flag = 0;
if(isset($_POST["search"])) {
  $page_flag = 1;
} 

session_start();
$stmt = $db->prepare('SELECT * FROM agents');
$stmt->execute();
$agents = $stmt->fetchAll();

$stmt = $db->prepare('SELECT * FROM agents');
$stmt->execute();
$agents = $stmt->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['agent_id'])){
    $agent_id = $_POST['agent_id'];
    $_SESSION['keep'][$agent_id]=$agent_id; //セッションにデータを格納
    if(isset($_POST['cancel'])) {
      unset($_SESSION['keep'][$agent_id]);
    }
  }
}
$keeps=array();
if(isset($_SESSION['keep'])){
  $keeps=$_SESSION['keep'];
  $_SESSION['time'] = time();
}

  // 絞り込み検索
// // $_REQUEST は現在の $_GET、$_POST、$_COOKIE などの内容をまとめた変数
// $stmt = $db->prepare('select * from characteristic left join agents on agents.id = characteristic.agent_id left join category on characteristic.category_id = category.id left join job_area on characteristic.job_area_id = job_area.id left join target_student on characteristic.target_student_id = target_student.id');
// $stmt->execute();
// $information_agents = $stmt->fetchAll();
// foreach ($information_agents as $information_agent) {}

// if(isset($_POST['category'])){
  foreach($_POST['category'] as $key => $category) {
    $category_array = array();
      $category_array[] = "$category";
      $stmt = $db->prepare('SELECT id FROM category WHERE category_name = :category');
      $stmt -> bindValue(':category', $category_array[$key]);
      $stmt->execute();
      $categories = $stmt->fetchAll();
      $stmt = $db->prepare('SELECT * FROM characteristic WHERE category_id = :category_id');
      $stmt -> bindValue(':category_id', $categories[$key]['id']);
      $stmt->execute();
      $agent_categories = $stmt->fetchAll();
      // print_r($agent_category);
  }
  foreach($agent_categories as $agent_category) {
          print_r($agent_category);
  }
// characteristic agentsをくっ付ける


// } 
// if(isset($_POST['job_area'])){
foreach($_POST['job_area'] as $key => $job_area) {
  $job_area_array = array();
    $job_area_array[] = "$job_area";
    $stmt = $db->prepare('SELECT id FROM job_area WHERE area = :job_area');
    $stmt -> bindValue(':job_area', $job_area_array[$key]);
    $stmt->execute();
    $areas = $stmt->fetchAll();
    $stmt = $db->prepare('SELECT * FROM characteristic WHERE job_area_id = :area_id');
    $stmt -> bindValue(':area_id', $areas[$key]['id']);
    $stmt->execute();
    $agent_job_areas = $stmt->fetchAll();
    // print_r($agent_category);
}
foreach($agent_categories as $agent_category) {
        print_r($agent_category);
} 
// } 
// if(isset($_POST['target_student'])){

foreach($_POST['target_student'] as $key => $target_student) {
  $target_student_array = array();
    $target_student_array[] = "$target_student";
    $stmt = $db->prepare('SELECT id FROM target_student WHERE graduation_year = :target_student');
    $stmt -> bindValue(':target_student', $target_student_array[$key]);
    $stmt->execute();
    $targets = $stmt->fetchAll();
    $stmt = $db->prepare('SELECT * FROM characteristic WHERE target_student_id = :target_id');
    $stmt -> bindValue(':target_id', $targets[$key]['id']);
    $stmt->execute();
    $target_students = $stmt->fetchAll();
}
foreach($agent_categories as $agent_category) {
        print_r($agent_category);
} 
// }
// ベストではない
// →カテゴリーが日本語→よろしくない
// →カテゴリーの名前が変わった時に、エージェンシー企業のテーブル自体も変える
// →カテゴリーとエージェントのテーブルを分けるべき
// →中間テーブルを作ってつないであげる
// if(isset($_POST["job_area"])){
// switch($search) 
// {
//   case $_POST['category']:
//     echo($_POST['category']);
//     $category_array = array();foreach($_POST['category'] as $key => $category) {
//     $category_array[] = "$category";
//     $stmt = $db->prepare('SELECT * FROM agents WHERE category = :category');
//     $stmt -> bindValue(':category', $category_array[$key]);
//     $stmt->execute();
//     $categories = $stmt->fetchAll();      
//   }
//   case $_POST['job_area']:
//     $job_area_array = array();
//     foreach($_POST['job_area'] as $key => $job_area) {
//         $job_area_array[] = "$job_area";
//         $stmt = $db->prepare('SELECT * FROM agents WHERE job_area = :job_area');
//         $stmt -> bindValue(':job_area', $job_area_array[$key]);
//         $stmt->execute();
//         $areas = $stmt->fetchAll();    
//         print_r($areas);
//     }
//   case $_POST['target_student']:
//     $category_array = array();
//     foreach($_POST['target_student'] as $key => $target_student) {
//         $target_student_array[] = "$target_student";
//         $stmt = $db->prepare('SELECT * FROM agents WHERE target_student = :target_student');
//         $stmt -> bindValue(':target_student', $target_student_array[$key]);
//         $stmt->execute();
//         $students = $stmt->fetchAll();    
//         print_r($students);
//     }
//   break;
//   default:
//     print_r('該当する企業はありません');
// }
// }



  

// $where = ""
// foreach($_REQUEST['category'] as $key => $category) {
//   $where[] = "category = '$category'"
// }
// $print_r($where);
// }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>こだわり条件で絞り込む</title>
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="../css/index.css">
</head>
<body>
  <?php include (dirname(__FILE__) . "/student_header.php");?>
  <?php 
  if($page_flag === 1 || isset($_GET["back"])):?>
    <!-- 絞り込み結果 -->
  <div class="main">
    <h1>絞り込み結果</h1>
    <a href="./condition_selection.php">✕</a>
    <a href="./keep.php">キープ中の企業</a>
    <ul>
      <li>
        <a href="./agent_detail.php">
          <p><?php ?></p>
          <img src="../img/<?php ?>.png" alt="エージェンシー企業">
          <dl>
            <dt>得意な業種</dt>
            <dd><?php     print_r($categories);?></dd>
            <dt>対応エリア</dt>
            <dd><?php print_r($areas);?></dd>
            <dt>対象学生</dt>
            <dd><?php print_r($students);?></dd>
            <dt>対応企業の規模</dt>
            <dd><?php ?></dd>
          </dl>
          <form action="./keep.php" method="POST">
            <input type="hidden" name="agent_id" value="<?php print_r($agents[0]['id']);?>">
            <button type="submit" class="keepbtn">キープする</button>
            <button type="submit" formaction="./contact.php" class="inquirybtn">エージェンシー企業に問い合わせる</button>
          </form>
        </a>
      </li>
    </ul>
  </div>
  <!-- こだわり条件から探すをクリックした場合に表示 -->
  <?php else:?>
  <div class="main">
    <div class="conditionselectioninner">
      <a href="./index.php" class="exitbtn">✕</a>
      <form action="" method="POST">
        <h1 class="pagetitle">条件で絞り込む</h1>
        <div>
          <div class="conditiongroup">
            <h2>得意業界</h2>
            <input type="checkbox" name="category[]" value='IT' id="it">
            <label from="it">IT</label>
            <input type="checkbox" name="category[]" value='飲食' id="food">
            <label from="food">飲食</label>
            <input type="checkbox" name="category[]" value='メーカー' id="maker">
            <label from="maker">メーカー</label>
            <input type="checkbox" name="category[]" value='サービス' id="service">
            <label from="service">サービス</label>
            <input type="checkbox" name="category[]" value='商社' id="tradingCompany">
            <label from="tradingCompany">商社</label>
            <input type="checkbox" name="category[]" value='建築' id="architecture">
            <label from="architecture">建築</label>
            <input type="checkbox" name="category[]" value='小売' id="retail">
            <label from="retail">小売</label>
            <input type="checkbox" name="category[]" value='事務' id="officeWork">
            <label from="officeWork">事務</label>
            <input type="checkbox" name="category[]" value='広告' id="ad">
            <label from="ad">広告</label>
            <input type="checkbox" name="category[]" value='金融' id="finance">
            <label from="finance">金融</label>
            <input type="checkbox" name="category[]" value='コンサルティング' id="consulting">
            <label from="consulting">コンサルティング</label>
            <input type="checkbox" name="category[]" value='物流' id="logistics">
            <label from="logistics">物流</label>
            <input type="checkbox" name="category[]" value='通信' id="communication">
            <label from="communication">通信</label>
            <input type="checkbox" name="category[]" value='住宅' id="residence">
            <label from="residence">住宅</label>
            <input type="checkbox" name="category[]" value='保険' id="insurance">
            <label from="insurance">保険</label>
          </div>
          <div class="conditiongroup">
            <h2>対応している求人エリア</h2>
            <input type="checkbox" name="job_area[]" value="関東" id="kantoRegion">
            <label from="kantoRegion">関東地方</label>
            <input type="checkbox" name="job_area[]" value="関西" id="kansaiRegion">
            <label from="kansaiRegion">関西地方</label>
            <input type="checkbox" name="job_area[]" value="東海" id="tokaiRegion">
            <label from="tokaiRegion">東海地方</label>
            <input type="checkbox" name="job_area[]" value="九州" id="kyushuRegion">
            <label from="kyushuRegion">九州地方</label>
          </div>
          <div class="conditiongroup">
            <h2>対象学生</h2>
            <input type="checkbox" name="target_student[]" value="23卒" id="2023Graduation">
            <label from="2023Graduation">23卒</label>
            <input type="checkbox" name="target_student[]" value="24卒" id="2024Graduation">
            <label from="2024Graduation">24卒</label>
            <input type="checkbox" name="target_student[]" value="25卒" id="2025Graduation">
            <label from="2025Graduation">25卒</label>
            <input type="checkbox" name="target_student[]" value="26卒" id="2026Graduation">
            <label from="2026Graduation">26卒</label>
  
          </div>
        </div>
        <input type="submit" name="search" value = "検索" class="searchbtn">
      </form>
    </div>
  </div>
  <?php endif; ?>
  <?php include (dirname(__FILE__) . "/student_footer.php");?>
  <script src="./student.js"></script>
</body>
</html>