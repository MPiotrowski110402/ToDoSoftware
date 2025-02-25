<?php
include 'C:\xampp\htdocs\ToDoSoftware\connect\session.php';
include 'C:\xampp\htdocs\ToDoSoftware\connect\db_connect.php';
require '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('C:/xampp/htdocs/ToDoSoftware/views');
$twig = new \Twig\Environment($loader);

// Renderujemy widok logowania
echo $twig->render('register.twig');



//rejestracja 
if(isset($_POST['register'])){
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['password_confirm'];

    if($password === $confirmPassword){
        $sql = "SELECT * FROM users WHERE login = '$username'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            echo "Użytkownik o takim loginie już istnieje!";
            
        }else{
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                echo "Użytkownik o takim emailu już istnieje!";
                
            }else{
                $sql = "INSERT INTO users (first_name, last_name, login, email, password, created_at) VALUES ('$imie', '$nazwisko', '$username', '$email', '$password', NOW())";
                if(mysqli_query($conn, $sql)){
                    echo "Rejestracja przebiegła sukcesem!";
                    header("Location: login.php");
                }else{
                    echo "Wystąpił błąd podczas rejestracji! ".mysqli_error($conn);
                }
            }
        }
    }else{
        echo "Hasła nie pasują do siebie!";
        
    }
}
?>
