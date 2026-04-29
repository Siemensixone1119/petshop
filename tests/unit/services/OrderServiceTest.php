<?php

use Codeception\Test\Unit;
use app\models\User;
use app\models\Category;
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;
use app\models\Order;
use app\models\OrderItem;
use app\services\OrderService;

class OrderServiceTest extends Unit
{
    private OrderService $orderService;

    private $user;
    private $category;
    private $product;
    private $secondProduct;

    protected function _before()
    {
        $this->orderService = new OrderService();

        $this->clearTestData();

        $this->user = $this->createUser(
            'TEST_order_user',
            'test_order_user@example.com',
            '123456'
        );

        $this->category = $this->createCategory('TEST_Собаки');

        $this->product = $this->createProduct(
            'TEST_Сухой корм для собак',
            'Полнорационный корм для взрослых собак',
            1200,
            $this->category->id
        );

        $this->secondProduct = $this->createProduct(
            'TEST_Миска для собак',
            'Металлическая миска для воды',
            450,
            $this->category->id
        );
    }

    protected function _after()
    {
        $this->clearTestData();
    }

    public function testCanGetUserOrders()
    {
        $this->createOrder($this->user->id, 1200);
        $this->createOrder($this->user->id, 450);

        $response = $this->orderService->getUserOrders($this->user->id);

        $this->assertTrue($response['success']);
        $this->assertCount(2, $response['data']);
    }

    public function testCannotGetUserOrdersWithoutUserId()
    {
        $response = $this->orderService->getUserOrders(null);

        $this->assertFalse($response['success']);
    }

    public function testCanGetOrderById()
    {
        $order = $this->createOrder($this->user->id, 1200);

        $response = $this->orderService->getOrderById($order->id);

        $this->assertTrue($response['success']);
        $this->assertInstanceOf(Order::class, $response['data']);
        $this->assertSame((int)$order->id, (int)$response['data']->id);
    }

    public function testCannotGetUnknownOrder()
    {
        $response = $this->orderService->getOrderById(999999);

        $this->assertFalse($response['success']);
        $this->assertSame(404, $response['status']);
    }

    public function testCannotCreateOrderWithoutCart()
    {
        $response = $this->orderService->createOrder($this->user->id);

        $this->assertFalse($response['success']);
        $this->assertSame(404, $response['status']);
    }

    public function testCannotCreateOrderWithEmptyCart()
    {
        $this->createCart($this->user->id);

        $response = $this->orderService->createOrder($this->user->id);

        $this->assertFalse($response['success']);
        $this->assertSame(404, $response['status']);
    }

    public function testCanCreateOrderFromCart()
    {
        $cart = $this->createCart($this->user->id);

        $this->createCartItem($cart->id, $this->product->id, 2);

        $response = $this->orderService->createOrder($this->user->id);

        $this->assertTrue($response['success']);
        $this->assertInstanceOf(Order::class, $response['data']);
        $this->assertSame((int)$this->user->id, (int)$response['data']->user_id);
    }

    public function testCreateOrderCalculatesTotalAmount()
    {
        $cart = $this->createCart($this->user->id);

        $this->createCartItem($cart->id, $this->product->id, 2);       // 1200 * 2 = 2400
        $this->createCartItem($cart->id, $this->secondProduct->id, 3); // 450 * 3 = 1350

        $response = $this->orderService->createOrder($this->user->id);

        $this->assertTrue($response['success']);

        $order = $response['data'];

        $this->assertEquals(3750, (float)$order->total_amount);
    }

    public function testCreateOrderCreatesOrderItems()
    {
        $cart = $this->createCart($this->user->id);

        $this->createCartItem($cart->id, $this->product->id, 2);
        $this->createCartItem($cart->id, $this->secondProduct->id, 1);

        $response = $this->orderService->createOrder($this->user->id);

        $this->assertTrue($response['success']);

        $order = $response['data'];

        $orderItems = OrderItem::findAll(['order_id' => $order->id]);

        $this->assertCount(2, $orderItems);
    }

    public function testCreateOrderClearsCartItems()
    {
        $cart = $this->createCart($this->user->id);

        $this->createCartItem($cart->id, $this->product->id, 2);

        $itemsBeforeOrder = CartItem::findAll(['cart_id' => $cart->id]);

        $this->assertNotEmpty($itemsBeforeOrder);

        $response = $this->orderService->createOrder($this->user->id);

        $this->assertTrue($response['success']);

        $itemsAfterOrder = CartItem::findAll(['cart_id' => $cart->id]);

        $this->assertEmpty($itemsAfterOrder);
    }

    public function testCanRemoveOrder()
    {
        $order = $this->createOrder($this->user->id, 1200);

        $response = $this->orderService->removeOrder(
            $this->user->id,
            $order->id
        );

        $this->assertTrue($response['success']);

        $deletedOrder = Order::findOne($order->id);

        $this->assertNull($deletedOrder);
    }

    public function testCanUpdateOrderStatus()
    {
        $order = $this->createOrder($this->user->id, 1200);

        $response = $this->orderService->updateOrderStatus(
            $this->user->id,
            'completed',
            $order->id
        );

        $this->assertTrue($response['success']);

        $updatedOrder = Order::findOne($order->id);

        $this->assertSame('completed', $updatedOrder->status);
    }

    public function testCannotUpdateOrderStatusWithoutStatus()
    {
        $order = $this->createOrder($this->user->id, 1200);

        $response = $this->orderService->updateOrderStatus(
            $this->user->id,
            null,
            $order->id
        );

        $this->assertFalse($response['success']);
    }

    private function createUser($login, $email, $password): User
    {
        $user = new User();

        $this->setAttributeIfExists($user, 'login', $login);
        $this->setAttributeIfExists($user, 'email', $email);

        $this->setAttributeIfExists(
            $user,
            'password_hash',
            \Yii::$app->security->generatePasswordHash($password)
        );

        $this->setAttributeIfExists($user, 'role', 'user');
        $this->setAttributeIfExists($user, 'auth_token', null);
        $this->setAttributeIfExists($user, 'created_at', time());
        $this->setAttributeIfExists($user, 'updated_at', time());

        $user->save(false);

        return $user;
    }

    private function createCategory(string $name): Category
    {
        $category = new Category();

        $this->setAttributeIfExists($category, 'name', $name);
        $this->setAttributeIfExists($category, 'description', 'Тестовая категория');
        $this->setAttributeIfExists($category, 'image', 'test-category.png');
        $this->setAttributeIfExists($category, 'image_url', 'test-category.png');
        $this->setAttributeIfExists($category, 'slug', 'test-category-' . uniqid());
        $this->setAttributeIfExists($category, 'parent_id', null);
        $this->setAttributeIfExists($category, 'created_at', time());
        $this->setAttributeIfExists($category, 'updated_at', time());

        $category->save(false);

        return $category;
    }

    private function createProduct(string $name, string $description, float $price, int $categoryId): Product
    {
        $product = new Product();

        $this->setAttributeIfExists($product, 'name', $name);
        $this->setAttributeIfExists($product, 'description', $description);
        $this->setAttributeIfExists($product, 'price', $price);
        $this->setAttributeIfExists($product, 'stock', 10);
        $this->setAttributeIfExists($product, 'image', 'test-product.png');
        $this->setAttributeIfExists($product, 'image_url', 'test-product.png');
        $this->setAttributeIfExists($product, 'category_id', $categoryId);
        $this->setAttributeIfExists($product, 'created_at', time());
        $this->setAttributeIfExists($product, 'updated_at', time());

        $product->save(false);

        return $product;
    }

    private function createCart(int $userId): Cart
    {
        $cart = new Cart();

        $this->setAttributeIfExists($cart, 'user_id', $userId);
        $this->setAttributeIfExists($cart, 'created_at', time());
        $this->setAttributeIfExists($cart, 'updated_at', time());

        $cart->save(false);

        return $cart;
    }

    private function createCartItem(int $cartId, int $productId, int $quantity): CartItem
    {
        $cartItem = new CartItem();

        $this->setAttributeIfExists($cartItem, 'cart_id', $cartId);
        $this->setAttributeIfExists($cartItem, 'product_id', $productId);
        $this->setAttributeIfExists($cartItem, 'quantity', $quantity);
        $this->setAttributeIfExists($cartItem, 'created_at', time());
        $this->setAttributeIfExists($cartItem, 'updated_at', time());

        $cartItem->save(false);

        return $cartItem;
    }

    private function createOrder(int $userId, float $totalAmount, string $status = 'pending'): Order
    {
        $order = new Order();

        $this->setAttributeIfExists($order, 'user_id', $userId);
        $this->setAttributeIfExists($order, 'status', $status);
        $this->setAttributeIfExists($order, 'total_amount', $totalAmount);
        $this->setAttributeIfExists($order, 'created_at', time());
        $this->setAttributeIfExists($order, 'updated_at', time());

        $order->save(false);

        return $order;
    }

    private function setAttributeIfExists($model, string $attribute, $value): void
    {
        if ($model->hasAttribute($attribute)) {
            $model->$attribute = $value;
        }
    }

    private function clearTestData(): void
    {
        $testUserIds = User::find()
            ->select('id')
            ->where(['like', 'login', 'TEST_'])
            ->column();

        if (!empty($testUserIds)) {
            $testOrderIds = Order::find()
                ->select('id')
                ->where(['user_id' => $testUserIds])
                ->column();

            if (!empty($testOrderIds)) {
                OrderItem::deleteAll(['order_id' => $testOrderIds]);
            }

            Order::deleteAll(['user_id' => $testUserIds]);

            $testCartIds = Cart::find()
                ->select('id')
                ->where(['user_id' => $testUserIds])
                ->column();

            if (!empty($testCartIds)) {
                CartItem::deleteAll(['cart_id' => $testCartIds]);
            }

            Cart::deleteAll(['user_id' => $testUserIds]);
        }

        Product::deleteAll(['like', 'name', 'TEST_']);
        Category::deleteAll(['like', 'name', 'TEST_']);
        User::deleteAll(['like', 'login', 'TEST_']);
    }
}