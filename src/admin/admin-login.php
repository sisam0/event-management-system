<?php
session_start();
include "connect.php";
$loginMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //to execute for login
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        //get the user id
        $stmt = $conn->prepare("select * from admin where email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result(); //gives result set of prepare()

        if ($row = $result->fetch_assoc()) {
            // print_r($row);
            if ($pass == $row['password']) {
                $_SESSION['admin_id'] = $row['admin_id'];
                // $_SESSION['pic'] = $row['photo'];
                $_SESSION['isLoggedin'] = "true";
                header("Location: /admin/admin.php");
                exit();
            } else {
                $loginMsg = "Mismatch password!";
            }
        } else {
            $loginMsg = "Email does not exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        #error2 {
            display: none;
        }

        * {
            margin: 0;
        }

        .parent {
            height: 587px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 1280px;
            background: url(bg-login.jpg);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;

        }

        .main-body {
            border: 1px solid black;
            width: fit-content;
            padding: 30px;
            height: fit-content;
            text-align: center;
            color: beige;
            /* line-height: 3rem; */
            /* filter: blur(4px); */
            backdrop-filter: blur(10px);
            border-radius: 10px;

        }

        input {
            margin-bottom: 10px;
            width: 250px;
            padding: 5px 10px;
            text-align: left;
            border: none;
            background-color: beige;
            border-radius: 10px;
            padding: 10px;
            width: 350px;
            text-align: left;
        }

        a {
            color: beige;
        }
    </style>
</head>

<body>

    <div class="parent">
        <div class="login main-body" id="login">
            <h1>Hi! Welcome back</h1>

            <div class="hasForm">
                <form action="" method="post">
                    <input type="email" placeholder="Email" name="email"><br>
                    <input type="password" placeholder="Password" name="password"><br>
                    <span id="message"><?php echo htmlspecialchars($loginMsg); ?></span><br>
                    <input type="submit" value="Log in" name="login" style="text-align: center;">
                </form>
            </div>
        </div>

    </div>

    <script src="login.js"></script>
</body>

</html>