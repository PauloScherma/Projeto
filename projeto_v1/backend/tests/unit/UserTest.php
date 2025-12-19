<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\User;
use const Grpc\STATUS_CANCELLED;

class UserTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testCreateUser()
    {
        $user = new User();
        $user->username = 'test';
        $user->email = 'test@test.com';
        $user->password_hash = ("$2y$13qewrqsbrC6hfx0Lc5zcCuV8YdOenG8e6PhCvuEt3hJ0LtVRb5TKV");
        $user->status = User::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->roleName = "cliente";
        $isSaved = $user->save();

        $this->assertTrue($isSaved,'O modelo User deve ser salvo com sucesso na BD. Erros: ' . print_r($user->errors, true));
    }

    public function testDeleteUser()
    {
        $user = new User();
        $userId = $user->id;
        $user->status = User::STATUS_ACTIVE;
        $user->deleteUser();

        $deletedUser = User::findOne($userId);
        dd($deletedUser);

        $deletedUserStatus = $deletedUser->status;

        $this->assertEquals(0, $deletedUserStatus, "Status não foi bem defenido");
    }
}
