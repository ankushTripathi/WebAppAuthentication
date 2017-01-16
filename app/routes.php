<?php

$app->get('/','HomeController:index')->setName('home');
$app->get('/register','HomeController:register')->setName('register');
$app->post('/register','AuthController:register')->setName('register.post');
$app->get('/login','HomeController:login')->setName('login');
$app->post('/login','AuthController:login')->setName('login.post');