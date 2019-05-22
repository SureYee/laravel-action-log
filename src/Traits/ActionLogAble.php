<?php

namespace Sureyee\ActionLog\Traits;

use Sureyee\ActionLog\Models\ActionLog;

trait ActionLogAble
{
    protected $excepts = ['updated_at', 'created_at'];

    public function actionLogs()
    {
        return $this->morphMany(ActionLog::class, 'model');
    }

    public function getExcepts()
    {
        return $this->excepts;
    }
}