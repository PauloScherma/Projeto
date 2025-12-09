<?php

use common\models\Request;
use common\models\RequestRating;
use hail812\adminlte\widgets\InfoBox;

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];

// Request Model (Lógica de dados mantida)
$requestCount = Request::find()->count();
$requestNewCount = Request::find()->where('status = "new"')->count();
$requestInProgressCount = Request::find()->where('status = "in_progress"')->count();
$requestDoneCount = Request::find()->where('status = "completed"')->count();

// Protege contra divisão por zero
$requestMilestonPercentage = ($requestCount > 0) ? round($requestDoneCount / $requestCount * 100) : 0;
$requestMilestone = $requestCount - $requestDoneCount;

// Outros Modelos
$requestRatingCount = RequestRating::find()->count();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <?= InfoBox::widget([
                'text' => 'Total Requests',
                'number' => $requestCount,
                'icon' => 'fas fa-list-alt',
                'theme' => 'primary',
            ]) ?>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <?= InfoBox::widget([
                'text' => 'New Requests',
                'number' => $requestNewCount,
                'theme' => 'info',
                'icon' => 'fas fa-inbox',
            ]) ?>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <?= InfoBox::widget([
                'text' => 'Requests In Progress',
                'number' => $requestInProgressCount,
                'theme' => 'warning',
                'icon' => 'fas fa-hourglass-half',
            ]) ?>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <?= InfoBox::widget([
                'text' => 'Requests Done',
                'number' => $requestDoneCount,
                'theme' => 'success',
                'icon' => 'fas fa-check-circle',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-6 col-12">
            <?= InfoBox::widget([
                'text' => 'Our Goal (Completed)',
                'number' => $requestCount.' requests',
                'icon' => 'fas fa-bullseye',
                'progress' => [
                    'width' => $requestMilestonPercentage.'%',
                    'description' => $requestMilestone.' requests to go'
                ]
            ]) ?>
        </div>

    </div>
</div>