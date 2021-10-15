<?php
require "../config.php";
require "../common.php";
require "../templates/header.php";

?>
    <h2>Edit a user</h2>
<?php

if (isset($_POST['finduser'])) {

    try {
      $connection = new PDO($dsn, $username, $password, $options);
      $firstname = $_POST['finduser'];

      $sql = "SELECT * FROM " . $table . " WHERE firstname = :firstname";
      $statement = $connection->prepare($sql);
      $statement->bindValue(':firstname', $firstname);
      $statement->execute();

      $user = $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }

    if($user){
?>

    <form method="post">
        <input name="csrf" type="hidden" value="<?php echo escape($_SESSION['csrf']); ?>">
        <?php foreach ($user as $key => $value) : ?>
                <?php if($key != "lastname" && $key != "reg_date"){ ?>
          <label <?php if($key == "id" || $key == "id2"){ echo "class=\"ghost\"";} else {echo "class=\"label\"";} ?>  for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
    	    <textarea <?php if($key == "id" || $key == "id2"){ echo "class=\"ghost\"";} else {echo "class=\"item\"";} ?> <?php if($key == "wishlist"){ echo "rows=\"10\"";} else {echo "rows=\"1\"";} ?> type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" <?php echo (($key === 'id'  || $key == "id2"  || $key == "firstname"  || $key == "email") ? 'readonly' : null); ?>><?php echo escape($value); ?></textarea>
          <br>


        <?php } ?>
        <?php endforeach; ?>
        <input type="submit" name="submit" value="Submit">
    </form>
    <?php
  } else {
        echo "<div class=\"red\" >User doesn't Exist! </div> <br>";
    ?>
    <form action="" method="post">
    <label for="finduser">Whats Your First Name?</label>
    <input type="text" id="finduser" name="finduser"><br><br>
    <input type="submit" value="Submit">
  </form>
  <?php
  }
 // echo "You have: {$user['firstname']}. And he wants: {$user['wishlist']} <br>";
} else if(isset($_POST['id'])){

echo "Submitted! Your secret santa will be notified shortly. <br>";



try {
  $connection3 = new PDO($dsn, $username, $password, $options);

  $user3 =[
    "id"        => $_POST['id'],
    "firstname" => $_POST['firstname'],
    "email"     => $_POST['email'],
    "wishlist"  => $_POST['wishlist'],
    "id2"       => $_POST['id2']
  ];

  $sql3 = "UPDATE " . $table . "
          SET wishlist = :wishlist
          WHERE id = :id";

$statement3 = $connection3->prepare($sql3);
$statement3->execute($user3);
} catch(PDOException $error) {
    echo $sql3 . "<br>" . $error->getMessage();
}



try {
  $connection2 = new PDO($dsn, $username, $password, $options);
  $id2 = $_POST['id2']; // change to other id

  $sql2 = "SELECT * FROM " . $table . " WHERE id = " . $id2;
  $statement2 = $connection2->prepare($sql2);
  $statement2->bindValue(':id', $id2);
  $statement2->execute();

  $user2 = $statement2->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}


$from = "Santa <wishlist@domain.com>";
$to = "{$user2['firstname']} <{$user2['email']}>"; //<{$user2['email']}>"; //user2email user2 name
$subject = "Wish List";
$body = "To: {$user2['firstname']} \n{$_POST['firstname']}'s Wish List: {$_POST['wishlist']}";

$host = "domain.com";
$username = "wishlist@domain.com";
$password = "OMITTED";

$headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
$smtp = Mail::factory('smtp',
  array ('host' => $host,
    'auth' => true,
    'username' => $username,
    'password' => $password));

$mail = $smtp->send($to, $headers, $body);

if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
 } else {
  //echo("<p>Message successfully sent!</p>");
 }





} else {
  ?>
  <form action="" method="post">
  <label for="finduser">Whats Your First Name?</label>
  <input type="text" id="finduser" name="finduser"><br><br>
  <input type="submit" value="Submit">
</form>
<?php
}
?>


<a href="index.php">Back to home</a>
