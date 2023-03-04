<?php

namespace app\controllers;

use app\controllers\BaseController;
use app\models\User;

class WorkerController extends BaseController
{
    public $modelClass = User::class;

}