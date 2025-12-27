<?php

namespace common\models;
namespace app\mosquitto;

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
 * @property string $created_at
 * @property string|null $updated_at
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

    #region API MOSQUITTO
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $id=$this->id;
        $user_id=$this->user_id;
        $first_name=$this->first_name;
        $last_name=$this->last_name;
        $phone=$this->phone;
        $created_at=$this->created_at;
        $updated_at=$this->updated_at;
        $myObj=new \stdClass();

        $myObj->id=$id;
        $myObj->user_id=$user_id;
        $myObj->first_name=$first_name;
        $myObj->last_name=$last_name;
        $myObj->phone=$phone;
        $myObj->created_at=$created_at;
        $myObj->updated_at=$updated_at;
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
            [['first_name', 'last_name', 'phone', 'updated_at'], 'default', 'value' => null],
            [['availability'], 'default', 'value' => 'disponivel'],
            [['user_id', 'created_at'], 'required'],
            [['user_id'], 'integer'],
            [['availability'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
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
     * Delets a profile.
     *
     * @return \yii\db\ActiveQuery
     */
    public function deleteProfile(){

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
}
