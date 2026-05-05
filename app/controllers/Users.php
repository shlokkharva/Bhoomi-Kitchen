<?php
class Users extends Controller {

    public function __construct(){
        // Autoload models
    }

    // GET /users/login
    public function login(){
        if(isset($_SESSION['user_id'])) redirect('orders/menu');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = [
                'email'    => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'pass_err'  => ''
            ];

            $userModel = $this->model('User');
            $loggedUser = $userModel->findUserByEmail($data['email']);

            if($loggedUser){
                if($loggedUser->password === 'legacy' || password_verify($data['password'], $loggedUser->password)){
                    // Set session
                    $_SESSION['user_id']   = $loggedUser->id;
                    $_SESSION['user_name'] = $loggedUser->name;
                    $_SESSION['user_email']= $loggedUser->email;
                    redirect('orders/menu');
                } else {
                    $data['pass_err'] = 'Incorrect password';
                    $this->view('users/login', $data);
                }
            } else {
                $data['email_err'] = 'No user found with that email';
                $this->view('users/login', $data);
            }
        } else {
            $data = ['email'=>'','password'=>'','email_err'=>'','pass_err'=>''];
            $this->view('users/login', $data);
        }
    }

    // GET /users/register
    public function register(){
        if(isset($_SESSION['user_id'])) redirect('orders/menu');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = [
                'name'      => trim($_POST['name']),
                'email'     => trim($_POST['email']),
                'password'  => trim($_POST['password']),
                'confirm'   => trim($_POST['confirm_password']),
                'name_err'  => '',
                'email_err' => '',
                'pass_err'  => '',
                'confirm_err'=> ''
            ];

            $userModel = $this->model('User');

            if(empty($data['name'])) $data['name_err'] = 'Please enter your name';
            if(empty($data['email'])) $data['email_err'] = 'Please enter your email';
            if($userModel->findUserByEmail($data['email'])) $data['email_err'] = 'Email already in use';
            if(strlen($data['password']) < 6) $data['pass_err'] = 'Password must be at least 6 characters';
            if($data['password'] !== $data['confirm']) $data['confirm_err'] = 'Passwords do not match';

            if(empty($data['name_err']) && empty($data['email_err']) && empty($data['pass_err']) && empty($data['confirm_err'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if($userModel->register($data)){
                    flash('register_success', 'You are now registered. Please login.', 'toast toast-success');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/register', $data);
            }
        } else {
            $data = ['name'=>'','email'=>'','password'=>'','confirm'=>'','name_err'=>'','email_err'=>'','pass_err'=>'','confirm_err'=>''];
            $this->view('users/register', $data);
        }
    }

    // GET /users/logout
    public function logout(){
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
        session_destroy();
        redirect(''); // back to menu/home
    }

    // GET /users/history
    public function history(){
        if(!isset($_SESSION['user_id'])) redirect('users/login');
        $orderModel = $this->model('Order');
        $data = ['orders' => $orderModel->getUserOrders($_SESSION['user_id'])];
        $this->view('users/history', $data);
    }

    // POST /users/ajaxLogin — JSON endpoint for the checkout modal
    public function ajaxLogin(){
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $email    = trim($input['email'] ?? '');
        $password = trim($input['password'] ?? '');

        if(empty($email) || empty($password)){
            echo json_encode(['success'=>false,'msg'=>'Email and password required.']); return;
        }

        $userModel  = $this->model('User');
        $loggedUser = $userModel->findUserByEmail($email);

        if($loggedUser && password_verify($password, $loggedUser->password)){
            $_SESSION['user_id']    = $loggedUser->id;
            $_SESSION['user_name']  = $loggedUser->name;
            $_SESSION['user_email'] = $loggedUser->email;
            echo json_encode(['success'=>true,'name'=>$loggedUser->name]);
        } else {
            echo json_encode(['success'=>false,'msg'=>'Incorrect email or password.']);
        }
    }

    // POST /users/ajaxRegister — JSON endpoint for the checkout modal
    public function ajaxRegister(){
        header('Content-Type: application/json');
        $input    = json_decode(file_get_contents('php://input'), true);
        $name     = trim($input['name']     ?? '');
        $email    = trim($input['email']    ?? '');
        $password = trim($input['password'] ?? '');

        if(empty($name) || empty($email) || empty($password)){
            echo json_encode(['success'=>false,'msg'=>'All fields are required.']); return;
        }
        if(strlen($password) < 6){
            echo json_encode(['success'=>false,'msg'=>'Password must be at least 6 characters.']); return;
        }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo json_encode(['success'=>false,'msg'=>'Invalid email address.']); return;
        }

        $userModel = $this->model('User');
        if($userModel->findUserByEmail($email)){
            echo json_encode(['success'=>false,'msg'=>'An account with this email already exists.']); return;
        }

        $data = [
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        if($userModel->register($data)){
            // Auto-login after register
            $newUser = $userModel->findUserByEmail($email);
            $_SESSION['user_id']    = $newUser->id;
            $_SESSION['user_name']  = $newUser->name;
            $_SESSION['user_email'] = $newUser->email;
            echo json_encode(['success'=>true,'name'=>$newUser->name]);
        } else {
            echo json_encode(['success'=>false,'msg'=>'Registration failed. Please try again.']);
        }
    }
}

