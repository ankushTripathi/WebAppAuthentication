<?php

$app->get('/','HomeController:index')->setName('home');
$app->get('/register','HomeController:register')->setName('register');
$app->post('/register','AuthController:register')->setName('register.post');