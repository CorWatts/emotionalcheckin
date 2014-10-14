<?php

namespace site\controllers;

use Yii;
use common\models\Category;
use common\models\Option;
use common\models\UserOption;
use site\models\CheckinForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\helpers\VarDumper;

class CheckinController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $form = new CheckinForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $options = array_merge((array)$form->options1, (array)$form->options2, (array)$form->options3, (array)$form->options4, (array)$form->options5, (array)$form->options6, (array)$form->options7);
            $options = array_filter($options);

            // delete the old data, we only store one data set per day
            if(sizeof($options) > 0)
                UserOption::deleteAll('user_id=:user_id AND date(date)=:date', [':user_id' => Yii::$app->user->id, ':date' => date('Y-m-d H:i:s')]);

            foreach($options as $option_id) {
                $user_option = new UserOption;
                $user_option->option_id = $option_id;
                $user_option->user_id = Yii::$app->user->id;
                $user_option->date = date('Y-m-d H:i:s');
                $user_option->save();
            }
            Yii::$app->session->setFlash('success', 'Your emotions have been logged!');
            return $this->redirect(['checkin/view'], 200);
        } else {
            $categories = Category::find()->asArray()->all();
            $options = Option::find()->asArray()->all();
            $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");
            return $this->render('index', ['categories' => $categories, 'model' => $form, 'optionsList' => $optionsList]);
        }
    }

    public function actionView($date = null)
    {
        if(is_null($date))
            $date = date("Y-m-d");

        $form = new CheckinForm();

        $past_checkin_dates = UserOption::getPastCheckinDates();
        $user_options = UserOption::find()->where(["user_id" => Yii::$app->user->id, 'date(date)' => $date])->with('option')->asArray()->all();
        foreach($user_options as $option) {                                                                                                                         
                $user_options_by_category[$option['option']['category_id']][] = $option['option_id'];
                $attribute = "options".$option['option']['category_id'];
                $form->{$attribute}[] = $option['option_id'];
            }   


        $categories = Category::find()->asArray()->all();

        $options = Option::find()->asArray()->all();
        $optionsList = \yii\helpers\ArrayHelper::map($options, "id", "name", "category_id");

        $score = UserOption::calculateScoreByDate($date);

        return $this->render('view', ['model' => $form, 'categories' => $categories, 'optionsList' => $optionsList, 'date' => $date, 'score' => $score, 'past_checkin_dates' => $past_checkin_dates]);
    }

    public function actionReport() {
        $query = new Query;
        $query->params = [":user_id" => Yii::$app->user->id];
        $query->select("o.id as id, o.name as name, COUNT(o.id) as count")
            ->from('user_option_link l')
            ->join("INNER JOIN", "option o", "l.option_id = o.id")
            ->groupBy('o.id, l.user_id')
            ->having('l.user_id = :user_id')
            ->orderBy('count DESC')
            ->limit(5);
        $user_rows = $query->all();

        $query2 = new Query;
        $query2->params = [":user_id" => Yii::$app->user->id];
        $query2->select("c.name as name, COUNT(o.id) as count")
            ->from('user_option_link l')
            ->join("INNER JOIN", "option o", "l.option_id = o.id")
            ->join("INNER JOIN", "category c", "o.category_id = c.id")
            ->groupBy('c.id, c.name, l.user_id')
            ->having('l.user_id = :user_id');
        $answer_pie = $query2->all();

       $scores = UserOption::calculateScoresOfLastMonth();

        return $this->render('report', ['top_options' => $user_rows, 'answer_pie' => $answer_pie, 'scores' => $scores]);
    }
}
