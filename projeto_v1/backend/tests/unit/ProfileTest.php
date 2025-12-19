<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\User;
use common\models\Profile;
use Yii;

class ProfileTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testCreateProfile()
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

        $this->assertTrue($isSaved,'O modelo Profile deve ser salvo com sucesso na BD. Erros: ' . print_r($profile->errors, true));
    }

    public function testUpdateProfile()
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

        $profile->updated_at = date('Y-m-d H:i:s');
        $updated_at=$profile->updated_at;

        $this->assertTrue($isSaved,'O modelo Request deve ser salvo com sucesso na BD. Erros: ' . print_r($profile->errors, true));
        $this->assertNotNull($updated_at, 'O updated_at deve ter sido atualizado após o save().');
    }

    public function testDeleteProfile()
    {
        $admin = User::find()->where(['username' => 'admin'])->one();
        Yii::$app->user->setIdentity($admin);

        $profile = new Profile();
        $profile = Profile::find()->one();
        $profileId = $profile->id;

        $profile->delete();
        $deletedProfile = Profile::findOne($profileId);

        $this->assertNull($deletedProfile, 'O perfil ainda existe no banco de dados');
    }

    public function testReadProfile()
    {
        $admin = User::find()->where(['username' => 'admin'])->one();
        $adminId = $admin->id;

        $profile = new Profile();
        $profile = Profile::find()->one();
        $profile->user_id = $adminId;
        $profile->first_name = 'Test';
        $profile->phone = '911111111';
        $profile->created_at = date('Y-m-d H:i:s');
        $profile->save();

        $profileId = $profile->id;
        $model = Profile::findOne($profileId);

        $this->assertNotNull($model, 'O Request deveria ter sido encontrado no banco.');
        $this->assertEquals('Test', $model->first_name);
        $this->assertEquals($adminId, $model->user_id);
    }
}
