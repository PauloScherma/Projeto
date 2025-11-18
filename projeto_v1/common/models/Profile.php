<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $availability
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Address $address
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const AVAILABILITY_DISPONIVEL = 'disponivel';
    const AVAILABILITY_INDISPONIVEL = 'indisponivel';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'phone'], 'default', 'value' => null],
            [['availability'], 'default', 'value' => 'disponivel'],
            [['user_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['availability'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 32],
            ['availability', 'in', 'range' => array_keys(self::optsAvailability())],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone' => 'Phone',
            'availability' => 'Availability',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Address]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::class, ['profile_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    /**
     * column availability ENUM value labels
     * @return string[]
     */
    public static function optsAvailability()
    {
        return [
            self::AVAILABILITY_DISPONIVEL => 'disponivel',
            self::AVAILABILITY_INDISPONIVEL => 'indisponivel',
        ];
    }

    /**
     * @return string
     */
    public function displayAvailability()
    {
        return self::optsAvailability()[$this->availability];
    }

    /**
     * @return bool
     */
    public function isAvailabilityDisponivel()
    {
        return $this->availability === self::AVAILABILITY_DISPONIVEL;
    }

    public function setAvailabilityToDisponivel()
    {
        $this->availability = self::AVAILABILITY_DISPONIVEL;
    }

    /**
     * @return bool
     */
    public function isAvailabilityIndisponivel()
    {
        return $this->availability === self::AVAILABILITY_INDISPONIVEL;
    }

    public function setAvailabilityToIndisponivel()
    {
        $this->availability = self::AVAILABILITY_INDISPONIVEL;
    }
    //For sync
    public static function getChangesSince($time)
    {
        return self::find()->where(['>', 'updated_at', $time])->all();
    }

}
