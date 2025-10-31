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

        $auth->removeAll();

        //Permissões de domínio

        // ---- REQUEST ----
        $requestView = $auth->createPermission('request.view');
        $requestView->description = 'Ver pedidos';
        $auth->add($requestView);

        $requestCreate = $auth->createPermission('request.create');
        $requestCreate->description = 'Criar pedidos';
        $auth->add($requestCreate);

        $requestUpdate = $auth->createPermission('request.update');
        $requestUpdate->description = 'Atualizar pedidos';
        $auth->add($requestUpdate);

        $requestDelete = $auth->createPermission('request.delete');
        $requestDelete->description = 'Remover pedidos';
        $auth->add($requestDelete);

        $requestCancel = $auth->createPermission('request.cancel');
        $requestCancel->description = 'Cancelar pedidos';
        $auth->add($requestCancel);

        $requestChangePriority = $auth->createPermission('request.changePriority');
        $requestChangePriority->description = 'Alterar prioridade do pedido';
        $auth->add($requestChangePriority);

        $requestChangeStatus = $auth->createPermission('request.changeStatus');
        $requestChangeStatus->description = 'Alterar estado do pedido';
        $auth->add($requestChangeStatus);

        // validação/rejeição de orçamento faz parte do fluxo do request
        $requestValidateBudget = $auth->createPermission('request.validateBudget');
        $requestValidateBudget->description = 'Validar ou rejeitar orçamento do pedido';
        $auth->add($requestValidateBudget);

        // ---- RATING ----
        $ratingView = $auth->createPermission('rating.view');
        $ratingView->description = 'Ver avaliações';
        $auth->add($ratingView);

        $ratingCreate = $auth->createPermission('rating.create');
        $ratingCreate->description = 'Criar avaliações';
        $auth->add($ratingCreate);

        $ratingUpdate = $auth->createPermission('rating.update');
        $ratingUpdate->description = 'Atualizar avaliações';
        $auth->add($ratingUpdate);

        $ratingDelete = $auth->createPermission('rating.delete');
        $ratingDelete->description = 'Remover avaliações';
        $auth->add($ratingDelete);

        // ---- REQUEST ASSIGNMENT ----
        $assignmentAssignTechnician = $auth->createPermission('assignment.assignTechnician');
        $assignmentAssignTechnician->description = 'Atribuir técnico a pedido';
        $auth->add($assignmentAssignTechnician);

        $assignmentChangeTechnician = $auth->createPermission('assignment.changeTechnician');
        $assignmentChangeTechnician->description = 'Mudar técnico do pedido';
        $auth->add($assignmentChangeTechnician);

        // ---- ATTACHMENT ----
        $attachmentView = $auth->createPermission('attachment.view');
        $attachmentView->description = 'Ver anexos do pedido';
        $auth->add($attachmentView);

        $attachmentCreate = $auth->createPermission('attachment.create');
        $attachmentCreate->description = 'Criar anexos do pedido';
        $auth->add($attachmentCreate);

        $attachmentUpdate = $auth->createPermission('attachment.update');
        $attachmentUpdate->description = 'Atualizar anexos do pedido';
        $auth->add($attachmentUpdate);

        $attachmentDelete = $auth->createPermission('attachment.delete');
        $attachmentDelete->description = 'Remover anexos do pedido';
        $auth->add($attachmentDelete);

        // ---- CALENDAR EVENT ----
        $appointmentView = $auth->createPermission('appointment.view');
        $appointmentView->description = 'Ver marcações';
        $auth->add($appointmentView);

        $appointmentCreate = $auth->createPermission('appointment.create');
        $appointmentCreate->description = 'Criar marcações';
        $auth->add($appointmentCreate);

        $appointmentUpdate = $auth->createPermission('appointment.update');
        $appointmentUpdate->description = 'Atualizar marcações';
        $auth->add($appointmentUpdate);

        $appointmentDelete = $auth->createPermission('appointment.delete');
        $appointmentDelete->description = 'Remover marcações';
        $auth->add($appointmentDelete);

        // ---- USER----
        $userView = $auth->createPermission('user.view');
        $userView->description = 'Ver utilizadores';
        $auth->add($userView);

        $userCreate = $auth->createPermission('user.create');
        $userCreate->description = 'Criar utilizadores';
        $auth->add($userCreate);

        $userUpdate = $auth->createPermission('user.update');
        $userUpdate->description = 'Atualizar utilizadores';
        $auth->add($userUpdate);

        $userDelete = $auth->createPermission('user.delete');
        $userDelete->description = 'Remover utilizadores';
        $auth->add($userDelete);

        $userChangeAvailability = $auth->createPermission('user.changeAvailability');
        $userChangeAvailability->description = 'Atualizar disponibilidade do técnico';
        $auth->add($userChangeAvailability);

        // ---- OUTROS ----
        $dashboardView = $auth->createPermission('dashboard.view');
        $dashboardView->description = 'Ver dashboard';
        $auth->add($dashboardView);

        // ---- ROLES ----
        // ---- CLIENTE ----
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);

        // cliente pode criar e ver os próprios pedidos e validar orçamento
        $auth->addChild($cliente, $requestView);
        $auth->addChild($cliente, $requestCreate);
        $auth->addChild($cliente, $requestValidateBudget);

        // avaliar o técnico
        $auth->addChild($cliente, $ratingCreate);
        $auth->addChild($cliente, $ratingView);

        // ---- TÉCNICO ----
        $tecnico = $auth->createRole('tecnico');
        $auth->add($tecnico);

        // técnico vê pedidos (normalmente os que estão atribuídos)
        $auth->addChild($tecnico, $requestView);

        // pode mudar estado e prioridade
        $auth->addChild($tecnico, $requestChangeStatus);
        $auth->addChild($tecnico, $requestChangePriority);

        // pode criar/atualizar anexos (relatórios, fotos, etc.)
        $auth->addChild($tecnico, $attachmentView);
        $auth->addChild($tecnico, $attachmentCreate);
        $auth->addChild($tecnico, $attachmentUpdate);

        // pode gerir marcações
        $auth->addChild($tecnico, $appointmentView);
        $auth->addChild($tecnico, $appointmentCreate);
        $auth->addChild($tecnico, $appointmentUpdate);

        // pode criar rating (avaliar cliente)
        $auth->addChild($tecnico, $ratingCreate);
        $auth->addChild($tecnico, $ratingView);

        // ---- GESTOR ----
        $gestor = $auth->createRole('gestor');
        $auth->add($gestor);

        // gestor herda tudo do técnico
        $auth->addChild($gestor, $tecnico);

        // gestor atribui técnicos e altera
        $auth->addChild($gestor, $assignmentAssignTechnician);
        $auth->addChild($gestor, $assignmentChangeTechnician);

        // gestor pode atualizar e cancelar pedidos
        $auth->addChild($gestor, $requestUpdate);
        $auth->addChild($gestor, $requestCancel);

        // gestor vê dashboard
        $auth->addChild($gestor, $dashboardView);

        // gestor pode mexer nos ratings (corrigir avaliações indevidas)
        $auth->addChild($gestor, $ratingUpdate);
        $auth->addChild($gestor, $ratingDelete);

        // gestor pode mexer nos anexos todos
        $auth->addChild($gestor, $attachmentDelete);

        // gestor pode mexer em marcações
        $auth->addChild($gestor, $appointmentDelete);

        // ---- ADMIN ----
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $gestor);
        $auth->addChild($admin, $userView);
        $auth->addChild($admin, $userCreate);
        $auth->addChild($admin, $userUpdate);
        $auth->addChild($admin, $userDelete);
        $auth->addChild($admin, $userChangeAvailability);

        // ---- Criação primeiro admin ----
        $adminUser = User::find()->where(['username' => 'admin'])->one();

        if (!$adminUser) {
            $adminUser = new User();
            $adminUser->username = 'admin';
            $adminUser->email = 'admin@admin.com';
            $adminUser->setPassword('admin123'); // mete algo menos óbvio
            $adminUser->generateAuthKey();
            $adminUser->generateEmailVerificationToken();
            $adminUser->save(false);
        }

        $auth->assign($admin, $adminUser->id);

        echo "RBAC inicializado com sucesso!\n";
    }
}