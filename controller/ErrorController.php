<?php

class ErrorController extends Cheapskate\AppController
{
    public function error404()
    {
        $this->render('_error/404', [], 404);
    }
}