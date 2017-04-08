<?php

$app->get('/','HomeController:index')->setName('home');
$app->get('/register','HomeController:register')->add('CSRFprotect:generate')->setName('register');
$app->post('/register','AuthController:register')->add('CSRFprotect:check')->setName('register.post');
$app->get('/login','HomeController:login')->add('CSRFprotect:generate')->setName('login');
$app->post('/login','AuthController:login')->add('CSRFprotect:check')->setName('login.post');
$app->get('/logout','AuthController:logout')->setName('logout');
$app->get('/activate','AuthController:activate')->setName('activate');
$app->get('/recover','AuthController:recover')->setName('recover');
$app->get('/forgotpassword','HomeController:forgotpassword')->add('CSRFprotect:generate')->setName('forgotpassword');
$app->post('/forgotpassword','AuthController:forgotpassword')->add('CSRFprotect:check')->setName('forgotpassword.post');
$app->get('/changepassword','HomeController:changepassword')->add('CSRFprotect:generate')->setName('changepassword');
$app->post('/changepassword','AuthController:changepassword')->add('CSRFprotect:check')->setName('changepassword.post');