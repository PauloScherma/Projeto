<?php
namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\Request;
use common\models\User;
use Yii;

class RequestTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {

    }

    public function testCreateRequest()
    {
        $request = new Request();
        $request->customer_id = 53;
        $request->title = 'Title';
        $request->description = 'Description';
        $request->setPriorityToMedium();
        $request->setStatusToNew();
        $request->created_at = date('Y-m-d H:i:s');

        $isSaved = $request->save();

        $this->assertTrue($isSaved,'O modelo Request deve ser salvo com sucesso na BD. Erros: ' . print_r($request->errors, true));
    }

    public function testUpdateRequest()
    {
        $request = new Request();
        $request->customer_id = 53;
        $request->title = 'Title';
        $request->description = 'Description';
        $request->setPriorityToMedium();
        $request->setStatusToNew();
        $request->created_at = date('Y-m-d H:i:s');
        $request->save();

        $request->updated_at = date('Y-m-d H:i:s');
        $isSaved = $request->save();
        $updated_at=$request->updated_at;


        $this->assertTrue($isSaved,'O modelo Request deve ser update com sucesso na BD. Erros: ' . print_r($request->errors, true));
        $this->assertNotNull($updated_at, 'O updated_at deve ter sido atualizado após o save().');
    }

    public function testDeleteRequest(){

        $user = new User(['id' => 100]);
        Yii::$app->user->setIdentity($user);

        $request = new Request();
        $request->canceled_at = null;
        $request->deleteRequest();
        $requestStatus = $request->status;

        $this->assertEquals($requestStatus, "canceled", 'O status não tem o valor certo');
    }
}
