<?php

use common\models\User;
use yii\helpers\Url;

$username = Yii::$app->user->identity->username;

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">PSIAssist</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                    <img src="<?= Url::to(['/img/user-icon.jpg']) ?> " class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info ms-5">
                <a class="d-block "><?=$username?></a>
            </div>
        </div>
        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php

            $currentUserRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
            $isGestor = isset($currentUserRoles['gestor']);

            if ($isGestor) {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'Management', 'header' => true],
                        [
                            'label' => 'Requests',
                            'icon' => 'list-alt',
                            'items' => [
                                //'new', 'in_progress', 'completed', 'canceled'
                                ['label' => 'All Requests', 'icon' => 'folder-open', 'url' => ['/request/index']],
                                ['label' => 'New', 'icon' => 'plus-circle', 'url' => ['/request/index', 'RequestSearch[status]' => 'new']],
                                ['label' => 'In Progress', 'icon' => 'sync', 'url' => ['/request/index', 'RequestSearch[status]' => 'active']],
                                ['label' => 'Completed', 'icon' => 'check-circle', 'url' => ['/request/index', 'RequestSearch[status]' => 'closed']],
                                ['label' => 'Canceled', 'icon' => 'times-circle', 'url' => ['/request/index', 'RequestSearch[status]' => 'closed']],
                            ]
                        ],
                        [
                            'label' => 'Users',
                            'icon' => 'users',
                            'items' => [
                                ['label' => 'All Users', 'icon' => 'user-friends', 'url' => ['/user/index']],
                                ['label' => 'Manager', 'icon' => 'user-cog', 'url' => ['/user/index', 'UserSearch[role]' => 'gestor']],
                                ['label' => 'Technician', 'icon' => 'user-plus', 'url' => ['/user/index', 'UserSearch[role]' => 'tecnico']],
                                ['label' => 'Client', 'icon' => 'user', 'url' => ['/user/index', 'UserSearch[role]' => 'cliente']],
                            ]
                        ],
                    ],
                ]);
            }
            else{
                    echo \hail812\adminlte\widgets\Menu::widget([
                        'items' => [
                            ['label' => 'Management', 'header' => true],
                            [
                                'label' => 'Requests',
                                'icon' => 'list-alt',
                                'items' => [
                                    //'new', 'in_progress', 'completed', 'canceled'
                                    ['label' => 'All Requests', 'icon' => 'folder-open', 'url' => ['/request/index']],
                                    ['label' => 'New', 'icon' => 'plus-circle', 'url' => ['/request/index', 'RequestSearch[status]' => 'new']],
                                    ['label' => 'In Progress', 'icon' => 'sync', 'url' => ['/request/index', 'RequestSearch[status]' => 'in_progress']],
                                    ['label' => 'Completed', 'icon' => 'check-circle', 'url' => ['/request/index', 'RequestSearch[status]' => 'completed']],
                                    ['label' => 'Canceled', 'icon' => 'times-circle', 'url' => ['/request/index', 'RequestSearch[status]' => 'canceled']],
                                ]
                            ],
                            [
                                'label' => 'Users',
                                'icon' => 'users',
                                'items' => [
                                    ['label' => 'All Users', 'icon' => 'user-friends', 'url' => ['/user/index']],
                                    ['label' => 'Admins', 'icon' => 'user-shield', 'url' => ['/user/index', 'UserSearch[role]' => 'admin']],
                                    ['label' => 'Manager', 'icon' => 'user-cog', 'url' => ['/user/index', 'UserSearch[role]' => 'gestor']],
                                    ['label' => 'Technician', 'icon' => 'user-plus', 'url' => ['/user/index', 'UserSearch[role]' => 'tecnico']],
                                    ['label' => 'Client', 'icon' => 'user', 'url' => ['/user/index', 'UserSearch[role]' => 'cliente']],
                                ]
                            ],
                        ],
                    ]);
            }
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>