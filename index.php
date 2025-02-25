<?php
include 'connect/session.php';
include 'connect/db_connect.php';

require 'vendor/autoload.php';
require 'modules/function.php';
require 'modules/category.php';
if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true) {
    error_reporting(0);
    if(isset($_GET['categoryId'])) {
        $_SESSION['category' ] = $_GET['categoryId'];
    }

$user = hello();
$tasks = displayTasks();
$completedTasks = displayCompletedTasks();
$notCompletedTasks = displayNotCompletedTasks();
$categoryList = displayCategories();
if(isset($_GET['categoryId'])) {
$categoryId = $_GET['categoryId'];
}
$allTasks = [
    'tasks' => $tasks,
    'completedTasks' => $completedTasks,
    'notCompletedTasks' => $notCompletedTasks,
    'displayCategories' => $categoryList
];

$loader = new \Twig\Loader\FilesystemLoader('views');
$twig = new \Twig\Environment($loader);

echo $twig->render('header.twig', ['user' => $user]);
echo $twig->render('tasksList.html.twig', [
                                            'allTasks' => $allTasks,
                                             'categoryId' => $categoryId,
                                             'session_category' => $_SESSION['category'],
                                            ]);
echo $twig->render('footer.twig');
}else{
    $loader = new \Twig\Loader\FilesystemLoader('views');
    $twig = new \Twig\Environment($loader);
    header('Location: modules/login.php');
    exit();
}

?>
