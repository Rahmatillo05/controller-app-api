<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class InitController extends Controller
{
    public function actionIndex(): void
    {
        $env_example_path = Yii::$app->basePath . "/.env.example";
        $env_path = Yii::$app->basePath . "/.env";
        if(!file_exists($env_path)){
            copy($env_example_path, $env_path);
            $this->stdout("ENV file created! \n");
        } else{
            $this->stdout("ENV file already exist! \n");
        }
    }
}