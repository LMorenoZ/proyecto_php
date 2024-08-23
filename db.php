<?php
session_start();

$conn = mysqli_connect(
  'localhost',
  'root',
  'root',
  'proyecto_db'
) or die(mysqli_error($mysqli));
