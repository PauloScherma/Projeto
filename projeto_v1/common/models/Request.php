<?php

namespace common\models;
use common\models\User;
use app\models\CalendarEvent;
use app\models\RequestAssignment;
use app\models\RequestAttachment;
use app\models\RequestMessage;
use app\models\RequestRating;
use app\models\RequestStatusHistory;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $title
 * @property string|null $description
 * @property string $priority
 * @property string $status
 * @property int|null $current_technician_id
 * @property int|null $scheduled_start
 * @property int|null $canceled_at
 * @property int|null $canceled_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property CalendarEvent[] $calendarEvents
 * @property User $canceledBy
 * @property User $currentTechnician
 * @property User $customer
 * @property RequestAssignment[] $requestAssignments
 * @property RequestAttachment[] $requestAttachments
 * @property RequestMessage[] $requestMessages
 * @property RequestRating[] $requestRatings
 * @property RequestStatusHistory[] $requestStatusHistories
 */
class Request extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const STATUS_NEW = 'new';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_PARTS = 'waiting_parts';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELED = 'canceled';

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'current_technician_id', 'scheduled_start', 'canceled_at', 'canceled_by'], 'default', 'value' => null],
            [['priority'], 'default', 'value' => 'medium'],
            [['status'], 'default', 'value' => 'new'],
            [['customer_id', 'title'], 'required'],
            [['customer_id', 'current_technician_id', 'scheduled_start', 'canceled_at', 'canceled_by', 'created_at', 'updated_at'], 'integer'],
            [['description', 'priority', 'status'], 'string'],
            [['title'], 'string', 'max' => 140],
            ['priority', 'in', 'range' => array_keys(self::optsPriority())],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['canceled_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['canceled_by' => 'id']],
            [['current_technician_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['current_technician_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'description' => 'Description',
            'priority' => 'Priority',
            'status' => 'Status',
            'current_technician_id' => 'Current Technician ID',
            'scheduled_start' => 'Scheduled Start',
            'canceled_at' => 'Canceled At',
            'canceled_by' => 'Canceled By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CalendarEvents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendarEvents()
    {
        return $this->hasMany(CalendarEvent::class, ['request_id' => 'id']);
    }

    /**
     * Gets query for [[CanceledBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCanceledBy()
    {
        return $this->hasOne(User::class, ['id' => 'canceled_by']);
    }

    /**
     * Gets query for [[CurrentTechnician]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrentTechnician()
    {
        return $this->hasOne(User::class, ['id' => 'current_technician_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[RequestAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestAssignments()
    {
        return $this->hasMany(RequestAssignment::class, ['request_id' => 'id']);
    }

    /**
     * Gets query for [[RequestAttachments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestAttachments()
    {
        return $this->hasMany(RequestAttachment::class, ['request_id' => 'id']);
    }

    /**
     * Gets query for [[RequestMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestMessages()
    {
        return $this->hasMany(RequestMessage::class, ['request_id' => 'id']);
    }

    /**
     * Gets query for [[RequestRatings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestRatings()
    {
        return $this->hasMany(RequestRating::class, ['request_id' => 'id']);
    }

    /**
     * Gets query for [[RequestStatusHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestStatusHistories()
    {
        return $this->hasMany(RequestStatusHistory::class, ['request_id' => 'id']);
    }


    /**
     * column priority ENUM value labels
     * @return string[]
     */
    public static function optsPriority()
    {
        return [
            self::PRIORITY_LOW => 'low',
            self::PRIORITY_MEDIUM => 'medium',
            self::PRIORITY_HIGH => 'high',
        ];
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_ASSIGNED => 'assigned',
            self::STATUS_IN_PROGRESS => 'in_progress',
            self::STATUS_WAITING_PARTS => 'waiting_parts',
            self::STATUS_COMPLETED => 'completed',
            self::STATUS_CANCELED => 'canceled',
        ];
    }

    /**
     * @return string
     */
    public function displayPriority()
    {
        return self::optsPriority()[$this->priority];
    }

    /**
     * @return bool
     */
    public function isPriorityLow()
    {
        return $this->priority === self::PRIORITY_LOW;
    }

    public function setPriorityToLow()
    {
        $this->priority = self::PRIORITY_LOW;
    }

    /**
     * @return bool
     */
    public function isPriorityMedium()
    {
        return $this->priority === self::PRIORITY_MEDIUM;
    }

    public function setPriorityToMedium()
    {
        $this->priority = self::PRIORITY_MEDIUM;
    }

    /**
     * @return bool
     */
    public function isPriorityHigh()
    {
        return $this->priority === self::PRIORITY_HIGH;
    }

    public function setPriorityToHigh()
    {
        $this->priority = self::PRIORITY_HIGH;
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    public function setStatusToNew()
    {
        $this->status = self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isStatusAssigned()
    {
        return $this->status === self::STATUS_ASSIGNED;
    }

    public function setStatusToAssigned()
    {
        $this->status = self::STATUS_ASSIGNED;
    }

    /**
     * @return bool
     */
    public function isStatusInprogress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function setStatusToInprogress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    /**
     * @return bool
     */
    public function isStatusWaitingparts()
    {
        return $this->status === self::STATUS_WAITING_PARTS;
    }

    public function setStatusToWaitingparts()
    {
        $this->status = self::STATUS_WAITING_PARTS;
    }

    /**
     * @return bool
     */
    public function isStatusCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function setStatusToCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isStatusCanceled()
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function setStatusToCanceled()
    {
        $this->status = self::STATUS_CANCELED;
    }

    //For sync
    //For sync
    public static function getChangesSince($time)
    {
        return self::find()
            ->where(['>', 'updated_at', $time])
            ->all();
    }
}
