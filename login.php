<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        #error2{
            display: none;
        }
        * {
            text-align: center;
        }

        .register {
            display: none;
        }

        input {
            margin-bottom: 10px;
            width: 250px;
            padding: 5px 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php
    include "connect.php";
    $message = "";
    $loginMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //to execute for registration
        if (isset($_POST['register'])) {
            $email = $_POST['email'];
            $pass = $_POST['Mpassword'];
            $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $open_email = $_POST['Oemail'];
            $folder = "photos/user/";
            $target_file = $folder . basename($_FILES["pic"]["name"]);

            if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
                $photo = $target_file; //this saved the path of target file in photo
            } else {
                echo "Sorry! Your file could not be uploaded!";
            }

            //check if email already exists
            $checkemail = $conn->prepare("select email from user where email = ?");
            $checkemail->bind_param("s", $email);
            $checkemail->execute();
            $checkemail->store_result();

            if ($checkemail->num_rows() > 0) {
                $message = "Email already exists.";
            } else {
                $stmt = $conn->prepare("insert into user(email, password, fname, lname, open_email, pic)
                            values (?,?,?,?,?,?)");
                $stmt->bind_param("ssssss", $email, $hash_pass, $fname, $lname, $open_email, $photo);

                if ($stmt->execute()) {
                    $message = "Account created!";
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
            $checkemail->close();
        }

        //to execute for login
        if (isset($_POST['login'])) {
            $email = $_POST['email'];
            $pass = $_POST['password'];

            //get the user id
            $stmt = $conn->prepare("select user_id, password from user where email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result(); //gives result set of prepare()
            if ($row = $result->fetch_assoc()) {
                if (password_verify($pass, $row['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['name'] = $row['fname'];
                    if ($email == "admin@gmail.com") {
                        header("Location:http://localhost/event-mgt/admin/admin.php");
                    } else {
                        header("Location:http://localhost/event-mgt/homepg.php");
                    }
                } else {
                    $loginMsg = "Mismatch password!";
                }
            } else {
                $loginMsg = "Email does not exist.";
            }
        }
    }
    ?>

    <div class="parent">
        <div class="login" id="login">
            <h1>Welcome Back!</h1>
            <h3>If you don't have an account you can <a href="#" id="showRegister">Register account.</a></h3>
            <div class="hasForm">
                <form action="" method="post">
                    <input type="email" placeholder="Email" name="email"><br>
                    <input type="text" placeholder="Password" name="password"><br>
                    <span id="message"><?php echo htmlspecialchars($loginMsg); ?></span>
                    <input type="submit" value="Log in" name="login">
                </form>
            </div>
        </div>

        <div class="register" id="register">
            <h1>Welcome!</h1>
            <h3>Start by building your account. To book your events and view real time updates. If you have an account then you can <a href="#" id="showLogin">Log in</a>
            </h3>
            <div class="regForm">
                <form action="" method="post" enctype="multipart/form-data">
                    <p id="error1"></p>
                    <label for="">Email:</label> <input type="email" placeholder="ram7872@gmail.com" name="email" id="email"><br>
                    <label for="">Password:</label> <input type="text" placeholder="'Your password" name="Mpassword" id="Mpassword"><br>
                    <p id="error2"></p><br>
                    <label for="">Confirm Password:</label> <input type="text" placeholder="Confirm password" name="Cpassword" id="Cpassword"><br>
                    <label for="">First Name:</label><input type="text" placeholder="Ram" name="fname"><br>
                    <label for="">Last Name:</label><input type="text" placeholder="Lama" name="lname"><br>
                    <p>This email will be visible to the service provider.</p>
                    <label for="">Email:</label><input type="email" placeholder="ram6567@gmail.com" name="Oemail" id="Oemail"><br>
                    <label for="">Photo:</label><input type="file" name="pic"><br>
                    <input type="submit" value="Create account" name="register">
                </form>
            </div>
        </div>
    </div>

    <script src="login.js"></script>
</body>

</html>