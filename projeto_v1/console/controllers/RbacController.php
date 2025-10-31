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

        //tabela request
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

        $cancelRequest = $auth->createPermission('cancelRequest');
        $cancelRequest->description = 'Cancelar o pedido';
        $auth->add($cancelRequest);

        $changePriority = $auth->createPermission('changePriority');
        $changePriority->description = 'Atualizar a priporidade';
        $auth->add($changePriority);

        $changeStatus = $auth->createPermission('changestatus');
        $changeStatus -> description = 'Atualizar o status do request';
        $auth->add($changeStatus);

        //tavela request_rate
        $viewRating = $auth->createPermission('viewrating');
        $viewRating -> description = 'Ver os ratings';
        $auth->add($viewRating);

        $createRating = $auth->createPermission('createrating');
        $createRating -> description = 'Criar um rating';
        $auth->add($createRating);

        $updateRating = $auth->createPermission('updaterating');
        $updateRating -> description = 'Atualizar um rating';
        $auth->add($updateRating);

        $deleteRating = $auth->createPermission('deleterating');
        $deleteRating -> description = 'Remover um rating';
        $auth->add($deleteRating);

        //tabela request_assigment
        $assignTechnician = $auth->createPermission('assignTechnician');
        $assignTechnician->description = 'Atribuir técnico';
        $auth->add($assignTechnician);

        $changeTechnician = $auth->createPermission('changeTechnician');
        $changeTechnician->description = 'Muda o técnico';
        $auth->add($changeTechnician);

        //tabela user
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

        $validateBudget = $auth->createPermission('validatebudget');
        $validateBudget->description = 'Validar o budget do request';
        $auth->add($validateBudget);

        $changeAvailability = $auth->createPermission('changeavailability');
        $changeAvailability -> description = 'Atualizar o disponibilidade do tecnico';
        $auth->add($changeAvailability);

        //tabela request_attachement
        $viewReport = $auth->createPermission('viewreport');
        $viewReport->description = 'Ver o report';
        $auth->add($viewReport);

        $createReport = $auth->createPermission('createreport');
        $createReport -> description = 'Criar o report';
        $auth->add($createReport);

        $updateReport = $auth->createPermission('updatereport');
        $updateReport -> description = 'Atualizar o report';
        $auth->add($updateReport);

        $deleteReport = $auth->createPermission('deletereport');
        $deleteReport -> description = 'Remover o report';
        $auth->add($deleteReport);

        //tabela calendar_event
        $viewAppointment = $auth->createPermission('viewappointment');
        $viewAppointment -> description = 'Ver o marcação';
        $auth->add($viewAppointment);

        $createAppointment = $auth->createPermission('createappointment');
        $createAppointment -> description = 'Criar o marcação';
        $auth->add($createAppointment);

        $updateAppointment = $auth->createPermission('updateappointment');
        $updateAppointment -> description = 'Atualizar o marcação';
        $auth->add($updateAppointment);

        $deleteAppointment = $auth->createPermission('deleteappointment');
        $deleteAppointment -> description = 'Remover o marcação';
        $auth->add($deleteAppointment);

        //outras
        $viewDashboard = $auth->createPermission('viewDashboard');
        $viewDashboard->description = 'Ver a dashboard';
        $auth->add($viewDashboard);

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