<?php
namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\Request;
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
        $request->customer_id = ('2');
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
        $request->customer_id = ('2');
        $request->title = 'Title';
        $request->description = 'Description';
        $request->setPriorityToMedium();
        $request->setStatusToNew();

        $request->created_at = date('Y-m-d H:i:s');
        $request->save();
        $created_at=$request->created_at;

        sleep(1);

        $request->updated_at = date('Y-m-d H:i:s');
        $request->save();
        $updated_at=$request->updated_at;

        $isSaved = $request->save();

        $this->assertTrue($isSaved,'O modelo Request deve ser update com sucesso na BD. Erros: ' . print_r($request->errors, true));
        $this->assertNotEquals($created_at, $updated_at, 'O updated_at deve ter sido atualizado após o save().');
    }

    public function testDeleteRequest(){

        $request = Request::findOne(1);


    }
}
