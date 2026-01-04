<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\Request;
use common\models\RequestRating;
use common\models\User;

class RequestRatingTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {

    }

    public function testCreateRating()
    {
        $admin = User::find()->where(['username' => 'admin'])->one();
        $adminId = $admin->id;

        $request = new Request();
        $request->customer_id = $adminId;
        $request->title = 'Title';
        $request->description = 'Description';
        $request->setPriorityToMedium();
        $request->setStatusToNew();
        $request->created_at = date('Y-m-d H:i:s');
        $request->save(false);

        $rating = new RequestRating();
        $rating->request_id = $request->id;
        $rating->title = "RatingTest";
        $rating->description = "RatingTest";
        $rating->score = "3";
        $rating->created_at = date('Y-m-d H:i:s');
        $rating->created_by = $adminId;
        $rating->save(false);

        $isSaved = $rating->save();

        $this->assertTrue($isSaved,'O modelo Request deve ser salvo com sucesso na BD. Erros: ' . print_r($rating->errors, true));
    }
}
