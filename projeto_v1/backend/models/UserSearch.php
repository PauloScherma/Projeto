<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user;

/**
 * UserSearch represents the model behind the search form of `common\models\user`.
 */
class UserSearch extends User
{
    public $role;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role'], 'safe'],
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = user::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'verification_token', $this->verification_token]);

        //START - Lógica show user menos admin se gestor
        $currentUserRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
        $isGestor = isset($currentUserRoles['gestor']);
        if ($isGestor) {
            $query->leftJoin('auth_assignment', 'id = user_id');
            $query->andWhere(['<>', 'auth_assignment.item_name', 'admin']);
            $query->groupBy('id');
        }
        //END - Lógica show user menos admin se gestor

        //START - Lógica do filtro da sidebar
        //query diretamente com a tabela auth_assigment na base de dados
        $query->leftJoin('{{%auth_assignment}}', '{{%auth_assignment}}.user_id = {{%user}}.id');
        //filtra pela role
        $query->andFilterWhere(['like', '{{%auth_assignment}}.item_name', $this->role]);
        //agropa pelo id do user
        $query->groupBy('user.id');
        //END - Lógica do filtro da sidebar

        return $dataProvider;
    }
}
