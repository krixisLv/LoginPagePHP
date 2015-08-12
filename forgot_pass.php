<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 3:19 PM
 */

include_once 'dbconnect.php';

if( isset($_POST['btn-generate-pass']) && isset($_POST['email']) ) {

    $email = $_POST['email'];
    $query = ("SELECT id FROM users WHERE email=".$conn->quote($email));
    $stmt = $conn->prepare($query);
    $stmt = $conn->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($row['id'])){

        $new_pass_hash = md5(rand(0, 1000));

        $query = "UPDATE users SET password = '".$new_pass_hash. "', pass_expires = NOW() WHERE id = " . $row['id'];

        $stmt = $conn->prepare($query);
        $stmt = $stmt->execute();

        if ($stmt) {

            $to = $email;
            $subject = 'Password recovery | temporary password';
            $message = 'New temporary password generated!/n' .
                'Please change your password immediately after signing in for safety reasons.\n' .
                '\n' .
                '------------------------\n' .
                'New password: "$new_pass_hash"\n' .
                '------------------------\n';

            $headers = 'From:noreply@localhost.com' . "\r\n";
            mail($to, $subject, $message, $headers);

            ?>
            <script>
                alert('New temporary password has been sent to your email');
            </script>
            <?php

        } else {
            ?>
            <script>
                alert('Error while trying to generate temporary password');
            </script>
            <?php
        }

    } else {
        ?>
        <script>
            alert('The email you have provided is not registered in this system');
        </script>
        <?php
    }

} else if(isset($_POST['btn-generate-pass'])) {
    ?>
    <script>
        alert('Invalid data given, password recovery was not possible');
    </script>
    <?php
}
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div id="header">
    <div id="right">
        <div id="content">
            <a href="index.php">Back to home page</a>
        </div>
    </div>
</div>
<div id="body">
    <form method="post">
        <table align="center" width="30%" border="0">
            <tr>
                <td>
                    <input type="text" name="email" placeholder="Email" required />
                </td>
            </tr>
            <tr>
                <td>
                    <button type="submit" name="btn-generate-pass">Generate new password</button>
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>
