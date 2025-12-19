<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    #region Constants
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    #endregion

    #region Variaveis apoio
    public $roleName;
    public $password;
    #endregion

    /**
     * Substitui o delete padrão (hard delete) por um soft delete (cancelamento).
     * @return bool|int O resultado do save() ou false.
     */
    public function deleteRequest()
    {
        // Verifica se o pedido já foi cancelado
        if ($this->status !== 10) {
            Yii::$app->session->setFlash('error', 'Este user já se encontra cancelado.');
            return false;
        }

        // Atribui os valores do "soft delete"
        $this->status = self::STATUS_DELETED;

        // Salva o modelo (realiza o soft delete)
        return $this->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            [['password'], 'required', 'on' => 'create'],
            [['password'], 'string', 'min' => 6],
            ['password', 'safe'],

            ['roleName', 'required'],
            ['roleName', 'safe'],
        ];
    }

    /**
     * Retorna a role do utilizador.
     * @return string|null
     */
    public function getRoleName()
    {
        if ($this->roleName === null) {
            $authManager = Yii::$app->authManager;
            $assignments = $authManager->getAssignments($this->id);

            if (!empty($assignments)) {
                // Pega no nome da primeira função que aparecer na lista
                $firstAssignment = reset($assignments);
                $this->roleName = $firstAssignment->roleName;
            }
        }

        return $this->roleName;
    }

    /**
     * Define a função para ser salva.
     * @param string $value
     */
    public function setRoleName($value)
    {
        $this->roleName = $value;
    }

    /**
     * Pega todos os tecnico
     * @return array todos os user com rule tecnico
     */
    public static function getAllTechnicians()
    {
        $auth = Yii::$app->authManager;

        $technicianIds = $auth->getUserIdsByRole('tecnico');

        $technicians = User::find()
            ->where(['id' => $technicianIds])
            ->all();

        return \yii\helpers\ArrayHelper::map($technicians, 'id', 'username');
    }

    /**
     * Hashifica a password e cria a authkey antes de salvar
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->password) {
                // Usa o méto.do setPassword para hashificar
                $this->setPassword($this->password);
                $this->generateAuthKey();
            }
            // Moda o status
            if ($insert) {
                $this->status = self::STATUS_ACTIVE;
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Pega o id
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Pega o profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);

    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
