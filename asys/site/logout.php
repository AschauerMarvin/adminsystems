<?php 
ob_start(); 
include 'headerconfig.php';
session_unset(); 
session_destroy(); 
header ("Location: login.php"); 
ob_end_flush(); 
?> 