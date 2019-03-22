<?php

require_once '../constants.php';

$request = rtrim($_SERVER['REQUEST_URI'], '/');

switch ($request) {
    case '' :
        $paginaAtual = 'Início';
        require PATH_ABS . '/view/pages/home.php';
        break;

    case '/proglinear1' :
        $paginaAtual = 'Programação Linear';
        require PATH_ABS . '/view/pages/proglinear1.php';
        break;

    case '/proglinear2' :
        $paginaAtual = 'Programação Linear';
        require PATH_ABS . '/view/pages/proglinear2.php';
        break;

    default:
        $paginaAtual = '404';
        require PATH_ABS . '/view/pages/404.php';
        break;
}