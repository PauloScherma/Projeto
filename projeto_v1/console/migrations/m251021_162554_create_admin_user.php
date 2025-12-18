<?php

use yii\db\Migration;
use common\models\User;

class m251021_162554_create_admin_user extends Migration
{
    public function safeUp()
    {
        // Verifica se o admin já existe
        $admin = User::find()->where(['username' => 'admin'])->one();
        if (!$admin) {
            $admin = new User();
            $admin->username = 'admin';
            $admin->email = 'admin@admin.com';
            $admin->setPassword('admin'); // altere para uma senha segura
            $admin->generateAuthKey();
            $admin->generateEmailVerificationToken();
            $admin->save(false);
        }

        // Atribui o papel de admin (RBAC)
        $auth = Yii::$app->authManager;
        $adminRole = $auth->getRole('admin');

        // Só atribui se ainda não estiver atribuído
        if (!$auth->getAssignment('admin', $admin->id)) {
            $auth->assign($adminRole, $admin->id);
        }
    }

    public function safeDown()
    {
        $admin = User::find()->where(['username' => 'admin'])->one();
        if ($admin) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($admin->id);
            $admin->deleteRequest();
        }
    }
}
