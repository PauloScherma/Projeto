<?php

declare(strict_types=1);

namespace backend\tests\Functional;

use backend\tests\FunctionalTester;
use common\models\Request;
use common\models\User;
use Yii;

final class RequestControllerCest
{
    #region Funções de apoio
    private function ensureRequestModel(?int $customerId = null): Request
    {
        if ($customerId === null) {
            $admin = User::find()->where(['username' => 'admin'])->one();
            if ($admin === null) {
                $admin = new User();
                $admin->username = 'admin';
                $admin->email = 'admin@test.com';
                $admin->status = User::STATUS_ACTIVE;
                $admin->setPassword('password_0');
                $admin->generateAuthKey();
                $admin->save(false);
            }
            $customerId = (int)$admin->id;
        }

        $request = new Request();
        $request->customer_id = $customerId;
        $request->title = 'Title';
        $request->description = 'Description';
        $request->setPriorityToMedium();
        $request->setStatusToNew();
        $request->created_at = date('Y-m-d H:i:s');

        if (!$request->save()) {
            throw new \RuntimeException(
                'Falhou criar Request de teste. Erros: ' . print_r($request->errors, true)
            );
        }

        return $request;
    }
    private function loginAsRole(string $roleName): User
    {
        $username = $roleName . '_test';
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
        $role = $auth->getRole($roleName);
        if ($role) {
            $auth->revokeAll($user->id);
            $auth->assign($role, $user->id);
        } else {
            throw new \RuntimeException("Role '{$roleName}' não existe no RBAC.");
        }

        Yii::$app->user->login($user);
        return $user;
    }
    #endregion

    public function _before(FunctionalTester $I): void
    {
        Yii::$app->user->logout();
    }

    public function gestorCanDeleteButNotCreate(FunctionalTester $I): void
    {
        $this->loginAsRole('gestor');
        $req = $this->ensureRequestModel(Yii::$app->user->id);

        $I->amOnRoute('request/create');
        $I->seeResponseCodeIs(403);

        $I->amOnRoute('request/delete', ['id' => $req->id]);
        $I->seeResponseCodeIs(405);

        $I->sendAjaxPostRequest('/request/delete?id=' . $req->id, []);
        $I->seeResponseCodeIs(302);

        $after = Request::findOne($req->id);

        verify($after)->notNull();
        verify($after->canceled_at)->notNull();
        verify($after->canceled_by)->notNull();
        verify($after->status)->equals(Request::STATUS_CANCELED);
        verify($after->canceled_by)->equals(Yii::$app->user->id);
    }

    public function adminCanCreateAndDelete(FunctionalTester $I): void
    {
        $this->loginAsRole('admin');

        $I->amOnRoute('request/create');
        $I->seeResponseCodeIs(200);

        $req = $this->ensureRequestModel(Yii::$app->user->id);

        $I->sendAjaxPostRequest('/request/delete?id=' . $req->id, []);
        $I->seeResponseCodeIs(302);

        $after = Request::findOne($req->id);

        verify($after)->notNull();
        verify($after->status)->equals(Request::STATUS_CANCELED);
        verify($after->canceled_at)->notNull();
        verify($after->canceled_by)->equals(Yii::$app->user->id);
    }

    public function tecnicoCannotCreateOrDelete(FunctionalTester $I): void
    {
        $this->loginAsRole('tecnico');

        $I->amOnRoute('request/create');
        $I->seeResponseCodeIs(403);

        $this->loginAsRole('admin');
        $req = $this->ensureRequestModel(Yii::$app->user->id);

        $this->loginAsRole('tecnico');

        $I->amOnRoute('request/delete', ['id' => $req->id]);
        $I->seeResponseCodeIs(403);

        $I->sendAjaxPostRequest('/request/delete?id=' . $req->id, []);
        $I->seeResponseCodeIs(403);

        $after = \common\models\Request::findOne($req->id);

        verify($after)->notNull();
        verify($after->status)->notEquals(\common\models\Request::STATUS_CANCELED);
        verify($after->canceled_at)->null();
        verify($after->canceled_by)->null();
    }

    public function tecnicoGestorAndAdminCanUpdate(FunctionalTester $I): void
    {
        $this->loginAsRole('admin');
        $req = $this->ensureRequestModel(Yii::$app->user->id);

        $this->loginAsRole('tecnico');
        $I->amOnRoute('request/update', ['id' => $req->id]);
        $I->seeResponseCodeIs(200);

        $this->loginAsRole('gestor');
        $I->amOnRoute('request/update', ['id' => $req->id]);
        $I->seeResponseCodeIs(200);

        $this->loginAsRole('admin');
        $I->amOnRoute('request/update', ['id' => $req->id]);
        $I->seeResponseCodeIs(200);
    }
}
