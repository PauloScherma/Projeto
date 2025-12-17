<?php

namespace backend\tests\Functional;

use backend\tests\FunctionalTester;
use common\models\Request;
use common\models\User;

class RequestControllerTest extends \Codeception\Test\Unit
{
    protected FunctionalTester $tester;

    protected function _before(FunctionalTester $I)
    {
        $adminId = User::find()
            ->where(['username' => 'admin'])
            ->one()
            ->id;
        //(id) de quem está loggedIn
        $I->amLoggedInAs($adminId);
    }

    // tests
    public function testIndex(FunctionalTester $I)
    {
        $I->amOnRoute('/request/index');
        $I->seeResponseCodeIs(200);
        $I->see('Requests');
    }
}
