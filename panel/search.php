<?php
include_once('../includes/core.php');
if ($_SESSION['level'] > 0) {
    mod($action = 'fetch');
} else {
    fetchFiles();
}
