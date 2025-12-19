<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\User;
use common\models\Profile;

class ProfileTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }


    public function testCreateRequest()
    {
        $admin = User::find()->where(['username' => 'admin'])->one();
        $adminId = $admin->id;

        $profile = new Profile();
        $profile->user_id = $adminId;
        $profile->first_name = 'Test';
        $profile->last_name = 'Test';
        $profile->phone = '911111111';
        $profile->created_at = date('Y-m-d H:i:s');

        $isSaved = $profile->save();

        $this->assertTrue($isSaved,'O modelo Request deve ser salvo com sucesso na BD. Erros: ' . print_r($request->errors, true));
    }

}
