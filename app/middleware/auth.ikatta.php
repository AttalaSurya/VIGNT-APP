<?php

class AuthMiddleware extends Middleware
{
    public function handle()
    {
        if (!isset($_SESSION['vignt_user_id'])) {
            header('Location: /login');
            exit();
        }
    }
}
