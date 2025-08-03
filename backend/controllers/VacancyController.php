<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use app\models\Vacancy;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class VacancyController extends ActiveController
{
    private const DEFAULT_PAGE_SIZE = 10;
    public $modelClass = 'app\models\Vacancy';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'index'  => ['GET'],
                    'view'   => ['GET'],
                ],
            ],
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();

        // Для того чтобы переопределить метод создания, чтобы возвращать ID созданной вакансии и код результата (ошибка или успех).
        unset($actions['create']);

        $actions['index']['prepareDataProvider'] = function () {
            return new ActiveDataProvider([
                'query' => Vacancy::find(),
                'pagination' => [
                    'pageSize' => (int) Yii::$app->request->get('per-page', self::DEFAULT_PAGE_SIZE),
                ],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                    ],
                ],
            ]);
        };

        return $actions;
    }

    public function actionCreate()
    {
        $model = new Vacancy();
        $model->load(Yii::$app->request->getBodyParams(), '');

        if ($model->save()) {
            Yii::$app->response->statusCode = 201;
            return ['id' => $model->id, 'result' => 'success'];
        }

        Yii::$app->response->statusCode = 422;
        return ['result' => 'error', 'errors' => $model->getErrors()];
    }
}
