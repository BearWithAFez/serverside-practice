<?php
/**
 * Includes
 * ----------------------------------------------------------------
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';
session_start();

//Init Twig
require_once __DIR__ . '/includes/Twig/Autoloader.php';
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader);

/**
 * Database Connection
 * ----------------------------------------------------------------
 */
$db = getDatabase();

// If user has already logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('location: index.php');
    exit();
}

/**
 * Page vars
 * -----------------------------------------------------------------
 */
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$formErrors = array();

if(isset($_POST['moduleAction']) && $_POST['moduleAction'] == 'login') {
    $stmt = $db->prepare('SELECT username FROM users WHERE username = ?');
    $stmt->execute(array($username));

    if(trim($username) === '') {
        $formErrors[] = 'No username provided';
    }

    if(trim($password) === '') {
        $formErrors[] = 'No password provided';
    }

    // User does not exist
    if($stmt->rowCount() == 0) {
        $formErrors[] = 'User does not exist';
    } else {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute(array($username));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // Check password
        if(password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['logged_in'] = true;

            // Redirect to browse.php
            header('location: index.php');
            exit();
        } else {
            $formErrors[] = 'Incorrect password';
        }
    }
}

/**
 * Template rendering
 * -----------------------------------------------------------------
 */
$tpl = $twig->loadTemplate('login.twig');
echo $tpl->render(array(
        'formErrors' => $formErrors,
        'username' => $username,
        'PHP_SELF' =>  $_SERVER['PHP_SELF'],
    )
);
