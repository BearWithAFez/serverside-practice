<?php

    // @TODO: Insert showDbError Here
/**
 * Redirects to the error handling page
 * @param string $type
 * @param object $dbhandler
 * @return void
 */
function showDbError($type, $msg) {

    // debug activated
    if (DEBUG === true) {

        switch($type) {
            case 'connect':
            case 'query':
                echo $msg;
                break;
            default:
                echo 'There was an unknown error while communicating with the database';
                break;
        }
    }

    // debug not activated
    else {

        // Log the error
        file_put_contents(ERROR_LOG, PHP_EOL . (new DateTime())->format('Y-m-d H:i:s') . ' : ' . $msg, FILE_APPEND);

        // The referrerd page will show a proper error based on the $_GET parameters
        header('location: error.php?type=db&detail=' . $type);
        exit();
    }

    // stop the execution of the script !!
    exit();
}

function getDatabase() {
    try {
        $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
        showDbError('connect', $exception->getMessage());
    }

    return $db;
}





// EOF