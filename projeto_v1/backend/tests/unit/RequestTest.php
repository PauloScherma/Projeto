<?php
namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\Request;
use common\models\RequestAttachment;
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
        $admin = User::find()->where(['username' => 'admin'])->one();
        $adminId = $admin->id;

        $request = new Request();
        $request->customer_id = $adminId;
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
        $admin = User::find()->where(['username' => 'admin'])->one();
        $adminId = $admin->id;

        $request = new Request();
        $request->customer_id = $adminId;
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
        $admin = User::find()->where(['username' => 'admin'])->one();
        Yii::$app->user->setIdentity($admin);

        $request = Request::find()->one();
        $request->deleteRequest();
        $requestStatus = $request->status;
        $this->assertEquals($requestStatus, "canceled", 'O status não tem o valor certo');
    }

    public function testReadRequest(){
        $admin = User::find()->where(['username' => 'admin'])->one();
        $adminId = $admin->id;

        $request = new Request();
        $request->customer_id = $adminId;
        $request->title = 'Pedido de Teste';
        $request->description = 'Verificando a leitura';
        $request->created_at = date('Y-m-d H:i:s');
        $request->save(false);

        $id = $request->id;

        $model = Request::findOne($id);

        $this->assertNotNull($model, 'O Request deveria ter sido encontrado no banco.');
        $this->assertEquals('Pedido de Teste', $model->title);
        $this->assertEquals($adminId, $model->customer_id);
    }

    public function testUploadAttachment(){
        $admin = User::find()->where(['username' => 'admin'])->one();
        Yii::$app->user->setIdentity($admin);

        $request = new Request();
        $request->customer_id = $admin->id;
        $request->title = 'Teste Upload';
        $request->created_at = date('Y-m-d H:i:s');
        $request->save(false);

        $tempFile = tempnam(sys_get_temp_dir(), 'test');
        file_put_contents($tempFile, 'conteudo');

        $file = new \yii\web\UploadedFile([
            'name' => 'documento_teste.txt',
            'tempName' => $tempFile,
            'type' => 'text/plain',
            'size' => filesize($tempFile),
            'error' => UPLOAD_ERR_OK,
        ]);

        $request->request_attachment = [$file];

        $success = $request->upload();

        $this->assertTrue($success, 'O upload falhou.');

        $attachment = RequestAttachment::find()->where(['request_id' => $request->id])->one();
        $this->assertNotNull($attachment, 'Attachment não encontrado.');
    }
}
