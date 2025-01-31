<?php
  require 'functions.php';
  session_start();

  if(!isset($_SESSION['login'])) {
    header("location:login.php");
  }

  if($_SESSION['role'] == "User") {
    header("location:index.php");
  }

  if(isset($_GET['delete'])) {
    delete_penilaian($_GET['delete']);
  }
?>