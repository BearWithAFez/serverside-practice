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

$logged_in = false;
$username = '';
// If user has already logged in
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    $logged_in = true;
    $username = $_SESSION['username'];
}


/**
 * Page vars
 * -----------------------------------------------------------------
 */
$topic = isset($_GET['topic']) ? $_GET['topic'] : '';

// Check if there is a GET parameter containing topic_id
if(trim($topic) !== '') {
    $stmt = $db->prepare('SELECT id FROM topics WHERE id = ?');
    $stmt->execute(array($topic));

    // If topic was not found, reroute the user back to index.php
    if($stmt->rowCount() == 0) {
        header('location: index.php');
        exit();
    }

    $stmt = $db->prepare('SELECT books.id as book_id, topics.id as topic_id, books.title as book_title, numpages, users.username, cover_extension, topics.title as topic_title FROM books
    INNER JOIN topics on books.topic_id = topics.id
    INNER JOIN users on books.user_id = users.id
    WHERE books.topic_id = ?');
    $stmt->execute(array($topic));
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT * FROM topics');
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->prepare('SELECT books.id as book_id, topics.id as topic_id, books.title as book_title, numpages, users.username, cover_extension, topics.title as topic_title FROM books
        INNER JOIN topics on books.topic_id = topics.id
        INNER JOIN users on books.user_id = users.id');
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT * FROM topics');
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
}



/**
 * Template rendering
 * -----------------------------------------------------------------
 */
$tpl = $twig->loadTemplate('index.twig');
echo $tpl->render(array(
        'books' => $books,
        'topics' => $topics,
        'logged_in' => $logged_in,
        'username' => $username
    )
);
