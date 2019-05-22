<?php

namespace Sureyee\ActionLog\Observers;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Sureyee\ActionLog\Models\ActionLog;

class ActionLogObserver
{

    /**
     * @param Model $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function created(Model $model)
    {
        $newData = $model->getAttributes();
        $oldData = null;

        $model->actionLogs()->save($this->makeInstance($oldData, $newData, ActionLog::CREATE));
    }

    /**
     * @param Model $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function updated(Model $model)
    {
        $watching = $this->watchingAttributes($model);

        $newData = Arr::only($model->getChanges(), $watching);

        if (!empty($newData)) {
            $oldData = Arr::only($model->getOriginal(), array_keys($newData));
            $model->actionLogs()->save($this->makeInstance($oldData, $newData, ActionLog::UPDATE));
        }
    }

    /**
     * @param Model $model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function deleted(Model $model)
    {
        $newData = null;
        $oldData = $model->getOriginal();

        $model->actionLogs()->save($this->makeInstance($oldData, $newData, ActionLog::DELETE));
    }

    /**
     * @param Model $model
     * @return array
     */
    protected function watchingAttributes(Model $model)
    {

        $excepts = $model->getExcepts();

        return array_diff(array_keys($model->getAttributes()), $excepts);
    }

    /**
     * @param $oldData
     * @param $newData
     * @param $type
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
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
