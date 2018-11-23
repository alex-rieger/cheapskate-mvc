<?php

class HomeController extends \Cheapskate\AppController
{
    public function index()
    {
        $this->render('home/index', [], 200);
    }
}