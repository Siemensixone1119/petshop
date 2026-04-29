<?php

use Codeception\Test\Unit;
use app\models\User;
use app\services\AuthService;

class AuthServiceTest extends Unit
{
    private AuthService $authService;

    protected function _before()
    {
        $this->authService = new AuthService();

        $this->clearTestData();
    }

    protected function _after()
    {
        $this->clearTestData();
    }

    public function testCanRegisterUser()
    {
        $response = $this->authService->register(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $this->assertTrue($response['success']);

        $user = User::findOne(['login' => 'TEST_user']);

        $this->assertNotNull($user);
        $this->assertSame('TEST_user', $user->login);
        $this->assertSame('test_user@example.com', $user->email);
        $this->assertSame('user', $user->role);

        $this->assertNotSame('123456', $user->password_hash);

        $this->assertTrue(
            Yii::$app->security->validatePassword('123456', $user->password_hash)
        );
    }

    public function testCannotRegisterWithExistingLogin()
    {
        $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $response = $this->authService->register(
            'TEST_user',
            'another_email@example.com',
            '123456'
        );

        $this->assertFalse($response['success']);
    }

    public function testCannotRegisterWithExistingEmail()
    {
        $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $response = $this->authService->register(
            'TEST_another_user',
            'test_user@example.com',
            '123456'
        );

        $this->assertFalse($response['success']);
    }

    public function testCanLoginWithCorrectCredentials()
    {
        $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $response = $this->authService->login(
            'test_user@example.com',
            '123456'
        );

        $this->assertTrue($response['success']);

        $user = User::findOne(['login' => 'TEST_user']);

        $this->assertNotNull($user);
        $this->assertNotEmpty($user->auth_token);
    }

    public function testCannotLoginWithWrongPassword()
    {
        $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $response = $this->authService->login(
            'test_user@example.com',
            'wrong_password'
        );

        $this->assertFalse($response['success']);
    }

    public function testCannotLoginWithUnknownUser()
    {
        $response = $this->authService->login(
            'unknown_user@example.com',
            '123456'
        );

        $this->assertFalse($response['success']);
    }

    public function testCanValidateToken()
    {
        $user = $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $token = 'TEST_TOKEN_123456';

        $user->auth_token = $token;
        $user->save(false);

        $result = $this->authService->validateToken($token);

        $this->assertTrue($result['success']);
        $this->assertSame(200, $result['status']);

        $validatedUser = $result['data'];

        $this->assertIsArray($validatedUser);
        $this->assertSame((int)$user->id, (int)$validatedUser['id']);
        $this->assertSame('TEST_user', $validatedUser['login']);
        $this->assertSame('test_user@example.com', $validatedUser['email']);
        $this->assertSame('user', $validatedUser['role']);
    }

    public function testCannotValidateWrongToken()
    {
        $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $result = $this->authService->validateToken('WRONG_TOKEN');

        $this->assertFalse($result['success']);
        $this->assertSame(401, $result['status']);
        $this->assertSame('INVALID_TOKEN', $result['code']);
    }

    public function testCanLogoutUser()
    {
        $user = $this->createUser(
            'TEST_user',
            'test_user@example.com',
            '123456'
        );

        $user->auth_token = 'TEST_TOKEN_123456';
        $user->save(false);

        $response = $this->authService->logout($user);

        $this->assertTrue($response['success']);

        $updatedUser = User::findOne($user->id);

        $this->assertTrue(
            $updatedUser->auth_token === null || $updatedUser->auth_token === ''
        );
    }

    private function createUser($login, $email, $password): User
    {
        $user = new User();

        $this->setAttributeIfExists($user, 'login', $login);
        $this->setAttributeIfExists($user, 'email', $email);

        $this->setAttributeIfExists(
            $user,
            'password_hash',
            Yii::$app->security->generatePasswordHash($password)
        );

        $this->setAttributeIfExists($user, 'role', 'user');
        $this->setAttributeIfExists($user, 'auth_token', null);
        $this->setAttributeIfExists($user, 'created_at', time());
        $this->setAttributeIfExists($user, 'updated_at', time());

        $user->save(false);

        return $user;
    }

    private function setAttributeIfExists($model, string $attribute, $value): void
    {
        if ($model->hasAttribute($attribute)) {
            $model->$attribute = $value;
        }
    }

    private function clearTestData(): void
    {
        User::deleteAll(['like', 'login', 'TEST_']);
        User::deleteAll(['like', 'email', 'test_']);
    }
}
