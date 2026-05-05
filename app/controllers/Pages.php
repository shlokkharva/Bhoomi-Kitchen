<?php
class Pages extends Controller {
    public function index(){
        $data = [
            'title' => 'Welcome to Bhoomi\'s Kitchen'
        ];
        $this->view('pages/index', $data);
    }
}
