<?php
require_once 'model/login_model.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_COOKIE['generic'])) {
        if ($_POST["usrname"] != '' && $_POST["password"]) {
            check_login($_POST["usrname"], $_POST["password"]);
        }
    } else {
        echo "<script>window.open('view/index_view.phtml', '_self')</script>";
    }
}

require_once 'view/login_view.phtml';
