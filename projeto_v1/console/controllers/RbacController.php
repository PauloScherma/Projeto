<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); // limpa tudo

        //------Permissoes------
        $viewRequest = $auth->createPermission('viewRequest');
        $viewRequest->description = 'Ver os pedidos';
        $auth->add($viewRequest);

        $createRequest = $auth->createPermission('createRequest');
        $createRequest->description = 'Criar o pedido';
        $auth->add($createRequest);

        $updateRequest = $auth->createPermission('updateRequest');
        $updateRequest->description = 'Atualizar o pedido';
        $auth->add($updateRequest);

        $deleteRequest = $auth->createPermission('deleteRequest');
        $deleteRequest->description = 'Remover o pedido';
        $auth->add($deleteRequest);

        $viewRequestHistory = $auth->createPermission('viewRequestHistory');
        $viewRequestHistory->description = 'Ver o histórico de request';
        $auth->add($viewRequestHistory);

        $cancelRequest = $auth->createPermission('cancelRequest');
        $cancelRequest->description = 'Cancelar o pedido';
        $auth->add($cancelRequest);

        $rateRequest = $auth->createPermission('rateRequest');
        $rateRequest->description = 'Criar o pedido';
        $auth->add($rateRequest);

        $talkToTechnician = $auth->createPermission('talkToTechnician');
        $talkToTechnician->description = 'Permite falar com o tecnico especifico';
        $auth->add($talkToTechnician);

        $assignTechnician = $auth->createPermission('assignTechnician');
        $assignTechnician->description = 'Atribuir técnico';
        $auth->add($assignTechnician);

        $viewDashboard = $auth->createPermission('viewDashboard');
        $viewDashboard->description = 'Ver o painel geral';
        $auth->add($viewDashboard);

        $viewUsers = $auth->createPermission('viewUsers');
        $viewUsers->description = 'Ver os utilizadores';
        $auth->add($viewUsers);

        $createUsers = $auth->createPermission('createUser');
        $createUsers->description = 'Criar o utilizador';
        $auth->add($createUsers);

        $updateUsers = $auth->createPermission('updateUser');
        $updateUsers->description = 'Atualizar o utilizador';
        $auth->add($updateUsers);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Remover o utilizador';
        $auth->add($deleteUser);

        // ------ Roles ------
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);
        
        $tecnico = $auth->createRole('tecnico');
        $auth->add($tecnico);

        $gestor = $auth->createRole('gestor');
        $auth->add($gestor);
        $auth->addChild($gestor, $tecnico); // herda permissões do técnico

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $gestor); // herda permissões do gestor


        // ------ Criação do primeiro admin ------
        $adminUser = User::find()->where(['username' => 'admin'])->one();

        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->username = 'admin';
            $adminUser->email = 'admin@admin.com';
            $adminUser->setPassword('admin');
            $adminUser->generateAuthKey();
            $adminUser->generateEmailVerificationToken();
            $adminUser->save(false);
        }

        // Atribui o papel de admin
        $adminRole = $auth->getRole('admin');
        $auth->assign($adminRole, $adminUser->id);

        echo "RBAC inicializado com sucesso!\n";
    }
}