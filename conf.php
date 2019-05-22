<?php 
ob_start();


const HOST = '127.0.0.1';
const UNAME = 'root';
const PASS = '';
const DBNAME = 'blog';

try{

$conn = new PDO('mysql:host='.HOST.';dbname='.DBNAME,UNAME,PASS);

}catch(PDOException $e){

    die('Sorry, could not connect.');

}

session_start();

