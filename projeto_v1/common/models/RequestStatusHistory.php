<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "request_status_history".
 *
 * @property int $id
 * @property int $request_id
 * @property string $from_status
 * @property string $to_status
 * @property int $changed_by
 * @property string $created_at
 *
 * @property User $changedBy
 * @property Request $request
 */
class RequestStatusHistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_status_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'from_status', 'to_status', 'changed_by', 'created_at'], 'required'],
            [['request_id', 'changed_by'], 'integer'],
            [['created_at'], 'safe'],
            [['from_status', 'to_status'], 'string', 'max' => 32],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['changed_by' => 'id']],
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
            'request_id' => 'Request ID',
            'from_status' => 'From Status',
            'to_status' => 'To Status',
            'changed_by' => 'Changed By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ChangedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChangedBy()
    {
        return $this->hasOne(User::class, ['id' => 'changed_by']);
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
