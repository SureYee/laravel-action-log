<?php

namespace Sureyee\ActionLog\Observers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Sureyee\ActionLog\Models\ActionLog;

class ActionLogObserver
{

    public function created(Model $model)
    {
        $watching = $this->watchingAttributes($model);

        $newData = Arr::only($model->getChanges(), $watching);
        $oldData = null;

        $model->actionLogs()->save($this->makeInstance($oldData, $newData, ActionLog::CREATE));
    }

    public function updated(Model $model)
    {
        $watching = $this->watchingAttributes($model);

        $newData = Arr::only($model->getChanges(), $watching);
        $oldData = Arr::only($model->getOriginal(), $watching);

        $model->actionLogs()->save($this->makeInstance($oldData, $newData, ActionLog::UPDATE));
    }

    public function deleted(Model $model)
    {
        $watching = $this->watchingAttributes($model);

        $newData = null;
        $oldData = Arr::only($model->getOriginal(), $watching);

        $model->actionLogs()->save($this->makeInstance($oldData, $newData, ActionLog::DELETE));
    }

    protected function watchingAttributes(Model $model)
    {

        $excepts = $model->getExcepts();

        return Arr::except(array_keys($model->getAttributes()), $excepts);
    }

    protected function makeInstance($oldData, $newData, $type)
    {
        $actionLog = app()->make(ActionLog::class);

        $actionLog->user_id = optional(Request::user())->id;
        $actionLog->old_data = json_encode($oldData);
        $actionLog->new_data = json_encode($newData);
        $actionLog->type = $type;
        $actionLog->ip = Request::ip();
        $actionLog->agent = Request::userAgent();
        $actionLog->url = Request::url();

        return $actionLog;
    }
}