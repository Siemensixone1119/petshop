<?php

use Codeception\Test\Unit;
use app\services\ProductService;
use app\models\Product;
use app\models\Category;

class ProductServiceTest extends Unit
{
    private $dogCategory;
    private $catCategory;

    protected function _before()
    {
        $this->clearTestData();

        $this->dogCategory = $this->createCategory('TEST_Собаки');
        $this->catCategory = $this->createCategory('TEST_Кошки');

        $this->createProduct(
            'TEST_A Сухой корм для собак',
            'Полнорационный корм для взрослых собак',
            1200,
            $this->dogCategory->id
        );

        $this->createProduct(
            'TEST_B Миска для собак',
            'Металлическая миска для воды и корма',
            450,
            $this->dogCategory->id
        );

        $this->createProduct(
            'TEST_C Игрушка для собак',
            'Резиновый мяч для активных игр',
            320,
            $this->dogCategory->id
        );

        $this->createProduct(
            'TEST_D Влажный корм для кошек',
            'Влажный корм для взрослых кошек',
            85,
            $this->catCategory->id
        );
    }

    protected function _after()
    {
        $this->clearTestData();
    }

    public function testCreateProduct()
    {
        $categoryId = $this->dogCategory->id;

        $data = [
            'name' => 'TEST_NEW Лакомство для собак',
            'description' => 'Тестовое лакомство для собак',
            'price' => 777,
            'stock' => 15,
            'image' => 'test-new-product.png',
        ];

        $productService = new ProductService();

        $newProduct = $productService->createProduct($categoryId, $data);

        $this->assertTrue($newProduct['success']);
        $this->assertSame('TEST_NEW Лакомство для собак', $newProduct['data']->name);
        $this->assertSame('Тестовое лакомство для собак', $newProduct['data']->description);
        $this->assertEquals(777, $newProduct['data']->price);
        $this->assertSame(15, (int)$newProduct['data']->stock);
        $this->assertSame((int)$categoryId, (int)$newProduct['data']->category_id);
    }

    public function testCanGetProductsByCategory()
    {
        $categoryId = $this->dogCategory->id;

        $productService = new ProductService();

        $productList = $productService->getProductList($categoryId, '', '');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        foreach ($productList['data'] as $product) {
            $this->assertSame((int)$categoryId, (int)$product->category_id);
        }
    }

    public function testCanSearchProductByName()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, 'Сухой', '');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $names = $this->getNames($productList['data']);

        $this->assertContains('TEST_A Сухой корм для собак', $names);
    }

    public function testCanSearchProductByDescription()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, 'металлическая', '');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $names = $this->getNames($productList['data']);

        $this->assertContains('TEST_B Миска для собак', $names);
    }

    public function testSearchIsCaseInsensitive()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, 'СУХОЙ', '');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $names = $this->getNames($productList['data']);

        $this->assertContains('TEST_A Сухой корм для собак', $names);
    }

    public function testSearchWithoutResultsReturnsEmptyArray()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, 'несуществующийтовар123', '');

        $this->assertTrue($productList['success']);
        $this->assertEmpty($productList['data']);
    }

    public function testCanSortByPriceAsc()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, '', 'price_asc');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $prices = $this->getPrices($productList['data']);

        $this->assertSame([320.0, 450.0, 1200.0], $prices);
    }

    public function testCanSortByPriceDesc()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, '', 'price_desc');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $prices = $this->getPrices($productList['data']);

        $this->assertSame([1200.0, 450.0, 320.0], $prices);
    }

    public function testCanSortByNameAsc()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, '', 'name_asc');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $names = $this->getNames($productList['data']);

        $this->assertSame([
            'TEST_A Сухой корм для собак',
            'TEST_B Миска для собак',
            'TEST_C Игрушка для собак',
        ], $names);
    }

    public function testCanSortByNameDesc()
    {
        $productService = new ProductService();

        $productList = $productService->getProductList($this->dogCategory->id, '', 'name_desc');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $names = $this->getNames($productList['data']);

        $this->assertSame([
            'TEST_C Игрушка для собак',
            'TEST_B Миска для собак',
            'TEST_A Сухой корм для собак',
        ], $names);
    }

    public function testCanSearchAndSort()
    {
        $productService = new ProductService();

        $search = 'корм';

        $productList = $productService->getProductList($this->dogCategory->id, $search, 'price_asc');

        $this->assertTrue($productList['success']);
        $this->assertNotEmpty($productList['data']);

        $previousPrice = null;

        foreach ($productList['data'] as $product) {
            $currentPrice = (float)$product->price;

            if ($previousPrice !== null) {
                $this->assertGreaterThanOrEqual($previousPrice, $currentPrice);
            }

            $name = (string)$product->name;
            $description = (string)$product->description;

            $hasSearchText =
                mb_stripos($name, $search) !== false ||
                mb_stripos($description, $search) !== false;

            $this->assertTrue(
                $hasSearchText,
                'Товар не содержит слово "' . $search . '" в названии или описании: ' . $name
            );

            $previousPrice = $currentPrice;
        }
    }

    private function createCategory(string $name)
    {
        $category = new Category();

        $this->setAttributeIfExists($category, 'name', $name);
        $this->setAttributeIfExists($category, 'description', 'Тестовая категория');
        $this->setAttributeIfExists($category, 'image', 'test-category.png');
        $this->setAttributeIfExists($category, 'image_url', 'test-category.png');
        $this->setAttributeIfExists($category, 'slug', strtolower(str_replace(' ', '-', $name)));
        $this->setAttributeIfExists($category, 'parent_id', null);
        $this->setAttributeIfExists($category, 'created_at', time());
        $this->setAttributeIfExists($category, 'updated_at', time());

        $category->save(false);

        return $category;
    }

    private function createProduct(string $name, string $description, float $price, int $categoryId)
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

    private function getNames(array $products): array
    {
        return array_map(function ($product) {
            return $product->name;
        }, $products);
    }

    private function getPrices(array $products): array
    {
        return array_map(function ($product) {
            return (float)$product->price;
        }, $products);
    }

    private function setAttributeIfExists($model, string $attribute, $value): void
    {
        if ($model->hasAttribute($attribute)) {
            $model->$attribute = $value;
        }
    }

    private function clearTestData(): void
    {
        Product::deleteAll(['like', 'name', 'TEST_']);
        Category::deleteAll(['like', 'name', 'TEST_']);
    }
}
