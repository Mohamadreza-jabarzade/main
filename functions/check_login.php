<?php
session_start();
if (!isset($_SESSION['admin'])) {
    redirect('functions/access_denied.php');
}