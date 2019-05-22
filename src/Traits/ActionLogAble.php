<?php

namespace Sureyee\ActionLog\Traits;


use Sureyee\ActionLog\Models\ActionLog;

trait ActionLogAble
{
    protected $excepts = ['updated_at', 'created_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany;
     */
    public function actionLogs()
    {
        return $this->morphMany(ActionLog::class, 'model');
    }

    /**
     * @return array
     */
    public function getExcepts()
    {
        return $this->excepts;
    }
}