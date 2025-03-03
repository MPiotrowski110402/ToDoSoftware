<?php
include 'C:\xampp\htdocs\ToDoSoftware\connect\db_connect.php';
include 'C:\xampp\htdocs\ToDoSoftware\connect\session.php';


if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['category_value']) &&!empty($_POST['category_value'])){
        global $conn;
        $category = htmlspecialchars(trim($_POST['category_value']));
        $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] :0;
        $sql = "SELECT * FROM categories WHERE name = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $category, $user_id);
        mysqli_stmt_execute($stmt);
        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0){
            echo "Kategoria już istnieje!";
            exit();
        }else{
        $sql = "INSERT INTO categories (name,user_id) VALUES (?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $category, $user_id);
        mysqli_stmt_execute($stmt);
        $categoryId = mysqli_insert_id($conn);
        $_SESSION['category'] = $categoryId;
        header("Location: ../index.php?categoryId=".$categoryId);
        exit();
        }
    }
}

    function displayCategories(){
        global $conn;
        $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] :0;
        $query = "SELECT * FROM categories WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = $stmt->get_result();
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
            $categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] :0;
            $userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] :0; 
            
            $deleteCategoryQuery = "DELETE FROM categories WHERE id = ? AND user_id = ?";
            $stmt = mysqli_prepare($conn, $deleteCategoryQuery);
            mysqli_stmt_bind_param($stmt, "ii", $categoryId, $userId);
            if(mysqli_stmt_execute($stmt)){
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
            $category_id = isset($_POST['categoryId']) ? intval($_POST['categoryId']) :0;
            $task = htmlspecialchars($_POST['task']);
            $id = isset($_SESSION['id']) ? intval($_SESSION['id']):0;
            
            $query = "INSERT INTO
            tasks (title, category_id, status, user_id, created_at, priority_id) 
            VALUES (?, ?, 'in progress', ?, NOW(), 3)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sii", $task, $category_id, $id);
            mysqli_stmt_execute($stmt);
            $task_id = mysqli_insert_id($conn);
            $sql = "INSERT INTO task_users (task_id, user_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $task_id, $id);
            mysqli_stmt_execute($stmt);
            header('Location: ../index.php?categoryId='.$category_id);
            exit();
            }else{
                echo "Wybierz lub dodaj kategorię!";
            }
        }
    
    ?>
