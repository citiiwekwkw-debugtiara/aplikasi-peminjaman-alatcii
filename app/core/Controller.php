<?php

class Controller {
    public function view($view, $data = []) {
        require_once APPPATH . '/views/' . $view . '.php';
    }

    public function model($model) {
        require_once APPPATH . '/models/' . $model . '.php';
        return new $model;
    }
}
