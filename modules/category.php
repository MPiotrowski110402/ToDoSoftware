<?php
include 'C:\xampp\htdocs\ToDoSoftware\connect\db_connect.php';
include 'C:\xampp\htdocs\ToDoSoftware\connect\session.php';


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['category_value']) &&!empty($_POST['category_value'])){
        global $conn;
        $category = $_POST['category_value'];
        $user_id = $_SESSION['id'];
        $sql = "SELECT * FROM categories WHERE name = '$category' AND user_id = $user_id";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            echo "Kategoria już istnieje!";
            exit();
        }else{
        $sql = "INSERT INTO categories (name,user_id) VALUES ('$category','$user_id')";
        mysqli_query($conn, $sql);
        $categoryId = mysqli_insert_id($conn);
        $_SESSION['category'] = $categoryId;
        header("Location: ../index.php?categoryId=".$categoryId);
        exit();
        }
    }
}

    function displayCategories(){
        global $conn;
        $user_id = $_SESSION['id'];
        $query = "SELECT * FROM categories WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
            $category = [];
            while($row = mysqli_fetch_assoc($result)){
                $category[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        return $category;
        }
    }
    if (isset($_GET['button_dismiss'])) {
        if (isset($_GET['categoryId'])) {
            global $conn;
            $categoryId = $_GET['categoryId'];
            $userId = $_SESSION['id']; 
            
            $deleteCategoryQuery = "DELETE FROM categories WHERE id = $categoryId AND user_id = $userId";
                if (mysqli_query($conn, $deleteCategoryQuery)) {
                    echo "Kategoria została pomyślnie usunięta.";
                    $_SESSION['category'] = null;
                    header("Location: ../index.php");
                    exit();
                } else {
                    echo "Błąd podczas usuwania kategorii: " . mysqli_error($conn);
                }  
        } 
    }


    
        if(isset($_POST['dodajTask'])) {
            global $conn;
            if(isset($_POST['categoryId']) &&!empty($_POST['categoryId'])) {
            $category_id = $_POST['categoryId'];
            $task = mysqli_real_escape_string($conn, $_POST['task']);
            $id = $_SESSION['id'];
            
            $query = "INSERT INTO tasks (title, category_id, status, user_id, created_at, priority_id) VALUES ('$task', $category_id, 'in progress', $id, NOW(), 3)";
            mysqli_query($conn, $query);
            $task_id = mysqli_insert_id($conn);
            $sql = "INSERT INTO task_users (task_id, user_id) VALUES ($task_id, $id)";
            mysqli_query($conn, $sql);
            header('Location: ../index.php?categoryId='.$category_id);
            exit();
            }else{
                echo "Wybierz lub dodaj kategorię!";
            }
        }
    
    ?>
