<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\Request;

class RequestTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testChangeTitle()
    {
        $id = $this->tester->haveRecord('request', ['title' => 'test']);
        $request = Request::findOne($id);
        $request->title = 'testChangedTitle';
    }
}
