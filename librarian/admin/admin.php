<?php
require "../db_connect.php";
require "../message_display.php";
require "../components/header.php";
?>

<html>

<head>
    <title>Libranix | Sign up Student</title>

    <link href="../css/form_styles.css" rel="stylesheet" />
    <style>
        #form-sign {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>

</head>

<body>
    <div class="container">

        <div id="form-sign" class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <header class="card-header">
                        <h4 class="card-title mt-2">Sign up</h4>
                    </header>
                    <article class="card-body">
                        <form method="POST" action="#">
                            <div class="form-row">
                                <div class="col form-group">
                                    <label>Username</label>
                                    <input type="text" name="m_user" class="form-control" placeholder="">
                                </div> <!-- form-group end.// -->
                                <div class="col form-group">
                                    <label>Password</label>
                                    <input type="password" name="m_pass" class="form-control" placeholder=" ">
                                </div>
                                <div class="col form-group">
                                    <label>Name</label>
                                    <input type="text" name="m_name" class="form-control" placeholder=" ">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input name="m_email" class="form-control" type="email">
                                <small class="form-text text-muted">We'll never share your email with anyone else.</small>

                            </div>
                            <div class="form-group">
                                <label>Initial Balance</label>
                                <input name="m_balance" class="form-control">
                            </div>
                            <div class="form-group">
                                <button style="margin-top: 10px;" type="submit" value="Register" name="m_register" class="btn btn-primary btn-block"> Sign up </button>
                            </div> <!-- form-group// -->
                        </form>
                    </article> <!-- card-body end .// -->
                    <div class="border-top card-body text-center">Have an account? <a href="index.php">Log In</a></div>
                </div>
            </div>
        </div>

    </div>
    <?php
    include_once('../components/footer.php');
    ?>
</body>

<?php
if (isset($_POST['m_register'])) {
    if ($_POST['m_balance'] < 500)
        echo error_with_field("You need a balance of at least 500 to open an account", "m_balance");
    else {
        $query = $con->prepare("(SELECT username FROM member WHERE username = ?) UNION (SELECT username FROM pending_registrations WHERE username = ?);");
        $query->bind_param("ss", $_POST['m_user'], $_POST['m_user']);
        $query->execute();
        if (mysqli_num_rows($query->get_result()) != 0)
            echo error_with_field("The username you entered is already taken", "m_user");
        else {
            $query = $con->prepare("(SELECT email FROM member WHERE email = ?) UNION (SELECT email FROM pending_registrations WHERE email = ?);");
            $query->bind_param("ss", $_POST['m_email'], $_POST['m_email']);
            $query->execute();
            if (mysqli_num_rows($query->get_result()) != 0)
                echo error_with_field("An account is already registered with that email", "m_email");
            else {
                $query = $con->prepare("INSERT INTO pending_registrations(username, password, name, email, balance) VALUES(?, ?, ?, ?, ?);");
                $query->bind_param("ssssd", $_POST['m_user'], sha1($_POST['m_pass']), $_POST['m_name'], $_POST['m_email'], $_POST['m_balance']);
                if ($query->execute())
                    echo success("Details recorded. You will be notified on the email ID provided when your details have been verified");
                else
                    echo error_without_field("Couldn\'t record details. Please try again later");
            }
        }
    }
}
?>

</html>