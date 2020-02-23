<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Especialistas;

/**
 * EspecialistasSearch represents the model behind the search form of `app\models\Especialistas`.
 */
class EspecialistasSearch extends Especialistas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'especialidad_id'], 'integer'],
            [['nombre', 'hora_minima', 'hora_maxima', 'duracion', 'especialidad.especialidad'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Especialistas::find()->joinWith('especialidad e');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $dataProvider->sort->attributes['especialidad.especialidad'] = [
            'asc' => ['e.especialidad' => SORT_ASC],
            'desc' => ['e.especialidad' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'especialidad_id' => $this->especialidad_id,
            'hora_minima' => $this->hora_minima,
            'hora_maxima' => $this->hora_maxima,
        ]);

        $query->andFilterWhere(['ilike', 'nombre', $this->nombre])
            ->andFilterWhere(['ilike', 'duracion', $this->duracion])
            ->andFilterWhere(['ilike', 'e.especialidad', $this->getAttribute('especialidad.especialidad')]);


        return $dataProvider;
    }
}
