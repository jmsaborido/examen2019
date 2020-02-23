<?php

namespace app\controllers;

use Yii;
use app\models\Citas;
use app\models\CitasSearch;
use app\models\Especialidades;
use app\models\Especialistas;
use DateInterval;
use DateTime;
use yii\bootstrap4\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


use function Matrix\identity;

/**
 * CitasController implements the CRUD actions for Citas model.
 */
class CitasController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Citas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitasSearch(['id' => Yii::$app->user->identity]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionHistorial()
    {
        $searchModel = new CitasSearch(['id' => Yii::$app->user->identity]);
        $dataProvider = $searchModel->search(array_merge(Yii::$app->request->queryParams, ['actual' => false]));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Citas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Citas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Citas(['usuario_id' => Yii::$app->user->id]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::debug($model->attributes);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $especialidades = Especialidades::lista();
        $especialidad_id = key($especialidades);
        $especialistas = Especialistas::lista($especialidad_id);

        return $this->render('create', [
            'model' => $model,
            'especialidades' => ['' => ''] + $especialidades,
            'especialistas' => ['' => ''] // , $especialistas),
        ]);
    }

    /**
     * Updates an existing Citas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Citas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Citas::findOne(['id' => $id]);

        if ($model->instante > date('Y-m-d H:i:s')) {
            $model->delete();
        } else {
            Yii::$app->session->setFlash('error', 'No puedes borrar una cita pasada ');
        }

        return $this->redirect(['index']);
    }

    public function actionEspecialistas($especialidad_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Especialistas::lista($especialidad_id);
    }

    public function actionHueco($especialista_id)
    {
        $especialista = Especialistas::findOne($especialista_id);
        $horaMinima = $especialista->hora_minima;
        $horaMaxima = $especialista->hora_maxima;
        $duracion = new DateInterval($especialista->duracion);
        $ahora = new DateTime();
        $instante = new DateTime(date('Y-m-d') . ' ' . $horaMinima);

        for (;;) {
            if (
                $instante <= $ahora || Citas::find()
                ->where([
                    'especialista_id' => $especialista_id,
                    'instante' => $instante->format('Y-m-d H:i:s'),
                ])->exists()
            ) {
                $instante->add($duracion);
                $maximo = new DateTime($instante->format('Y-m-d') . ' ' . $horaMaxima);
                if ($instante >= $maximo) {
                    $instante->add(new DateInterval('P1D'));
                    $instante = new DateTime($instante->format('Y-m-d') . ' ' . $horaMinima);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'valor' => $instante->format('Y-m-d H:i:s'),
                    'formateado' => Yii::$app->formatter->asDatetime($instante),
                ];
            }
        }
    }

    /**
     * Finds the Citas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Citas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Citas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
