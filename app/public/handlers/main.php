<?php

session_start();

if (isset($_SESSION['id'])) {
    require_once './views/main.phtml';
} else {
    header('Location: /signin');
}