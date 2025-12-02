<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "request_attachment".
 *
 * @property int $id
 * @property int $request_id
 * @property int $uploaded_by
 * @property string $file_path
 * @property string $file_name
 * @property int $created_at
 * @property string $type Define o tipo de anexo: ficheiro genérico ou orçamento.
 * @property string|null $approval_status Estado de aprovação do anexo quando for um orçamento: pendente, aprovado ou rejeitado.
 * @property int|null $approved_by Utilizador que aprovou ou rejeitou o orçamento (geralmente o cliente).
 * @property int|null $approved_at Timestamp da aprovação/rejeição do orçamento.
 * @property string|null $notes Observações deixadas pelo cliente ao aprovar ou rejeitar o orçamento.
 *
 * @property User $approvedBy
 * @property Request $request
 * @property User $uploadedBy
 */
class RequestAttachment extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    #region Const
    const TYPE_GENERIC = 'generic';
    const TYPE_QUOTATION = 'quotation';
    const APPROVAL_STATUS_PENDING = 'pending';
    const APPROVAL_STATUS_APPROVED = 'approved';
    const APPROVAL_STATUS_REJECTED = 'rejected';
    #endregion

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_attachment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['approval_status', 'approved_by', 'approved_at', 'notes'], 'default', 'value' => null],
            [['type'], 'default', 'value' => 'generic'],
            [['request_id', 'uploaded_by', 'file_path', 'file_name', 'created_at'], 'required'],
            [['request_id', 'uploaded_by', 'approved_by', 'approved_at'], 'integer'],
            [['created_at'], 'safe'],
            [['type', 'approval_status'], 'string'],
            [['file_path', 'file_name', 'notes'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            ['approval_status', 'in', 'range' => array_keys(self::optsApprovalStatus())],
            [['request_id'], 'exist', 'skipOnError' => true, 'targetClass' => Request::class, 'targetAttribute' => ['request_id' => 'id']],
            [['uploaded_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['uploaded_by' => 'id']],
            [['approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['approved_by' => 'id']],
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
            'uploaded_by' => 'Uploaded By',
            'file_path' => 'File Path',
            'file_name' => 'File Name',
            'created_at' => 'Created At',
            'type' => 'Type',
            'approval_status' => 'Approval Status',
            'approved_by' => 'Approved By',
            'approved_at' => 'Approved At',
            'notes' => 'Notes',
        ];
    }

    /**
     * Gets query for [[ApprovedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(User::class, ['id' => 'approved_by']);
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
     * Gets query for [[UploadedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedBy()
    {
        return $this->hasOne(User::class, ['id' => 'uploaded_by']);
    }


    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_GENERIC => 'generic',
            self::TYPE_QUOTATION => 'quotation',
        ];
    }

    /**
     * column approval_status ENUM value labels
     * @return string[]
     */
    public static function optsApprovalStatus()
    {
        return [
            self::APPROVAL_STATUS_PENDING => 'pending',
            self::APPROVAL_STATUS_APPROVED => 'approved',
            self::APPROVAL_STATUS_REJECTED => 'rejected',
        ];
    }

    /**
     * @return string
     */
    public function displayType()
    {
        return self::optsType()[$this->type];
    }

    /**
     * @return bool
     */
    public function isTypeGeneric()
    {
        return $this->type === self::TYPE_GENERIC;
    }

    public function setTypeToGeneric()
    {
        $this->type = self::TYPE_GENERIC;
    }

    /**
     * @return bool
     */
    public function isTypeQuotation()
    {
        return $this->type === self::TYPE_QUOTATION;
    }

    public function setTypeToQuotation()
    {
        $this->type = self::TYPE_QUOTATION;
    }

    /**
     * @return string
     */
    public function displayApprovalStatus()
    {
        return self::optsApprovalStatus()[$this->approval_status];
    }

    /**
     * @return bool
     */
    public function isApprovalStatusPending()
    {
        return $this->approval_status === self::APPROVAL_STATUS_PENDING;
    }

    public function setApprovalStatusToPending()
    {
        $this->approval_status = self::APPROVAL_STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isApprovalStatusApproved()
    {
        return $this->approval_status === self::APPROVAL_STATUS_APPROVED;
    }

    public function setApprovalStatusToApproved()
    {
        $this->approval_status = self::APPROVAL_STATUS_APPROVED;
    }

    /**
     * @return bool
     */
    public function isApprovalStatusRejected()
    {
        return $this->approval_status === self::APPROVAL_STATUS_REJECTED;
    }

    public function setApprovalStatusToRejected()
    {
        $this->approval_status = self::APPROVAL_STATUS_REJECTED;
    }
}
