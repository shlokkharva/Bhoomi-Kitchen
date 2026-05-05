<?php
class Orders extends Controller {

    // GET /orders/menu — public, no login needed to browse
    public function menu(){
        $menuModel = $this->model('Menu');
        $data = [
            'categories' => $menuModel->getAllCategories(),
            'items'      => $menuModel->getAllItems(),
            'logged_in'  => isset($_SESSION['user_id']),
            'user_name'  => $_SESSION['user_name'] ?? ''
        ];
        $this->view('orders/menu', $data);
    }

    // POST /orders/place — AJAX endpoint
    public function place(){
        if(!isset($_SESSION['user_id'])){
            echo json_encode(['success'=>false,'msg'=>'Not logged in']);
            return;
        }
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            echo json_encode(['success'=>false]); return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $cartItems  = $input['cart']       ?? [];
        $groupSize  = (int)($input['group_size'] ?? 1);
        $tipAmount  = (float)($input['tip']  ?? 0);
        $payMethod  = $input['payment_method'] ?? 'cash';

        if(empty($cartItems)){
            echo json_encode(['success'=>false,'msg'=>'Cart is empty']); return;
        }

        $menuModel  = $this->model('Menu');
        $tableModel = $this->model('TableModel');
        $waiterModel= $this->model('Waiter');
        $orderModel = $this->model('Order');
        $payModel   = $this->model('Payment');

        // --- Auto Table Allocation ---
        $table = $tableModel->getAvailableTable($groupSize);
        if(!$table){
            echo json_encode(['success'=>false,'msg'=>'No available table for your group size. Please wait.']); return;
        }

        // --- Auto Waiter Assignment ---
        $waiter = $waiterModel->getAvailableWaiter();
        if(!$waiter){
            echo json_encode(['success'=>false,'msg'=>'All waiters are currently busy. Please wait a moment.']); return;
        }

        // --- Calculate totals ---
        $subtotal = 0;
        $items    = [];
        foreach($cartItems as $item){
            $menuItem = $menuModel->getItemById((int)$item['id']);
            if(!$menuItem) continue;
            $qty   = (int)$item['quantity'];
            $price = (float)$menuItem->price;
            $subtotal += $qty * $price;
            $items[] = ['item' => $menuItem, 'qty' => $qty, 'price' => $price];
        }

        $taxRate       = 0.05;  // 5% tax
        $serviceRate   = 0.10;  // 10% service charge
        $taxAmount     = round($subtotal * $taxRate, 2);
        $serviceCharge = round($subtotal * $serviceRate, 2);
        $totalAmount   = round($subtotal + $taxAmount + $serviceCharge + $tipAmount, 2);

        // --- Place Order ---
        $orderData = [
            'user_id'        => $_SESSION['user_id'],
            'waiter_id'      => $waiter->id,
            'table_id'       => $table->id,
            'total_amount'   => $totalAmount,
            'tax_amount'     => $taxAmount,
            'service_charge' => $serviceCharge,
            'tip_amount'     => $tipAmount
        ];
        $orderId = $orderModel->placeOrder($orderData);
        if(!$orderId){
            echo json_encode(['success'=>false,'msg'=>'Failed to place order']); return;
        }

        // Save order items
        foreach($items as $it){
            $orderModel->addOrderItem($orderId, $it['item']->id, $it['qty'], $it['price']);
        }

        // Occupy table
        $tableModel->occupyTable($table->id);

        // Record payment
        $payModel->recordPayment($orderId, $payMethod, $totalAmount);

        echo json_encode([
            'success'  => true,
            'order_id' => $orderId,
            'table'    => $table->table_number,
            'waiter'   => $waiter->name,
            'total'    => $totalAmount
        ]);
    }

    // GET /orders/track — show tracking page
    public function track(){
        if(!isset($_SESSION['user_id'])) redirect('users/login');
        $orderModel = $this->model('Order');
        $data = ['active_order' => $orderModel->getActiveOrder($_SESSION['user_id'])];
        $this->view('orders/track', $data);
    }

    // GET /orders/status/{order_id} — AJAX polling
    public function status($order_id = null){
        if(!$order_id){ echo json_encode(['error'=>'no id']); return; }
        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderById((int)$order_id);
        if($order){
            echo json_encode(['status'=>$order->status, 'waiter'=>$order->waiter_name]);
        } else {
            echo json_encode(['error'=>'not found']);
        }
    }

    // POST /orders/addTip — AJAX
    public function addTip(){
        if(!isset($_SESSION['user_id'])){ echo json_encode(['success'=>false]); return; }
        $input = json_decode(file_get_contents('php://input'), true);
        $order_id = (int)($input['order_id'] ?? 0);
        $tip      = (float)($input['tip'] ?? 0);
        $orderModel = $this->model('Order');
        $orderModel->updateTip($order_id, $tip);
        echo json_encode(['success'=>true]);
    }
}
