<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\requestAttachment;

/**
 * requestAttachmentSearch represents the model behind the search form of `common\models\requestAttachment`.
 */
class requestAttachmentSearch extends requestAttachment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'request_id', 'uploaded_by', 'approved_by', 'approved_at'], 'integer'],
            [['file_path', 'file_name', 'created_at', 'type', 'approval_status', 'notes'], 'safe'],
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
        $query = requestAttachment::find();

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
            'uploaded_by' => $this->uploaded_by,
            'created_at' => $this->created_at,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at,
        ]);

        $query->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'approval_status', $this->approval_status])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
