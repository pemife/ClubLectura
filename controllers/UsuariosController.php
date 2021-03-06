<?php

namespace app\controllers;

use app\models\Libros;
use app\models\Seleccion;
use Yii;
use app\models\Usuarios;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

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
                    'mis-libros', 'anadir-libro', 'borrar-libro', 'ordenar-lista-libros'
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

                            return true;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['borrar-libro'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                Yii::$app->session->setFlash('error', '¡No puedes borrar nada de tu lista de libros sin iniciar sesión!');
                                return false;
                            }

                            $l = Yii::$app->request->queryParams['l'];

                            if (sizeof(Yii::$app->user->identity->libros) < 1) {
                                Yii::$app->session->setFlash('error', '¡No puedes borrar nada de una lista de libros que está vacía!');
                                return false;
                            }

                            if (!Libros::findOne($l)) {
                                Yii::$app->session->setFlash('error', '¡No puedes borrar de tu lista un libro que no existe!');
                                return false;
                            }

                            if (!in_array(Libros::findOne($l), Yii::$app->user->identity->libros)) {
                                Yii::$app->session->setFlash('error', '¡No puedes borrar de tu lista un libro que no está en ella!');
                                return false;
                            }

                            return true;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['ordenar-lista-libros'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                Yii::$app->session->setFlash('error', '¡No puedes ordenar tu lista de libros sin iniciar sesión!');
                                return false;
                            }

                            return true;
                        },
                    ],
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
        $dataProvider = new ActiveDataProvider([
            'query' => Seleccion::find()
                ->where(['usuario_id' => Yii::$app->user->identity->id])
                ->orderBy('orden'),
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

        if (sizeof(Yii::$app->user->identity->libros) >= self::MAX_LIBROS) {
            return $this->devolverError('¡No puedes añadir más de ' . self::MAX_LIBROS . ' libros a tu lista!');
        }

        if (!Libros::findOne($l)) {
            return $this->devolverError('¡No puedes añadir a tu lista un libro que no existe!');
        }

        if (in_array(Libros::findOne($l), Yii::$app->user->identity->libros)) {
            return $this->devolverError( '¡No puedes añadir a tu lista un libro dos veces!');
        }

        $orden = sizeof(Yii::$app->user->identity->libros) + 1;
        $this->findModel(Yii::$app->user->identity->id)->link('libros', Libros::findOne($l), ['orden' => $orden]);
        $mensaje = '¡Se ha añadido el libro a tu lista correctamente!';
        $this->reordenarListaLibros(Yii::$app->user->identity->id);

        Yii::$app->session->setFlash('success', $mensaje);
        return $this->redirect(['mis-libros']);
    }

    /**
     * Función que un usuario usará para borrar un libro de su lista
     *
     * @param int $l    Id del libro a borrar
     * @return void
     */
    public function actionBorrarLibro($l)
    {
        $this->findModel(Yii::$app->user->identity->id)->unlink('libros', Libros::findOne($l), true);
        $mensaje = '¡Se ha borrado el libro de tu lista correctamente!';
        $this->reordenarListaLibros(Yii::$app->user->identity->id);
        if (Yii::$app->request->isAjax) {
            return Json::encode($mensaje);
        }
        Yii::$app->session->setFlash('success', $mensaje);
        return $this->redirect(['mis-libros']);
    }


    public function actionOrdenarListaLibros()
    {
        $post = Yii::$app->request->post();
        $uId = $post['uId'];
        $nO = $post['nO'];

        $propuestos = Seleccion::find()
        ->where(['usuario_id' => $uId])
        ->orderBy('orden')
        ->all();

        if (!$propuestos || (count($propuestos) != (count($nO)))) {
            Yii::debug('el usuario no tiene deseos o los arrays no coinciden');
            return false;
        }

        for ($i = 0; $i < count($propuestos); $i++) {
            for ($j = 0; $j < count($nO); $j++) {
                if ($propuestos[$i]->libro->id == $nO[$j]) {
                    $libroSel = $propuestos[$i];
                    $libroSel->orden = $j+1;
                    if (!$libroSel->save()) {
                        Yii::$app->session->setFlash('error', 'Ha ocurrido un error actualizando el orden de tu lista de libros');
                        return $this->redirect(['mis-libros']);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Función que solo se le llama desde borrar/añadir Libro, que corrije
     * el orden en la lista de libros por usuario
     *
     * @param int $uId
     * @return void
     */
    private function reordenarListaLibros($uId)
    {
        $listaLibros = Seleccion::find()->where(['usuario_id' => $uId])->orderBy('orden')->all();

        $i = 1;
        foreach ($listaLibros as $libro) {
            $libro->orden = $i;
            $i++;
            $libro->save();
        }
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

    /**
     * Funcion que devuelve un error en casos concretos
     *
     * @param String $mensaje
     * @return mixed
     */
    private function devolverError ($mensaje)
    {
        if (Yii::$app->request->isAjax) {
            return Json::encode($mensaje);
        }
        Yii::$app->session->setFlash('error', $mensaje);
        return $this->redirect(['mis-libros']);
    }
}
