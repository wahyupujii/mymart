<?php 
    namespace frontend\components;
    use yii;
    use yii\base\Component;
    use frontend\models\Statistic;

    class MyComponent extends Component {
        const EVENT_AFTER_SOMETHING = 'after-something';
        public function addStatistic() {
            $param = Yii::$app->request;
            $statis = new Statistic();
            $statis->access_time = date ('Y-m-d H:i:s');
            $statis->user_ip = $param->userIP;
            $statis->user_host = $param->hostInfo;
            $statis->path_info = $param->pathInfo;
            $statis->query_string = $param->queryString;
            $statis->save();
        }
    }
?>