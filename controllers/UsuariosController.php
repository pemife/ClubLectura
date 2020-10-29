<?php

namespace app\controllers;

use app\models\Libros;
use Yii;
use app\models\Usuarios;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    const MAX_LIBROS = 5;

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
                'only' => [
                    'mis-libros', 'anadir-libro'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['mis-libros'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                Yii::$app->session->setFlash('error', '¡No puedes ver tu lista de libros sin iniciar sesión!');
                                return false;
                            }

                            return true;
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['anadir-libro'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                Yii::$app->session->setFlash('error', '¡No puedes añadir nada a tu lista de libros sin iniciar sesión!');
                                return false;
                            }

                            $l = Yii::$app->request->queryParams['l'];

                            if (Yii::$app->user->identity->libros->length > self::MAX_LIBROS) {
                                Yii::$app->session->setFlash('error', '¡No puedes añadr más de ' + self::MAX_LIBROS + ' libros a tu lista');
                                return false;
                            }

                            // TODO
                            if (!Libros::findOne($l)) {
                                Yii::$app->session->setFlash('error', '¡No puedes añadir a tu lista un libro que no existe!');
                                return false;
                            }

                            return true;
                        }
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Usuarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Usuarios::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Usuarios model.
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
     * Creates a new Usuarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Usuarios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Usuarios model.
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
     * Deletes an existing Usuarios model.
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
     * Función que te lleva a la vista de los libros propuestos por el usuario
     * para poder asi visualizarla/modificarla.
     *
     * @param Integer $u    Id del usuario del que queremos ver la lista
     * @return mixed
     */
    public function actionMisLibros()
    {
        $dataProvider = new ArrayDataProvider([
            'models' => Yii::$app->user->identity->libros
        ]);

        return $this->render('misLibros', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Función que un usuario usará para añadir un libro a su lista
     *
     * @param int $l    Id del libro a añadir
     * @return void
     */
    public function actionAnadirLibro($l)
    {
        $this->findModel(Yii::$app->user->identity->id)->link('Libros', Libros::findOne($l));
        return true;
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
