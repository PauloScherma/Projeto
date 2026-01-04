<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;
use Yii;

class RequestControllerCest
{
    public function _before(FunctionalTester $I): void
    {
        $user = User::findOne(['username' => 'cliente_teste']);

        if ($user === null) {
            $user = new User();
            $user->username = 'cliente_teste';
            $user->email = 'cliente@test.com';
            $user->setPassword('123456');
            $user->status = User::STATUS_ACTIVE;
            $user->generateAuthKey();
            $user->save(false);

            $auth = Yii::$app->authManager;
            $role = $auth->getRole('cliente');

            if ($role) {
                $auth->assign($role, $user->id);
            }
        }

        $I->amLoggedInAs($user);
    }

    public function testIndex(FunctionalTester $I): void
    {
        $I->amOnRoute('request/index');
        $I->seeResponseCodeIs(200);
        $I->see('Requests');
    }
}