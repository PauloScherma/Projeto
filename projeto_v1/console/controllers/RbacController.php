<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); // limpa tudo

        // ===== PERMISSÕES =====
        $viewDashboard = $auth->createPermission('viewDashboard');
        $viewDashboard->description = 'Ver painel geral';
        $auth->add($viewDashboard);

        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Gerir utilizadores';
        $auth->add($manageUsers);

        $createOrder = $auth->createPermission('createOrder');
        $createOrder->description = 'Criar ordem de serviço';
        $auth->add($createOrder);

        $updateOrder = $auth->createPermission('updateOrder');
        $updateOrder->description = 'Atualizar ordem de serviço';
        $auth->add($updateOrder);

        $assignTechnician = $auth->createPermission('assignTechnician');
        $assignTechnician->description = 'Atribuir técnico a uma ordem';
        $auth->add($assignTechnician);

        $closeOrder = $auth->createPermission('closeOrder');
        $closeOrder->description = 'Fechar ordem de serviço';
        $auth->add($closeOrder);

        $viewOwnOrders = $auth->createPermission('viewOwnOrders');
        $viewOwnOrders->description = 'Ver apenas as suas ordens';
        $auth->add($viewOwnOrders);

        $updateOwnProfile = $auth->createPermission('updateOwnProfile');
        $updateOwnProfile->description = 'Atualizar o próprio perfil';
        $auth->add($updateOwnProfile);

        // ===== ROLES =====
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);
        $auth->addChild($cliente, $viewOwnOrders);
        $auth->addChild($cliente, $updateOwnProfile);

        $tecnico = $auth->createRole('tecnico');
        $auth->add($tecnico);
        $auth->addChild($tecnico, $viewDashboard);
        $auth->addChild($tecnico, $updateOrder);
        $auth->addChild($tecnico, $closeOrder);
        $auth->addChild($tecnico, $updateOwnProfile);

        $gestor = $auth->createRole('gestor');
        $auth->add($gestor);
        $auth->addChild($gestor, $tecnico); // herda permissões do técnico
        $auth->addChild($gestor, $createOrder);
        $auth->addChild($gestor, $assignTechnician);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $gestor); // herda tudo
        $auth->addChild($admin, $manageUsers);

        // ===== ATRIBUIR ROLES A UTILIZADORES (exemplo) =====
        $auth->assign($admin, 1);
        $auth->assign($gestor, 2);
        $auth->assign($tecnico, 3);
        $auth->assign($cliente, 4);

        // ===== ADMIN HARDCODED =====
        // Verifica se já existe um user admin
        $adminUser = User::find()->where(['username' => 'admin'])->one();

        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->username = 'admin';
            $adminUser->email = 'admin@admin.com';
            $adminUser->setPassword('admin'); // escolhe uma senha segura!
            $adminUser->generateAuthKey();
            $adminUser->generateEmailVerificationToken();
            $adminUser->save(false); // false para ignorar validação (já que é hardcoded)
        }

        // Atribui o papel de admin
        $adminRole = $auth->getRole('admin');
        $auth->assign($adminRole, $adminUser->id);

        echo "RBAC inicializado com sucesso!\n";
    }
}