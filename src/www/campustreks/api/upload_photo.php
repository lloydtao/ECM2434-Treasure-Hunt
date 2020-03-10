<?php
/**
 * Script for handling photo upload
 *
 * @author Marek Tancak
 * @contributor Jakub Kwak
 */

/**
 * Compresses and moves photo
 * @param $source
 * @param $destination
 * @param $quality
 * @author Marek Tancak
 */
function compressImage($source, $destination, $quality)
{

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg')
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif')
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png')
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);
}

/**
 * Echos error response
 * @param string $error
 * @author Jakub Kwak
 */
function errorResponse(string $error)
{
    echo json_encode([
        'status' => 'error',
        'message' => $error,
    ]);
    exit;
}

/**
 * Echos success response
 * @param string $message
 * @author Jakub Kwak
 */
function successResponse(string $message = null)
{
    echo json_encode([
        'status' => 'ok',
        'message' => $message,
    ]);
    exit;
}

/**
 * Sanitises data
 * @param $data
 * @return string
 */
function makeSafe($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_FILES['image'])) {
    $errors = array();
    $name = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];
    $nameExp = explode('.', $name);
    $ext = strtolower(end($nameExp));
    $allowedExt = array('jpg', 'jpeg', 'png');

    session_start();
    $huntSessionID = $_SESSION['gameID'];
    $teamName = $_SESSION['teamName'];

    if (in_array($ext, $allowedExt)) {
        $path = "../image_uploads/" . $huntSessionID . $teamName . '-' . $_REQUEST['objective_id'] . '.jpg';
        compressImage($tmp, $path, 50);
        //move_uploaded_file($tmp,$path);

        $json_data = file_get_contents('../hunt_sessions/' . $huntSessionID . '.json');
        $hunt_session_data = json_decode($json_data, true);
        $objective_id = makeSafe($_REQUEST['objective_id']);
        $hunt_session_data['teams'][$teamName]['objectives']['photo'][$objective_id]['completed'] = true;
        $hunt_session_data['teams'][$teamName]['objectives']['photo'][$objective_id]['path'] = $path;

        file_put_contents('../hunt_sessions/' . $huntSessionID . '.json', json_encode($hunt_session_data));
        successResponse("File uploaded");
    } else {
        errorResponse("Unsupported file format");
    }
}