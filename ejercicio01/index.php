<?php

require_once 'config.php';

if (estaLogueado()) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit();
?>