<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 2:43 PM
 */

include_once 'dbconnect.php';

$message = "";

if( isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash']) ){

    $email = $conn->quote($_GET['email']);
    $hash = $conn->quote($_GET['hash']);

    $query = "SELECT u.id id FROM users u ".
            "JOIN user_verification uv ON u.id = uv.user_id ".
            "WHERE u.email = $email AND uv.verify_hash = $hash AND uv.pending = 1";

    $stmt = $conn->prepare($query);
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $user_id = $row['id'];

    if( $user_id ){

        $query = "UPDATE user_verification SET pending = 0 WHERE user_id = $user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $query = "UPDATE users SET pass_expires = NOW() WHERE id = $user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $message = "Email successfully verified!";
    } else {
        $query = "SELECT u.id id FROM users u ".
            "JOIN user_verification uv ON u.id = uv.user_id ".
            "WHERE u.email = $email AND uv.verify_hash = $hash AND uv.pending = 0";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $user_id = $stmt->fetchColumn();

        if($user_id){
            $message = "Email verification already done!";
        } else {
            $message = "Email verification impossible, invalid data given!";
        }
    }
} else {
    header("Location: index.php");
    exit();
}

if( $user_id ){
    ?>
    <!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>LoginSystem</title>
    </head>
    <body>
        <div align="center" width="30%" border="0">
            <div><?php echo $message ?></div>
            <a href="index.php">Back to home page</a>
        </div>
    </body>
    </html>
<?php
}