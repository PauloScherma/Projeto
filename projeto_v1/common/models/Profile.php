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
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Address $address
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    #region API MOSQUITTO
    public function FazPublishNoMosquitto($canal,$msg)
    {
        require_once dirname(__DIR__, 2) . '/mosquitto/phpMQTT.php';

        $server = "127.0.0.1";
        $port = 1883;
        $username = "";
        $password = "";
        $client_id = "phpMQTT-publisher";
        $mqtt = new \app\mosquitto\phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password))
        {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        }
        else { file_put_contents("debug.output","Time out!"); }
    }
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
            [['user_id', 'created_at'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 32],
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
}
