<?php

namespace Sureyee\ActionLog\Models;


use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $table = 'action_logs';

    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * @param Model $model
     * @param Model $operator
     * @param string $type
     * @return $this
     */
    public static function log(Model $model, Model $operator, string $type)
    {
        $model->setVisible($model->getHidden());
        return static::create([
            'operate_type' => $type,
            'table_name' => $model->getTable(),
            'before_data' => $type === self::CREATE ? null : json_encode($model->getOriginal()),
            'after_data' => $type === self::DELETE ? null : $model->toJson(),
            'user_id' => $operator->primaryKey,
            'operated_at' => now()
        ]);
    }
}