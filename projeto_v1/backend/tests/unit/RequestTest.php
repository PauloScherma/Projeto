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

    // tests
    public function testCreateRequest()
    {
        $request = new Request();
        $request->customer_id = ('2');
        $request->title = 'Title';
        $request->description = 'Description';
        $request->setPriorityToMedium();
        $request->setStatusToNew();
        $request->created_at = date('Y-m-d H:i:s');
        $request->save();
    }
}
