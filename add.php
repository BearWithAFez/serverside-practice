<?php
/**
 * Includes
 * ----------------------------------------------------------------
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';
session_start();

$basePath = __DIR__ . DIRECTORY_SEPARATOR . 'files/covers'; // C:\wamp\www\vn.an\labo03\images
$images = array(); // An array which will hold all our images
$extensions = array (
    'jpg',
    'png',
    'gif',
);

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

// If user is not logged in
if(!isset($_SESSION['logged_in'])) {
    var_dump($_SESSION);
    header('location: index.php');
    exit();
} else {
    $logged_in = true;
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

/**
 * Page init handling
 * ----------------------------------------------------------------
 */

$stmt = $db->prepare('SELECT * FROM topics');
$stmt->execute();
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Form submission handling
 * -----------------------------------------------------------------
 */
$title = isset($_POST['title']) ? $_POST['title'] : '';
$numpages = isset($_POST['numpages']) ? (int) $_POST['numpages'] : '';
$topic_id = isset($_POST['topic_id']) ? (int) $_POST['topic_id'] : '';
$coverphoto = isset($_FILES['coverphoto']['name']) ? $_FILES['coverphoto']['name'] : '';
$formErrors = array();

if (isset($_POST['moduleAction']) && $_POST['moduleAction'] == 'add') {

    if (empty($coverphoto)) {
        $formErrors[] = 'File is empty';
    }

    if (empty($numpages) || $numpages < 0) {
        $formErrors[] = 'Numpages not filled in';
    }

    if (empty($topic_id)) {
        $formErrors[] = 'No topic chosen';
    }

    if (empty($title)) {
        $formErrors[] = 'Title is empty';
    }

    // Lets process
    if (sizeof($formErrors) == 0) {

        // Check the extension
        $fileInfo = new SplFileInfo($coverphoto);

        if(in_array($fileInfo->getExtension(), $extensions)) {
            $cover_extension = $fileInfo->getExtension();

            // Insert into database
            $stmt = $db->prepare('INSERT INTO books(title, numpages, user_id, topic_id, cover_extension, added_on) VALUES (?,?,?,?,?,?)');
            $stmt->execute(array($title, $numpages, $user_id, $topic_id, $cover_extension, (new DateTime())->format('Y-m-d H:i:s')));

            // Check if query succeeded
            if($stmt->rowCount() != 0) {

                // Fetch last inserted id
                $stmt = $db->prepare('SELECT id FROM books ORDER BY id DESC LIMIT 1');
                $stmt->execute();
                $id = $stmt->fetch(PDO::FETCH_ASSOC);

                // Create the correct filename for the image to store as
                $file =  $id['id'] . '.' . $fileInfo->getExtension();

                // Fetch temp file
                $tmp_name = $_FILES['coverphoto']['tmp_name'];


                if (move_uploaded_file($tmp_name, $basePath . DIRECTORY_SEPARATOR . $file)) {
                    header('location: index.php');
                    exit();
                } else {
                    $formErrors[] = 'File could not be uploaded';
                }

            } else {
                $formErrors[] = 'File could not be uploaded';
            }
        }
        else {
            $formErrors[] = 'Incorrect extension';
        }
    } else {
        $formErrors[] = 'File does not exist';
    }
}

/**
 * Template rendering
 * -----------------------------------------------------------------
 */
$tpl = $twig->loadTemplate('add.twig');
echo $tpl->render(array(
        'formErrors' => $formErrors,
        'topics' => $topics,
        'numpages' => $numpages,
        'title' => $title,
        'topic_selected' => $topic_id,
        'username' => $username,
        'PHP_SELF' =>  $_SERVER['PHP_SELF'],
        'logged_in' => $logged_in,
    )
);
