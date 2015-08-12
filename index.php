<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 12:54 PM
 */
session_start();
include_once 'dbconnect.php';
if(isset($_SESSION['user']))
{
    header("Location: user_page.php");
    exit();
}

if(isset($_POST['btn-login']))
{
    $email = $conn->quote($_POST['email']);
    $password = $_POST['password'];

    $query = ("SELECT id, password  FROM users WHERE email=$email");
    //echo $query;
    $stmt = $conn->prepare($query);
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //echo "password: " . $row['password'];
    $password = md5($password);
    //echo "password: " . $password;
    if( $row['password'] == $password )
    {
        // check if user email has been verified
        $query = "SELECT pending FROM user_verification WHERE user_id = " . $row['id'];
        $stmt = $conn->prepare($query);
        $stmt = $conn->query($query);
        $row2 = $stmt->fetch(PDO::FETCH_ASSOC);

        if( $row2['pending'] == 0 ) {
            $_SESSION['user'] = $row['id'];

            header("Location: user_page.php");
        } else {
            ?>
            <script>
                alert('First validate your email');
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            alert('invalid user data given');
        </script>
        <?php
    }

}
?>


<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LoginSystem</title>
</head>
<body>
    <div id="login-form">
        <form method="post">
            <table align="center" width="30%" border="0">
                <tr>
                    <td>
                        <input type="text" name="email" placeholder="Email" required />
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="password" name="password" placeholder="Password" required />
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="btn-login">Sign in</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="register.php">Sign up</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="forgot_pass.php">Forgot password</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>