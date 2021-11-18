<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
    <title>Welcome | Librarian Panel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <link href="../css/styles.css" rel="stylesheet" />
    <style>
    #container {
        margin-top: 40px;

    }

    

    .card::after {
        display: block;
        position: absolute;
        bottom: -10px;
        left: 20px;
        width: calc(100% - 40px);
        height: 35px;
        background-color: #fff;
        -webkit-box-shadow: 0 19px 28px 5px rgba(64, 64, 64, 0.09);
        box-shadow: 0 19px 28px 5px rgba(64, 64, 64, 0.09);
        content: '';
        z-index: -1;
    }

    a.card {
        text-decoration: none;
    }

    .card {
        position: relative;
        border: 0;
        border-radius: 0;
        background-color: #fff;
        -webkit-box-shadow: 0 12px 20px 1px rgba(64, 64, 64, 0.09);
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, 0.09);
    }

    .card {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: .25rem;
    }

    .box-shadow {
        -webkit-box-shadow: 0 12px 20px 1px rgba(64, 64, 64, 0.09) !important;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, 0.09) !important;
    }

    .ml-auto,
    .mx-auto {
        margin-left: auto !important;
    }

    .mr-auto,
    .mx-auto {
        margin-right: auto !important;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    .bg-white {
        background-color: #fff !important;
    }

    .ml-auto,
    .mx-auto {
        margin-left: auto !important;
    }

    .mr-auto,
    .mx-auto {
        margin-right: auto !important;
    }

    .d-block {
        display: block !important;
    }

    img,
    figure {
        max-width: 100%;
        height: auto;
        vertical-align: middle;
    }

    .card-text {
        padding-top: 12px;
        color: #8c8c8c;
    }

    .text-sm {
        font-size: 12px !important;
    }

    p,
    .p {
        margin: 0 0 16px;
    }

    .card-title {
        margin: 0;
        font-family: "Montserrat", sans-serif;
        font-size: 18px;
        font-weight: 900;
    }

    .pt-1,
    .py-1 {
        padding-top: .25rem !important;
		color: #212529;
    }

    .head-icon {
        margin-top: 18px;
        color: #198754;
    }
    </style>
</head>

<body>
    <div id="container" class="container">
        <div class="row justify-content-center">
            <!-- <a type="button" href="pending_registrations.php" class="btn btn-danger btn-lg btn-block w-50 mx-auto">Pending registrations</a>
			<a type="button" href="pending_book_requests.php" class="btn btn-danger btn-lg btn-block w-50 mx-auto">Pending book requests</a>
			<a type="button" href="insert_book.php" class="btn btn-primary btn-lg btn-block w-50 mx-auto">Add a new book</a>
			<a type="button" href="update_copies.php" class="btn btn-primary btn-lg btn-block w-50 mx-auto">Update book copies</a>
			<a type="button" href="update_balance.php" class="btn btn-primary btn-lg btn-block w-50 mx-auto">Update member balance</a>
			<a type="button" href="due_handler.php" class="btn btn-success btn-lg btn-block w-50 mx-auto">Reminders for today</a> -->

            <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
			<script src="https://kit.fontawesome.com/fc8b5c9583.js" crossorigin="anonymous"></script>

            <section class="container pt-3 mb-3">
                <div class="row pt-5 mt-30">
                    <div class="col-lg-4 col-sm-6 mb-30 pb-5">
                        <a class="card" href="pending_registrations.php">
                            <div class="box-shadow bg-white rounded-circle mx-auto text-center"
                                style="width: 90px; height: 90px; margin-top: -20px;"><i
                                    class="fas fa-address-card fa-3x head-icon"></i></div>
                            <div class="card-body text-center">
                                <h3  href="pending_registrations.php" class="card-title pt-1">Pending registrations</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-30 pb-5">
                        <a class="card" href="pending_book_requests.php">
                            <div class="box-shadow bg-white rounded-circle mx-auto text-center"
                                style="width: 90px; height: 90px; margin-top: -20px;"><i
                                    class="fas fa-business-time fa-3x head-icon"></i></div>
                            <div class="card-body text-center">
                                <h3 class="card-title pt-1">Pending book requests</h3>
                               
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-30 pb-5">
                        <a class="card" href="update_copies.php">
                            <div class="box-shadow bg-white rounded-circle mx-auto text-center"
                                style="width: 90px; height: 90px; margin-top: -20px;"><i
                                    class="fas fa-copy fa-3x head-icon"></i></div>
                            <div class="card-body text-center">
                                <h3 class="card-title pt-1">Update book copies</h3>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-30 pb-5">
                        <a class="card"  href="insert_book.php">
                            <div class="box-shadow bg-white rounded-circle mx-auto text-center"
                                style="width: 90px; height: 90px; margin-top: -20px;"><i
                                    class="fas fa-book fa-3x head-icon"></i></div>
                            <div class="card-body text-center">
                                <h3 class="card-title pt-1">Add a new book</h3>
                              
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-30 pb-5">
                        <a class="card"  href="update_balance.php">
                            <div class="box-shadow bg-white rounded-circle mx-auto text-center"
                                style="width: 90px; height: 90px; margin-top: -20px;"><i
                                    class="fas fa-wallet fa-3x head-icon"></i></div>
                            <div class="card-body text-center">
                                <h3 class="card-title pt-1">Update member balance</h3>
                               
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6 mb-30 pb-5">
                        <a class="card" href="due_handler.php">
                            <div class="box-shadow bg-white rounded-circle mx-auto text-center"
                                style="width: 90px; height: 90px; margin-top: -20px;"><i
                                    class="fas fa-bell fa-3x head-icon"></i></div>
                            <div class="card-body text-center">
                                <h3 class="card-title pt-1">Reminders for today</h3>
                            
                            </div>
                        </a>
                    </div>
                </div>
            </section>
        </div>

    </div>
</body>

</html>