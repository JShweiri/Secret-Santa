<?php
require "./config.php";
require "./common.php";
require "templates/header.php";
?>
<h2>Forgot Who I Have</h2>
<?php
if (isset($_GET['firstname'])) {


  try {
    $connection = new PDO($dsn, $username, $password, $options);
    $firstname = $_GET['firstname'];

    $sql = "SELECT * FROM " . $table . " WHERE firstname = :firstname";
    $statement = $connection->prepare($sql);
    $statement->bindValue(':firstname', $firstname);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }

  try {
    $connection2 = new PDO($dsn, $username, $password, $options);
    $id = $user['id'];

    $sql2 = "SELECT * FROM " . $table . " WHERE id2 = :id";
    $statement2 = $connection2->prepare($sql2);
    $statement2->bindValue(':id', $id);
    $statement2->execute();

    $user2 = $statement2->fetch(PDO::FETCH_ASSOC);
  } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
  }

if($user2){

  $from = "Santa <wishlist@domain.com>";
  $to = "{$user['firstname']} <{$user['email']}>";
  $subject = "We All Forget Sometimes..";
  $body = "You have: {$user2['firstname']}. And they want: {$user2['wishlist']}";

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

  echo "You were sent an email with info about who you have. <br>";
  // echo "You have: {$user2['firstname']}. And they want: {$user2['wishlist']} <br>";
} else {
  ?>
  <div class="red" >User doesn't Exist! </div> <br>
  <form action="" method="get">
  <label for="firstname">What's Your First Name?</label>
  <input type="text" id="firstname" name="firstname"><br><br>
  <input type="submit" value="Submit">
</form>
<?php
}

} else {
  ?>
  <form action="" method="get">
  <label for="firstname">What's Your First Name?</label>
  <input type="text" id="firstname" name="firstname"><br><br>
  <input type="submit" value="Submit">
</form>
<?php
}
?>


<a href="index.php">Back to home</a>
