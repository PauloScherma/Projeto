<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\models\Profile;
use common\models\Request;
use common\models\User;
use frontend\tests\FunctionalTester;
use Yii;

final class ProfileControllerCest
{
    #region Funções de apoio
    private function ensureProfileForCurrentUser(): Profile
    {
        $userId = Yii::$app->user->id;

        $existing = Profile::find()->where(['user_id' => $userId])->one();
        if ($existing) {
            return $existing;
        }

        $p = new Profile();
        $p->user_id = $userId;
        $p->first_name = "test";
        $p->last_name = "test";
        $p->phone = "919191919";
        $p->created_at = date('Y-m-d H:i:s');
        $p->updated_at = date('Y-m-d H:i:s');

        $p->save(false);
        return $p;
    }

    private function loginAsCliente(string $username = 'cliente_test'): void
    {
        $user = User::findOne(['username' => $username]);

        if ($user === null) {
            $user = new User();
            $user->username = $username;
            $user->email = $username . '@test.com';
            $user->status = User::STATUS_ACTIVE;
            $user->setPassword('password_0');
            $user->generateAuthKey();
            $user->save(false);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('cliente');
        if (!$role) {
            throw new \RuntimeException("Role 'cliente' não existe no RBAC.");
        }
        $auth->revokeAll($user->id);
        $auth->assign($role, $user->id);

        Yii::$app->user->login($user);
    }
    #endregion

    public function _before(FunctionalTester $I): void
    {

    }

    public function clienteCanOpenCreateIfNoProfileYet(FunctionalTester $I): void
    {
        $this->loginAsCliente();

        Profile::deleteAll(['user_id' => Yii::$app->user->id]);

        $I->amOnRoute('profile/index');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute('profile/create');
        $I->seeResponseCodeIs(200);
        $I->see('Create');
    }


    public function clienteCanCreateProfile(FunctionalTester $I): void
    {
        $this->loginAsCliente();

        Profile::deleteAll(['user_id' => Yii::$app->user->id]);

        $I->amOnRoute('profile/create');
        $I->seeResponseCodeIs(200);


        $I->fillField('#profile-first_name', 'test');
        $I->fillField('#profile-last_name', 'test');
        $I->fillField('#profile-phone', '910000000');

        $I->click('Save');

        $I->seeResponseCodeIs(200);
        $I->seeInCurrentUrl('profile/view');

        // valida na BD
        $created = Profile::find()->where(['user_id' => Yii::$app->user->id])->one();
        verify($created)->notNull();
        verify($created->first_name)->equals('test');
        verify($created->last_name)->equals('test');
        verify($created->phone)->equals('910000000');
    }

    public function clienteCanDeleteOwnProfile(FunctionalTester $I): void
    {
        $this->loginAsCliente();

        $profile = $this->ensureProfileForCurrentUser();

        $I->amOnRoute('profile/delete', ['id' => $profile->id]);
        $I->seeResponseCodeIs(200);
        $I->seeInCurrentUrl('profile/index');

        $after = Profile::findOne($profile->id);
        verify($after)->null();
    }




}
