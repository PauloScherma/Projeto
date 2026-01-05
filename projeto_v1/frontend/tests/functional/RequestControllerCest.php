<?php

declare(strict_types=1);

namespace frontend\tests\Functional;

use common\models\Request;
use common\models\User;
use frontend\tests\FunctionalTester;
use Yii;

final class RequestControllerCest
{
    #region Funções de apoio
    private function ensureRequestForUser(int $customerId): Request
    {
        $req = new Request();
        $req->customer_id = $customerId;
        $req->title = 'Title';
        $req->description = 'Description';
        $req->setPriorityToMedium();
        $req->setStatusToNew();
        $req->created_at = date('Y-m-d H:i:s');
        $req->canceled_at = null;
        $req->canceled_by = null;

        if (!$req->save()) {
            throw new \RuntimeException('Falhou criar Request. Erros: ' . print_r($req->errors, true));
        }

        return $req;
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
        Yii::$app->user->logout();
    }

    public function guestCannotAccessIndex(FunctionalTester $I): void
    {
        $I->amOnRoute('request/index');
        $I->dontSee('Create Request');
    }

    public function clienteCanAccessPages(FunctionalTester $I): void
    {
        $this->loginAsCliente();

        $I->amOnRoute('request/index');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute('request/create');
        $I->seeResponseCodeIs(200);

        $I->amOnRoute('request/history');
        $I->seeResponseCodeIs(200);
    }

    public function clienteCanCreateRequest(FunctionalTester $I): void
    {
        $this->loginAsCliente('cliente_create');

        $I->amOnRoute('request/create');
        $I->seeResponseCodeIs(200);

        $I->fillField('#request-title', 'Pedido teste Codeception');
        $I->fillField('#request-description', 'Descrição teste');

        $I->click('Save');

        $I->seeResponseCodeIs(200);
        $I->seeInCurrentUrl('request/view');

        $I->see('Pedido teste Codeception');
    }
}
