<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Citas;
use DateTime;
use DateTimeZone;
use Yii;

/**
 * CitasSearch represents the model behind the search form of `app\models\Citas`.
 */
class CitasSearch extends Citas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'usuario_id', 'especialista_id'], 'integer'],
            [['instante'], 'safe'],
            [['especialista.nombre', 'especialista.especialidad.especialidad'], 'string']
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'especialista.especialidad.especialidad',
        ]);
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
        $query = Citas::find()
            ->joinWith(['especialista ea' => function ($q) {
                $q->joinWith('especialidad ed');
            }]);
        // add conditions that should always apply here
        $query->where(['usuario_id' => Yii::$app->user->id]);
        $consulta = isset($params['actual']) ? '<' : '>';
        $query->andWhere('instante' . $consulta . 'LOCALTIMESTAMP');
        a:;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['especialista.nombre'] = [
            'asc' => ['ea.nombre' => SORT_ASC],
            'desc' => ['ea.nombre' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['especialista.especialidad.especialidad'] = [
            'asc' => ['ed.especialidad' => SORT_ASC],
            'desc' => ['ed.especialidad' => SORT_DESC],
        ];


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($this->instante != '') {
            $instante = new DateTime(
                $this->instante,
                new DateTimeZone(Yii::$app->formatter->timeZone)
            );
            $instante->setTimezone(new DateTimeZone('UTC'));
            $instante = $instante->format('Y-m-d H:i:s');
        }

        $query->andFilterWhere([
            'ilike',
            'ed.especialidad',
            $this->getAttribute('especialista.especialidad.especialidad'),
        ])
            ->andFilterWhere([
                'ilike',
                'ea.nombre',
                $this->getAttribute('especialista.nombre'),
            ]);
        return $dataProvider;
    }
}
