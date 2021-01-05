<?php

namespace app\controllers;

use Yii;
use app\models\Libros;
use app\models\Seleccion;
use app\models\Usuarios;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LibrosController implements the CRUD actions for Libros model.
 */
class LibrosController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete', 'seleccion'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete'],
                        'matchCallback' => function ($rule, $action) {
                            return (!Yii::$app->user->isGuest && Yii::$app->user->identity->id === 1);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['seleccion'],
                        'matchCallback' => function ($rule, $action) {

                            if (Yii::$app->user->isGuest){
                                Yii::$app->session->setFlash('error', 'No puedes acceder a la selección de libro sin iniciar sesión');
                                return false;
                            }

                            // Tenemos que ver si quiero que la seleccion solo la haga el admin
                            // o si queremos que el responsable de la rotacion sea el que haga la seleccion.

                            return true;
                        }
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all Libros models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Libros::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Libros model.
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
     * Creates a new Libros model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new Libros();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);

        return $this->render('pruebas');
    }

    /**
     * Updates an existing Libros model.
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
     * Deletes an existing Libros model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Te lleva a la lista de libros propuestos
     *
     * @return mixed
     */
    public function actionSeleccion()
    {
        // Quiero tener todos los libros de seleccion ordenados por usuario
        // y quiero representarlos con una tabla de 5 libros para cada usuario
        // pero quiero excluir aquellos usuarios que no tengan 5 libros en su selección

        $query = Seleccion::find()
        ->with('usuario')
        ->orderBy('usuario_id')
        ->where([]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('seleccion', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Libros model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Libros the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Libros::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
