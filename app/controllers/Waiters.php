<?php
class Waiters extends Controller {

    // GET /waiters/login
    public function login(){
        if(isset($_SESSION['waiter_id'])) redirect('waiters/dashboard');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = [
                'email'    => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'err'      => ''
            ];

            $waiterModel = $this->model('Waiter');
            $waiter = $waiterModel->findByEmail($data['email']);

            if($waiter){
                if($waiter->status !== 'approved'){
                    $data['err'] = 'Your account is pending admin approval.';
                    $this->view('waiters/login', $data);
                    return;
                }
                if(password_verify($data['password'], $waiter->password)){
                    $_SESSION['waiter_id']   = $waiter->id;
                    $_SESSION['waiter_name'] = $waiter->name;
                    redirect('waiters/dashboard');
                } else {
                    $data['err'] = 'Incorrect password';
                    $this->view('waiters/login', $data);
                }
            } else {
                $data['err'] = 'No waiter found with that email';
                $this->view('waiters/login', $data);
            }
        } else {
            $this->view('waiters/login', ['email'=>'','password'=>'','err'=>'']);
        }
    }

    // GET /waiters/register
    public function register(){
        if(isset($_SESSION['waiter_id'])) redirect('waiters/dashboard');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = [
                'name'      => trim($_POST['name']),
                'email'     => trim($_POST['email']),
                'password'  => trim($_POST['password']),
                'name_err'  => '',
                'email_err' => '',
                'pass_err'  => ''
            ];

            $waiterModel = $this->model('Waiter');
            if(empty($data['name'])) $data['name_err'] = 'Name required';
            if(empty($data['email'])) $data['email_err'] = 'Email required';
            if($waiterModel->findByEmail($data['email'])) $data['email_err'] = 'Email already registered';
            if(strlen($data['password']) < 6) $data['pass_err'] = 'Min 6 characters';

            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['pass_err'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if($waiterModel->register($data)){
                    flash('waiter_registered', 'Registration submitted! Wait for admin approval.', 'toast toast-info');
                    redirect('waiters/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('waiters/register', $data);
            }
        } else {
            $this->view('waiters/register', ['name'=>'','email'=>'','password'=>'','name_err'=>'','email_err'=>'','pass_err'=>'']);
        }
    }

    // GET /waiters/dashboard
    public function dashboard(){
        if(!isset($_SESSION['waiter_id'])) redirect('waiters/login');
        $waiterModel = $this->model('Waiter');
        $earnings    = $waiterModel->getEarnings($_SESSION['waiter_id']);
        $active      = $waiterModel->getAssignedOrders($_SESSION['waiter_id']);
        $completed   = $waiterModel->getCompletedOrders($_SESSION['waiter_id']);
        $data = [
            'active_orders'    => $active,
            'completed_orders' => $completed,
            'earnings'         => $earnings
        ];
        $this->view('waiters/dashboard', $data);
    }

    // POST /waiters/updateStatus
    public function updateStatus(){
        if(!isset($_SESSION['waiter_id'])) redirect('waiters/login');
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $order_id = (int)$_POST['order_id'];
            $status   = $_POST['status'];
            $allowed  = ['accepted','preparing','serving','completed'];
            if(!in_array($status, $allowed)){
                echo json_encode(['success'=>false]);
                return;
            }
            $orderModel = $this->model('Order');
            $order = $orderModel->getOrderById($order_id);
            // Only allow if this waiter owns this order
            if($order && $order->waiter_id == $_SESSION['waiter_id']){
                $orderModel->updateStatus($order_id, $status);
                // If completed, free the table
                if($status === 'completed'){
                    $tableModel = $this->model('TableModel');
                    $tableModel->freeTable($order->table_id);
                }
                echo json_encode(['success'=>true]);
            } else {
                echo json_encode(['success'=>false,'msg'=>'Not authorized']);
            }
        }
    }

    // GET /waiters/logout
    public function logout(){
        unset($_SESSION['waiter_id'], $_SESSION['waiter_name']);
        session_destroy();
        redirect('waiters/login');
    }
}
