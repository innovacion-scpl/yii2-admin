<?php

namespace mdm\admin\components;

use Yii;
use mdm\admin\models\LogAsignacionesPermiso;
use yii\db\Expression;
use yii\rbac\Item;
use yii\web\Controller;
use yii\filters\VerbFilter;
use mdm\admin\models\AuthItem;
use mdm\admin\models\Assignment;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;
use yii\web\ForbiddenHttpException;
use mdm\admin\models\searchs\AuthItem as AuthItemSearch;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @property integer $type
 * @property array $labels
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ItemController extends Controller
{
    public $userClassName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ? : 'mdm\admin\models\User';
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $esSuperUsuario = $this->findAssignmentModel(Yii::$app->user->id)->es_super_usuario;
        $searchModel = new AuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $esSuperUsuario);       
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param  string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        /**
         * Si el usuario es superusuario tiene permitido
         * el acceso a todo. En caso contrario, se verifica la regla 
         * y también se limita el acceso al rol "AdministradorDeUsuarios"
         */
        if($model->verificarAcceso())
        {
            return $this->render('view', ['model' => $model]);
        }else{
            throw new ForbiddenHttpException('No tiene permiso para ejecutar esta acción');
        }
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null);
        $model->type = $this->type;
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->verificarAcceso())
        {
            if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->name]);
            }
            return $this->render('update', ['model' => $model]);
        }else{
            throw new ForbiddenHttpException('No tiene permiso para ejecutar esta acción');
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->verificarAcceso())
        {
            Configs::authManager()->remove($model->item);
            Helper::invalidate();
            return $this->redirect(['index']);
        }else{
            throw new ForbiddenHttpException('No tiene permiso para ejecutar esta acción'); 
        }
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->addChildren($items);

        if($success){
            foreach ($items as $item) {   
                $logasignacion = new LogAsignacionesPermiso();
                $logasignacion->usuario_accion = Yii::$app->user->identity->id;
                $logasignacion->item_name_accion_permiso = $item;
                $logasignacion->item_name_modificado = $id;
                $logasignacion->fecha_hora = new Expression("NOW()");
                $logasignacion->tipo_accion_permiso = "asignado";
                $logasignacion->save();
            }
        }  

        Yii::$app->getResponse()->format = 'json';

        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionGetUsers($id)
    {
        $page = Yii::$app->getRequest()->get('page', 0);
        $model = $this->findModel($id);
        Yii::$app->getResponse()->format = 'json';

        return array_merge($model->getUsers($page));
    }

    /**
     * Assign or remove items
     * @param string $id
     * @return array
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->removeChildren($items);
        if($success){
            foreach ($items as $item) {   
                $logasignacion = new LogAsignacionesPermiso();
                $logasignacion->item_name_modificado = $id;
                $logasignacion->usuario_accion = Yii::$app->user->identity->id;
                $logasignacion->item_name_accion_permiso = $item;
                $logasignacion->fecha_hora = new Expression("NOW()");
                $logasignacion->tipo_accion_permiso = "desasignado";
                $logasignacion->save();
            }
        }  
        Yii::$app->getResponse()->format = 'json';
        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'item';
    }

    /**
     * Label use in view
     * @throws NotSupportedException
     */
    public function labels()
    {
        throw new NotSupportedException(get_class($this) . ' does not support labels().');
    }

    /**
     * Type of Auth Item.
     * @return integer
     */
    public function getType()
    {
        
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $auth = Configs::authManager();
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Assignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findAssignmentModel($id)
    {
        $class = $this->userClassName;
        if (($user = $class::findIdentity($id)) !== null) {
            return new Assignment($id, $user);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
