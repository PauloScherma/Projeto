<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RequestStatusHistory;

/**
 * RequestStatusHistorySearch represents the model behind the search form of `common\models\RequestStatusHistory`.
 */
class RequestStatusHistorySearch extends RequestStatusHistory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'request_id', 'changed_by'], 'integer'],
            [['from_status', 'to_status', 'created_at'], 'safe'],
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
        $query = RequestStatusHistory::find();

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
            'request_id' => $this->request_id,
            'changed_by' => $this->changed_by,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'from_status', $this->from_status])
            ->andFilterWhere(['like', 'to_status', $this->to_status]);

        return $dataProvider;
    }
}
