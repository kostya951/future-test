<?php

namespace app\controllers;

use app\models\TaskModel;
use Yii;

/**
 * Controller of getting tasks via REST
 */
class TaskController extends \yii\rest\Controller
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetAll(){
        $params =  $this->request->get();
        $model = TaskModel::find();

        if(isset($params['search'])){
            $model->where(['like','name', $params['search']]);
        }

        if(isset($params['sort'])){
            $model->orderBy($params['sort']);
        }

        return $model->all();
    }

    public function actionCreate(){
        $model = new TaskModel();
        $response = [];

        $model->attributes = Yii::$app->request->post();

        if($model->validate()){
            $model->save();
            $response['id'] = $model->id;
            $response['message'] = 'Task was created successfully';
        }else{
            $response['ok'] = false;
            $response['message'] = $model->errors;
        }

        return $response;
    }

    public function actionGet(int $id){
        $model = TaskModel::findOne(['id' => $id]);
        return isset($model) ? $model : [];
    }

    public function actionUpdate(int $id){
        $model = TaskModel::findOne(['id'=>$id]);
        $response = [];

        if(!isset($model)){
            $response = ['ok'=>'false','message'=>"task $id not found"];
            return $response;
        }

        $model->attributes = Yii::$app->request->bodyParams;
        if($model->validate()){
            $model->update(false);
            $response['message'] = 'Task updated successfully';
        }else{
            $response['ok'] = false;
            $response['message'] = $model->errors;
        }

        return $response;
    }

    public function actionDelete(int $id){
        $response = [];

        $model = TaskModel::findOne(['id' => $id]);

        if(!isset($model)){
            $response = ['ok'=>'false','message'=>"task $id not found"];
            return $response;
        }

        $model->delete();
        $response['message'] = 'Task deleted successfully';
        return $response;
    }

    public function behaviors()
    {
        /*
         * dismiss unnecessary components
         */
        $behaviours = parent::behaviors();
        unset($behaviours['authenticator']);
        unset($behaviours['rateLimiter']);
        return $behaviours;
    }
}