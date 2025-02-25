<?php
include 'C:\xampp\htdocs\ToDoSoftware\connect\session.php';
include 'C:\xampp\htdocs\ToDoSoftware\connect\db_connect.php';
require '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('C:/xampp/htdocs/ToDoSoftware/views');
$twig = new \Twig\Environment($loader);

// Renderujemy widok logowania
echo $twig->render('login.twig');


if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    global $conn;
    $query = "SELECT * FROM users WHERE login = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['login'];
        $_SESSION['zalogowany'] = true;
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        header('Location: ../index.php');
        exit();
    }
}
?>
