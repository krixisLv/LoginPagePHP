<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 7/11/2015
 * Time: 2:04 PM
 */

session_start();
if(isset($_SESSION['user'])!="")
{
    header("Location: user_page.php");
}
include_once 'dbconnect.php';

if(isset($_POST['btn-signup']))
{
    try {
        $firstname = $conn->quote($_POST['firstname']);
        $lastname = $conn->quote($_POST['lastname']);
        $email = $conn->quote($_POST['email']);
        $password = md5($_POST['password']);

        $query = "INSERT INTO users (firstname, lastname, email, password, pass_expires) " .
            "VALUES ($firstname, $lastname, $email, '$password', NOW())";

        $stmt = $conn->prepare($query);
        $stmt = $stmt->execute();

        if ($stmt) {
            $user_id = $conn->lastInsertId();
            $hash = md5(rand(0, 1000));

            $query = "INSERT INTO user_verification (user_id, verify_hash) " .
                "VALUES ('$user_id','$hash')";

            $stmt = $conn->prepare($query);
            $stmt = $stmt->execute();

            $to = $email;
            $subject = 'Email Verification';
            $message = 'Thanks for signing up!/n' .
                'Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.\n' .
                '\n' .
                '------------------------\n' .
                'Username: "$name"\n' .
                '------------------------\n' .
                'Please click this link to activate your account:\n' .
                'localhost/login/verify.php?email=' . $email . '&hash=' . $hash . '\n';

            $headers = 'From:noreply@localhost.com' . "\r\n";
            mail($to, $subject, $message, $headers);

            ?>
            <script>
                alert('successfully registered, pending email verification ');
            </script>
            <?php

            header("Location: index.php");
            exit();
        } else {
            ?>
            <script>
                alert('error while processing data');
            </script>
            <?php
        }
    } catch(PDOException $e) {
        ?>
        <script>
            alert('error while processing data');
        </script>
        <?php
    }
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>LoginSystem</title>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
    <script src="http://ajax.microsoft.com/ajax/jquery.validate/1.9/additional-methods.js"></script>
</head>
<body>
    <div id="login-form">
        <form id="form" method="post" action="register.php">
            <table align="center" width="30%" border="0">
                <tr>
                    <td>
                        <input id="firstname" type="text" name="firstname" placeholder="First Name" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="lastname" type="text" name="lastname" placeholder="Last Name" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="email" type="email" name="email" placeholder="Email" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input id="password" type="password" name="password" placeholder="Password" required/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" name="btn-signup">Sign Up</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="index.php">Sign In</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

<script>
    var response;
    $.validator.addMethod(
        "emailRegistered", function(value) {
            console.log("emailRegistered");
            $.ajax({
                type: "POST",
                url: "check_email.php",
                data: {email: value},
                dataType:"json",
                success: function(msg)
                {
                    //If email exists, set response to true
                    console.log(msg);
                    var data = JSON.parse(msg);
                    response = ( data.email == 'true' ) ? false : true;
                    //return true;
                }
            });
            //return false;
            return response;
        },
        "Email is Already Registered"
    );

    $(document).ready(function() {

        $("#form").validate({

            // Specify the validation rules
            rules: {
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    emailRegistered: true
                },
                password: {
                    required: true,
                    minlength: 5
                }
            },

            // Specify the validation error messages
            messages: {
                firstname: {
                    required: "Please enter your first name"
                },
                lastname: {
                    required: "Please enter your last name"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                email: {
                    required: "Email is required",
                    email: "Email address is not valid",
                    emailRegistered: "Email already registered"
                }
            },

            submitHandler: function(form) {
                console.log("submit");
                //form.submit();
            },

        });

    });
</script>