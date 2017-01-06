<?php

$app->get('/','HomeController:index')->setName('home');
$app->get('/flash','HomeController:flash');