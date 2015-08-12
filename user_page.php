<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 2:03 PM
 */
session_start();
include_once 'dbconnect.php';

if(!isset($_SESSION['user'])) {
    header("Location: index.php");
}

if( isset($_POST['btn-change-pass']) && isset($_POST['old_password']) && isset($_POST['new_password']) ) {

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $query = ("SELECT password  FROM users WHERE id=".$_SESSION['user']);
    //echo $query;
    $stmt = $conn->prepare($query);
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //echo "password: " . $row['password'];
    $old_password = md5($old_password);
    $new_password = md5($new_password);
    //echo "password: " . $password;
    if( $row['password'] == $old_password ) {
        $query = "UPDATE users SET password = '".$new_password. "', pass_expires = NOW() WHERE id = " . $_SESSION['user'];

        $stmt = $conn->prepare($query);
        $stmt = $stmt->execute();

        if ($stmt) {
            ?>
            <script>
                alert('Password changed successfully');
            </script>
            <?php
        } else {
            ?>
            <script>
                alert('Invalid password change details');
            </script>
            <?php
        }
    }

} else if(isset($_POST['btn-change-pass'])){
    ?>
    <script>
        alert('Invalid password change details!');
    </script>
    <?php
}

$query = "SELECT * FROM users WHERE id = ".$_SESSION['user'];

$stmt = $conn->prepare($query);
$stmt = $conn->query($query);
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome - <?php echo $userRow['email']; ?></title>
</head>
<body>
<div id="header">
    <div id="right">
        <div id="content">
            hi, <?php echo $userRow['firstname']; ?>,&nbsp;<a href="logout.php?logout">Sign Out</a>
        </div>
    </div>
</div>

<div id="body">
    <form method="post">
        <table align="center" width="30%" border="0">
            <tr>
                <td>
                    <p>First name: <?php echo $userRow['firstname']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Last name: <?php echo $userRow['lastname']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p>Email: <?php echo $userRow['email']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    Old pass: <input id="old_password" type="password" name="old_password" placeholder="old password" required/>
                </td>
            </tr>
            <tr>
                <td>
                    New pass: <input id="new_password" type="password" name="new_password" placeholder="new password" required />
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" name="btn-change-pass">Change password</button>
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>