<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\BaseModel
 *
 * @method static Builder where($value)
 * @mixin \Illuminate\Database\Eloquent
 */
class BaseModel extends Model
{
    /** @var array */
    protected $guarded = ['id'];

    /** @inheritDoc */
    public static function boot()
    {
        parent::boot();

        if (!null !== auth()->check()) {
            return;
        }

        $user_id = auth()->id();

        //created_by & updated_by
        static::creating(static function ($model) use ($user_id) {
//            $model->created_by = $user_id;
//            $model->updated_by = $user_id;
        });

        static::updating(static function ($model) use ($user_id) {
//            $model->updated_by = $user_id;
        });
    }
}
