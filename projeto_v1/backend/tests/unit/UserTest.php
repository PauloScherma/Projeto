<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\User;

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
        $isSaved = $user->save();

        $this->assertTrue($isSaved,'O modelo User deve ser salvo com sucesso na BD. Erros: ' . print_r($user->errors, true));
    }

    public function testUpdateUser()
    {
        
    }
}
