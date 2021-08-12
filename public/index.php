<?php

require_once '../classes/router.php';
require_once '../classes/contact.php';

$router = new Router();

$router->get('/php-routing/', function() {
    echo 'Home Page';
});

$router->get('/php-routing/about', function(array $params = []) {
    echo 'About Page';
    if(!empty([$params['username']])) {
    echo '<h1>'.$params['username'].'</h1>';
}
});

$router->get('/php-routing/contact', Contact::class . '::execute');

$router->post('/php-routing/contact', function($params) {
     var_dump($params);
});




$router->addNotFoundHandler(function(){
    require_once __DIR__ . '/../templates/404.php';
});

$router->run();