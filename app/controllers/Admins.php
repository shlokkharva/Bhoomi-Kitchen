<?php
class Admins extends Controller {

    private function requireAdmin(){
        if(!isset($_SESSION['admin_id'])) redirect('admins/login');
    }

    // GET /admins/login
    public function login(){
        if(isset($_SESSION['admin_id'])) redirect('admins/dashboard');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $adminModel = $this->model('Admin');
            $admin = $adminModel->findByUsername($username);

            if($admin && password_verify($password, $admin->password)){
                $_SESSION['admin_id']   = $admin->id;
                $_SESSION['admin_name'] = $admin->username;
                redirect('admins/dashboard');
            } else {
                $data = ['err' => 'Invalid credentials', 'username' => $username];
                $this->view('admins/login', $data);
            }
        } else {
            $this->view('admins/login', ['err'=>'','username'=>'']);
        }
    }

    // GET /admins/dashboard
    public function dashboard(){
        $this->requireAdmin();
        $userModel   = $this->model('User');
        $waiterModel = $this->model('Waiter');
        $orderModel  = $this->model('Order');
        $menuModel   = $this->model('Menu');
        $tableModel  = $this->model('TableModel');
        $payModel    = $this->model('Payment');

        $data = [
            'total_users'      => $userModel->countUsers(),
            'total_waiters'    => $waiterModel->countApproved(),
            'pending_waiters'  => $waiterModel->countPending(),
            'total_orders'     => $orderModel->countOrders(),
            'today_orders'     => $orderModel->countTodayOrders(),
            'total_revenue'    => $orderModel->getTotalRevenue(),
            'total_tips'       => $orderModel->getTotalTips(),
            'available_tables' => $tableModel->countAvailable(),
            'menu_items'       => $menuModel->countItems(),
            'daily_revenue'    => $orderModel->getDailyRevenue(),
            'payment_breakdown'=> $payModel->getRevenueByMethod(),
            'monthly_revenue'  => $payModel->getMonthlyRevenue(),
            'recent_orders'    => array_slice($orderModel->getAllOrders(), 0, 10)
        ];
        $this->view('admins/dashboard', $data);
    }

    // GET /admins/users
    public function users(){
        $this->requireAdmin();
        $userModel = $this->model('User');
        $data = ['users' => $userModel->getAllUsers()];
        $this->view('admins/users', $data);
    }

    // POST /admins/deleteUser/{id}
    public function deleteUser($id = null){
        $this->requireAdmin();
        if($id){
            $userModel = $this->model('User');
            try {
                $userModel->deleteUser((int)$id);
                flash('admin_msg', 'User deleted.', 'toast toast-success');
            } catch(Exception $e) {
                flash('admin_msg', 'Cannot delete user: They have past orders.', 'toast toast-danger');
            }
        }
        redirect('admins/users');
    }


    // GET /admins/waiters
    public function waiters(){
        $this->requireAdmin();
        $waiterModel = $this->model('Waiter');
        $data = ['waiters' => $waiterModel->getAllWaiters()];
        $this->view('admins/waiters', $data);
    }

    // POST /admins/approveWaiter/{id}
    public function approveWaiter($id = null){
        $this->requireAdmin();
        if($id){
            $waiterModel = $this->model('Waiter');
            $waiterModel->updateStatus((int)$id, 'approved');
            flash('admin_msg', 'Waiter approved!', 'toast toast-success');
        }
        redirect('admins/waiters');
    }

    // POST /admins/rejectWaiter/{id}
    public function rejectWaiter($id = null){
        $this->requireAdmin();
        if($id){
            $waiterModel = $this->model('Waiter');
            $waiterModel->updateStatus((int)$id, 'rejected');
            flash('admin_msg', 'Waiter rejected.', 'toast toast-danger');
        }
        redirect('admins/waiters');
    }

    // POST /admins/deleteWaiter/{id}
    public function deleteWaiter($id = null){
        $this->requireAdmin();
        if($id){
            $waiterModel = $this->model('Waiter');
            try {
                $waiterModel->deleteWaiter((int)$id);
                flash('admin_msg', 'Waiter deleted.', 'toast toast-success');
            } catch(Exception $e) {
                flash('admin_msg', 'Cannot delete waiter: They have served orders.', 'toast toast-danger');
            }
        }
        redirect('admins/waiters');
    }


    // GET /admins/menu
    public function menu(){
        $this->requireAdmin();
        $menuModel = $this->model('Menu');
        $data = [
            'items'      => $menuModel->getAllItems(),
            'categories' => $menuModel->getAllCategories()
        ];
        $this->view('admins/menu', $data);
    }

    // POST /admins/addItem
    public function addItem(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $image_url = '';
            // Handle file upload
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('food_') . '.' . $ext;
                $dest = APPROOT . '/../public/uploads/' . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $dest);
                $image_url = URLROOT . '/uploads/' . $filename;
            } elseif(!empty($_POST['image_url'])){
                $image_url = trim($_POST['image_url']);
            }

            $data = [
                'category_id' => (int)$_POST['category_id'],
                'name'        => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price'       => (float)$_POST['price'],
                'image_url'   => $image_url
            ];
            $menuModel = $this->model('Menu');
            $menuModel->addItem($data);
            flash('admin_msg', 'Menu item added!', 'toast toast-success');
        }
        redirect('admins/menu');
    }

    // POST /admins/deleteItem/{id}
    public function deleteItem($id = null){
        $this->requireAdmin();
        if($id){
            $menuModel = $this->model('Menu');
            try {
                if($menuModel->deleteItem((int)$id)){
                    flash('admin_msg', 'Item deleted.', 'toast toast-success');
                } else {
                    flash('admin_msg', 'Failed to delete item.', 'toast toast-danger');
                }
            } catch (Exception $e) {
                flash('admin_msg', 'Cannot delete item: It is referenced in past orders.', 'toast toast-danger');
            }
        }
        redirect('admins/menu');
    }

    // POST /admins/deleteCategory/{id}
    public function deleteCategory($id = null){
        $this->requireAdmin();
        if($id){
            $menuModel = $this->model('Menu');
            try {
                if($menuModel->deleteCategory((int)$id)){
                    flash('admin_msg', 'Category deleted.', 'toast toast-success');
                } else {
                    flash('admin_msg', 'Failed to delete category.', 'toast toast-danger');
                }
            } catch (Exception $e) {
                flash('admin_msg', 'Cannot delete category: It contains menu items.', 'toast toast-danger');
            }
        }
        redirect('admins/menu');
    }


    // POST /admins/addCategory
    public function addCategory(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = trim($_POST['category_name']);
            if(!empty($name)){
                $menuModel = $this->model('Menu');
                $menuModel->addCategory($name);
            }
        }
        redirect('admins/menu');
    }

    // GET /admins/orders
    public function orders(){
        $this->requireAdmin();
        $orderModel = $this->model('Order');
        $data = ['orders' => $orderModel->getAllOrders()];
        $this->view('admins/orders', $data);
    }

    // GET /admins/tables
    public function tables(){
        $this->requireAdmin();
        $tableModel = $this->model('TableModel');
        $data = ['tables' => $tableModel->getAllTables()];
        $this->view('admins/tables', $data);
    }

    // POST /admins/addTable
    public function addTable(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tableModel = $this->model('TableModel');
            $tableModel->addTable((int)$_POST['table_number'], (int)$_POST['capacity']);
        }
        redirect('admins/tables');
    }

    // POST /admins/deleteTable/{id}
    public function deleteTable($id = null){
        $this->requireAdmin();
        if($id){
            $tableModel = $this->model('TableModel');
            try {
                $tableModel->deleteTable((int)$id);
                flash('admin_msg', 'Table deleted.', 'toast toast-success');
            } catch(Exception $e) {
                flash('admin_msg', 'Cannot delete table: It was used in past orders.', 'toast toast-danger');
            }
        }
        redirect('admins/tables');
    }


    // GET /admins/logout
    public function logout(){
        unset($_SESSION['admin_id'], $_SESSION['admin_name']);
        session_destroy();
        redirect('admins/login');
    }
}
