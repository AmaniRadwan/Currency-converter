<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: homepage.html");
} else {
    header("Location: login.html");
}
exit();
?>