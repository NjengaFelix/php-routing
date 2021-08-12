<?php

class Contact {

    public function execute(array $params = [])
    {
        $username = $params['username'] ?? 'Guest';
        require_once __DIR__ . '/../templates/contact-view.php';
    }

}