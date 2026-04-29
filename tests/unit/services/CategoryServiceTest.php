<?php

use Codeception\Test\Unit;
use app\models\Category;
use app\services\CategoryService;

class CategorieServiceTest extends Unit
{
    private CategoryService $categorieService;

    protected function _before()
    {
        $this->categorieService = new CategoryService();

        $this->clearTestData();
    }

    protected function _after()
    {
        $this->clearTestData();
    }

    public function testCanCreateMainCategory()
    {
        $data = [
            'name' => 'TEST_Собаки',
            'description' => 'Тестовая категория для собак',
            'image' => 'test-dogs.png',
            'slug' => 'test-dogs',
            'parent_id' => null,
        ];

        $response = $this->categorieService->createCategory($data);

        $this->assertTrue($response['success']);

        $category = Category::findOne(['name' => 'TEST_Собаки']);

        $this->assertNotNull($category);
        $this->assertSame('TEST_Собаки', $category->name);
        $this->assertSame('Тестовая категория для собак', $category->description);
        $this->assertNull($category->parent_id);
    }

    public function testCanCreateSubcategory()
    {
        $parentCategory = $this->createCategory(
            'TEST_Собаки',
            'Товары для собак',
            'dogs.png',
            'test-dogs'
        );

        $data = [
            'name' => 'TEST_Корма',
            'description' => 'Корма для собак',
            'image' => 'test-dog-food.png',
            'slug' => 'test-dog-food',
            'parent_id' => $parentCategory->id,
        ];

        $response = $this->categorieService->createCategory($data);

        $this->assertTrue($response['success']);

        $subcategory = Category::findOne(['name' => 'TEST_Корма']);

        $this->assertNotNull($subcategory);
        $this->assertSame('TEST_Корма', $subcategory->name);
        $this->assertSame((int)$parentCategory->id, (int)$subcategory->parent_id);
    }

    public function testCanGetCategoryList()
    {
        $this->createCategory(
            'TEST_Собаки',
            'Товары для собак',
            'dogs.png',
            'test-dogs'
        );

        $this->createCategory(
            'TEST_Кошки',
            'Товары для кошек',
            'cats.png',
            'test-cats'
        );

        $response = $this->categorieService->getCategoryList();

        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['data']);
    }

    public function testCanGetCategoryById()
    {
        $category = $this->createCategory(
            'TEST_Собаки',
            'Товары для собак',
            'dogs.png',
            'test-dogs'
        );

        $response = $this->categorieService->getCategoryById($category->id);

        $this->assertTrue($response['success']);

        $foundCategory = $response['data'];

        $this->assertSame((int)$category->id, (int)$foundCategory->id);
        $this->assertSame('TEST_Собаки', $foundCategory->name);
    }

    public function testCanUpdateCategory()
    {
        $category = $this->createCategory(
            'TEST_Собаки',
            'Товары для собак',
            'dogs.png',
            'test-dogs'
        );

        $data = [
            'name' => 'TEST_Собаки обновлено',
            'description' => 'Обновлённое описание категории',
            'image' => 'updated-dogs.png',
            'slug' => 'test-dogs-updated',
            'parent_id' => null,
        ];

        $response = $this->categorieService->updateCategory($category->id, $data);

        $this->assertTrue($response['success']);

        $updatedCategory = Category::findOne($category->id);

        $this->assertNotNull($updatedCategory);
        $this->assertSame('TEST_Собаки обновлено', $updatedCategory->name);
        $this->assertSame('Обновлённое описание категории', $updatedCategory->description);
        $this->assertSame('test-dogs-updated', $updatedCategory->slug);
    }

    public function testCanDeleteCategory()
    {
        $category = $this->createCategory(
            'TEST_Собаки',
            'Товары для собак',
            'dogs.png',
            'test-dogs'
        );

        $response = $this->categorieService->deleteCategory($category->id);

        $this->assertTrue($response['success']);

        $deletedCategory = Category::findOne($category->id);

        $this->assertNull($deletedCategory);
    }

    public function testCannotGetUnknownCategory()
    {
        $response = $this->categorieService->getCategoryById(999999);

        $this->assertFalse($response['success']);
    }

    public function testCannotCreateCategoryWithoutName()
    {
        $data = [
            'name' => '',
            'description' => 'Категория без названия',
            'image' => 'empty-name.png',
            'slug' => 'empty-name',
            'parent_id' => null,
        ];

        $response = $this->categorieService->createCategory($data);

        $this->assertFalse($response['success']);
    }

    private function createCategory(
        string $name,
        string $description,
        string $image,
        string $slug,
        ?int $parentId = null
    ): Category {
        $category = new Category();

        $this->setAttributeIfExists($category, 'name', $name);
        $this->setAttributeIfExists($category, 'description', $description);
        $this->setAttributeIfExists($category, 'image', $image);
        $this->setAttributeIfExists($category, 'image_url', $image);
        $this->setAttributeIfExists($category, 'slug', $slug);
        $this->setAttributeIfExists($category, 'parent_id', $parentId);
        $this->setAttributeIfExists($category, 'created_at', time());
        $this->setAttributeIfExists($category, 'updated_at', time());

        $category->save(false);

        return $category;
    }

    private function setAttributeIfExists($model, string $attribute, $value): void
    {
        if ($model->hasAttribute($attribute)) {
            $model->$attribute = $value;
        }
    }

    private function clearTestData(): void
    {
        Category::deleteAll(['like', 'name', 'TEST_']);
    }
}
