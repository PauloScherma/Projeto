<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
use common\models\Request;


/**
 * This is the model class for table "request_rating".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $request_id
 * @property int $score
 * @property string $created_at
 * @property int $created_by
 *
 * @property User $createdBy
 * @property Request $request
 */
class RequestRating extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'request_id', 'score', 'created_at', 'created_by'], 'required'],
            [['request_id', 'score', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 45],
            [['description'], 'string', 'max' => 255],
            [['request_id'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => Request::class, 'targetAttribute' => ['request_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'request_id' => 'Request ID',
            'score' => 'Score',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    #region API MOSQUITTO
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $id=$this->id;
        $request_id=$this->request_id;
        $title=$this->title;
        $description=$this->description;
        $score=$this->score;
        $created_at=$this->created_at;
        $created_by=$this->created_by;

        $myObj=new \stdClass();
        $myObj->id=$id;
        $myObj->request_id=$request_id;
        $myObj->title=$title;
        $myObj->description=$description;
        $myObj->score=$score;
        $myObj->created_at=$created_at;
        $myObj->created_by=$created_by;
        $myJSON= json_encode($myObj);

        if($insert)
            $this->FazPublishNoMosquitto("INSERT",$myJSON);
        else
            $this->FazPublishNoMosquitto("UPDATE",$myJSON);
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $id= $this->id;
        $myObj=new \stdClass();
        $myObj->id=$id;
        $myJSON= json_encode($myObj);
        $this->FazPublishNoMosquitto("DELETE",$myJSON);
    }
    #endregion

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }


    /**
     * Gets query for [[Request]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequest()
    {
        return $this->hasOne(Request::class, ['id' => 'request_id']);
    }

}
