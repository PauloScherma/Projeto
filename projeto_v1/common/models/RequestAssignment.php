<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "request_assignment".
 *
 * @property int $id
 * @property int $request_id
 * @property int|null $from_technician
 * @property int $to_technician
 * @property int $changed_by
 * @property string $created_at
 *
 * @property User $changedBy
 * @property User $fromTechnician
 * @property Request $request
 * @property User $toTechnician
 */
class RequestAssignment extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_technician'], 'default', 'value' => null],
            [['request_id', 'to_technician', 'changed_by', 'created_at'], 'required'],
            [['request_id', 'from_technician', 'to_technician', 'changed_by'], 'integer'],
            [['created_at'], 'safe'],
            [['changed_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['changed_by' => 'id']],
            [['to_technician'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['to_technician' => 'id']],
            [['from_technician'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['from_technician' => 'id']],
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
            'from_technician' => 'From Technician',
            'to_technician' => 'To Technician',
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
     * Gets query for [[FromTechnician]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFromTechnician()
    {
        return $this->hasOne(User::class, ['id' => 'from_technician']);
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

    /**
     * Gets query for [[ToTechnician]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToTechnician()
    {
        return $this->hasOne(User::class, ['id' => 'to_technician']);
    }

}
