<?php

namespace Parameter;

use Illuminate\Database\Eloquent\Model;
use Parameter\ParametersModelTrait;
use Carbon\Carbon;

class Parameter extends Model
{
    use ParametersModelTrait;

    protected $guarded = ['id'];
    protected $appends = ['humanizedCreatedAt','humanizedUpdatedAt'];
    protected $casts = ['meta'=>'array'];

    public function category()
    {
        return $this->belongsTo('Parameter\Parameter');
    }

    public function getHumanizedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getHumanizedUpdatedAtAttribute($value)
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }
}
