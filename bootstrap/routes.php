<?php

$app->get('/','HomeController:index')->setName('home');
$app->get('/register','HomeController:register')->add('CSRFprotect:generate')->setName('register');
$app->post('/register','AuthController:register')->add('CSRFprotect:check')->setName('register.post');
$app->get('/login','HomeController:login')->add('CSRFprotect:generate')->setName('login');
$app->post('/login','AuthController:login')->add('CSRFprotect:check')->setName('login.post');
$app->get('/logout','AuthController:logout')->setName('logout');
$app->get('/activate','AuthController:activate')->setName('activate');