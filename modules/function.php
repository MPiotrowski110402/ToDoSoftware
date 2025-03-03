<?php
include 'C:\xampp\htdocs\ToDoSoftware\connect\db_connect.php';
include 'C:\xampp\htdocs\ToDoSoftware\connect\session.php';

    function displayTasks(){
        global $conn;
        $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] :0;
        if(isset($_GET['categoryId'])){
            $category_id = isset($_SESSION['category']) ? (int)$_SESSION['category'] :0;
            $query = "SELECT t.id, t.title, t.status, tp.level AS priority
            FROM tasks t
            JOIN task_users tu ON t.id = tu.task_id
            JOIN task_priority tp ON t.priority_id = tp.id
            WHERE tu.user_id = ? AND t.status = 'in progress' AND t.category_id = ?
            ORDER BY tp.id ASC";
            ;
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $user_id, $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $tasks = [];
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $tasks[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                    'status' => $row['status'],
                    ];
                }
            }
        }else{
            $query = "SELECT t.id, t.title, t.status, tp.level AS priority
            FROM tasks t
            JOIN task_users tu ON t.id = tu.task_id
            JOIN task_priority tp ON t.priority_id = tp.id
            WHERE tu.user_id = ? AND t.status = 'in progress'
            ORDER BY tp.id ASC";
            ;
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $tasks = [];
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $tasks[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                    'status' => $row['status'],
                    ];
                }
            }
        }
        return $tasks;
    }
    function hello(){
        global $conn;
        $user_id = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $result = $row['first_name'];
        return $result;
    }
    function displayCompletedTasks(){
        global $conn;
        $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] :0;
        if(isset($_GET['categoryId'])){
        $category_id = isset($_SESSION['category']) ? (int)$_SESSION['category'] :0;
        $query = "SELECT t.id, t.title, t.status, tp.level AS priority
        FROM tasks t
        JOIN task_users tu ON t.id = tu.task_id
        JOIN task_priority tp ON t.priority_id = tp.id
        WHERE tu.user_id = ? AND t.status = 'completed' AND t.category_id = ?
        ORDER BY tp.id ASC";
        $finishedTasks = [];
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $user_id, $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $finishedTasks[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                   'status' => $row['status'],
                ];
            }
        }
        else{
            $query = "SELECT t.id, t.title, t.status, tp.level AS priority
            FROM tasks t
            JOIN task_users tu ON t.id = tu.task_id
            JOIN task_priority tp ON t.priority_id = tp.id
            WHERE tu.user_id = ? AND t.status = 'completed'
            ORDER BY tp.id ASC";
            ;
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $tasks = [];
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $tasks[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                    'status' => $row['status'],
                    ];
                }
            }
        }
        return $finishedTasks;
        }
    }
    function displayNotCompletedTasks(){
        global $conn;
        $user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] :0;
        if(isset($_GET['categoryId'])){
            $category_id = isset($_SESSION['category']) ? (int)$_SESSION['category'] :0;
            $query = "SELECT t.id, t.title, t.status, tp.level AS priority
            FROM tasks t
            JOIN task_users tu ON t.id = tu.task_id
            JOIN task_priority tp ON t.priority_id = tp.id
            WHERE tu.user_id = ? AND t.status = 'not completed' AND t.category_id = ?
            ORDER BY tp.id ASC";
            $finishedTasks = [];
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $user_id, $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $finishedTasks[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                    'status' => $row['status'],
                    ];
                }
            }
        }
        else{
            $query = "SELECT t.id, t.title, t.status, tp.level AS priority
        FROM tasks t
        JOIN task_users tu ON t.id = tu.task_id
        JOIN task_priority tp ON t.priority_id = tp.id
        WHERE tu.user_id = ? AND t.status = 'not completed' 
        ORDER BY tp.id ASC";
        $finishedTasks = [];
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $finishedTasks[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                   'status' => $row['status'],
                ];
            }
        }
        }
        return $finishedTasks;
    }
    if(isset($_POST['button_agree'])){
        $categoryID = isset($_SESSION['category']) ? (int)$_SESSION['category'] :0;
        $task_id = isset($_POST['taskId']) ? (int)$_POST['taskId'] :0;
        $query = "UPDATE tasks SET status = 'completed' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        header('Location: ../index.php?categoryId='.$categoryID);
        exit();
    }
    if(isset($_POST['button_dismiss'])){
        $task_id = isset($_POST['taskId']) ? (int)$_POST['taskId'] :0;
        $categoryID = isset($_SESSION['category']) ? (int)$_SESSION['category'] :0;
        $query = "UPDATE tasks SET status = 'not completed' WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        header('Location: ../index.php?categoryId='.$categoryID);
        exit();
    }



?>