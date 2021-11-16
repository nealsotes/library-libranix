<?php
require "../db_connect.php";
require "../message_display.php";
require "../components/header.php";
?>

<html>

<head>
    <title>Libranix | Sign up Faculty</title>
    <link href="../css/form_styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
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
                <div class="error-message" id="error-message">
                    <p id="error"></p>
                </div>
                <div class="card">
                    <header class="card-header">
                        <h4 class="card-title mt-2">Faculty Form</h4>
                    </header>
                    <article class="card-body">
                        <form id="form" action="#" method="POST">
                            <div class="form-row">
                                <div class="col form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" placeholder="">
                                </div> <!-- form-group end.// -->
                                <div class="col form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder=" ">
                                </div>
                                <div class="col form-group">
                                    <label for="password">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" placeholder=" ">
                                </div>
                                <div class="col form-group">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control" placeholder=" ">
                                </div>
                                <div class="col form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control" placeholder=" ">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" class="form-control" type="email">
                                <small class="form-text text-muted">We'll never share your email with anyone else.</small>

                            </div>
                            <div class="col form-group">
                                <label>Phone No.</label>
                                <input type="tel" name="phone" placeholder="09*********">
                            </div>
                            <div class="col form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" placeholder=" ">
                            </div>
                            <div class="form-group">
                                <button style="margin-top: 10px;" type="submit" value="register" name="register" class="btn btn-success"> Sign up </button>
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
<style>
    .error {
        color: red;
    }
</style>
<script>
    $(document).ready(function() {
        $('#form').validate({
            rules: {
                username: {
                    required: true,

                },
                password: {
                    required: true,
                    minlength: 8
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password"
                },
                firstname: {
                    required: true,
                    minlength: 2
                },
                lastname: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true,

                },

                phone: {
                    required: true,
                    minlength: 11
                },
                address: {
                    required: true
                },


            },
            messages: {
                username: "Please enter a username",
                firstname: {
                    required: "Please enter  first name",
                },
                lastname: {
                    required: "Please enter last name ",

                },
                password: {
                    required: "Please enter a password",

                },
                confirm_password: {
                    required: 'Please enter confirm password.',
                    equalTo: 'Confirm Password do not match with Password.',
                },
                email: {
                    required: "Please enter a email"
                },
                phone: {
                    required: "Please enter phone number",
                },
                address: {
                    required: "Please enter address"
                },
                balance: {
                    required: "Please enter initial balance"
                },
                course: {
                    required: "Please select a program"
                }


            },
            submitHandler: function(form) {
                form.submit();
            },
        });
    });
</script>
<?php
if (isset($_POST["register"])) {
}
?>

</html>