<?php

use Codeception\Test\Unit;
use app\models\User;
use app\models\Category;
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;
use app\services\CartService;

class CartServiceTest extends Unit
{
    private CartService $cartService;

    private $user;
    private $category;
    private $product;

    protected function _before()
    {
        $this->cartService = new CartService();

        $this->clearTestData();

        $this->user = $this->createUser(
            'TEST_cart_user',
            'test_cart_user@example.com',
            '123456'
        );

        $this->category = $this->createCategory('TEST_Собаки');

        $this->product = $this->createProduct(
            'TEST_Сухой корм для собак',
            'Полнорационный корм для взрослых собак',
            1200,
            $this->category->id
        );
    }

    protected function _after()
    {
        $this->clearTestData();
    }

    public function testCannotViewCartIfCartDoesNotExist()
    {
        $response = $this->cartService->viewCart($this->user->id);

        $this->assertFalse($response['success']);
        $this->assertSame(404, $response['status']);
    }

    public function testCanAddCartItem()
    {
        $response = $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $this->assertTrue($response['success']);

        $cart = Cart::findOne(['user_id' => $this->user->id]);

        $this->assertNotNull($cart);

        $cartItem = CartItem::findOne([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ]);

        $this->assertNotNull($cartItem);
        $this->assertSame((int)$this->product->id, (int)$cartItem->product_id);
    }

    public function testCanViewCartAfterAddingProduct()
    {
        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $response = $this->cartService->viewCart($this->user->id);

        $this->assertTrue($response['success']);
        $this->assertInstanceOf(Cart::class, $response['data']);
        $this->assertSame((int)$this->user->id, (int)$response['data']->user_id);
    }

    public function testCanAddSameProductTwiceAndIncreaseQuantity()
    {
        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $cart = Cart::findOne(['user_id' => $this->user->id]);

        $cartItem = CartItem::findOne([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ]);

        $this->assertNotNull($cartItem);
        $this->assertSame(2, (int)$cartItem->quantity);
    }

    public function testCanUpdateCartItemQuantity()
    {
        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $cart = Cart::findOne(['user_id' => $this->user->id]);

        $cartItem = CartItem::findOne([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->cartService->updateItemCart(
            $cartItem->id,
            5,
            $this->user->id
        );

        $this->assertTrue($response['success']);

        $updatedCartItem = CartItem::findOne($cartItem->id);

        $this->assertNotNull($updatedCartItem);
        $this->assertSame(5, (int)$updatedCartItem->quantity);
    }

    public function testCannotUpdateCartItemWithInvalidQuantity()
    {
        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $cart = Cart::findOne(['user_id' => $this->user->id]);

        $cartItem = CartItem::findOne([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->cartService->updateItemCart(
            $cartItem->id,
            0,
            $this->user->id
        );

        $this->assertFalse($response['success']);
        $this->assertSame(422, $response['status']);
    }

    public function testCanRemoveCartItem()
    {
        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $cart = Cart::findOne(['user_id' => $this->user->id]);

        $cartItem = CartItem::findOne([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ]);

        $response = $this->cartService->removeCartItem(
            $cartItem->id,
            $this->user->id
        );

        $this->assertTrue($response['success']);

        $deletedCartItem = CartItem::findOne($cartItem->id);

        $this->assertNull($deletedCartItem);
    }

    public function testCanRemoveAllCartItems()
    {
        $secondProduct = $this->createProduct(
            'TEST_Миска для собак',
            'Металлическая миска для воды',
            450,
            $this->category->id
        );

        $this->cartService->addCartItem(
            $this->product->id,
            $this->user->id
        );

        $this->cartService->addCartItem(
            $secondProduct->id,
            $this->user->id
        );

        $cart = Cart::findOne(['user_id' => $this->user->id]);

        $itemsBeforeClear = CartItem::findAll(['cart_id' => $cart->id]);

        $this->assertNotEmpty($itemsBeforeClear);

        $response = $this->cartService->removeAllCartItem($this->user->id);

        $this->assertTrue($response['success']);

        $itemsAfterClear = CartItem::findAll(['cart_id' => $cart->id]);

        $this->assertEmpty($itemsAfterClear);
    }

    public function testCannotAddUnknownProductToCart()
    {
        $response = $this->cartService->addCartItem(
            999999,
            $this->user->id
        );

        $this->assertFalse($response['success']);
        $this->assertSame(404, $response['status']);
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
