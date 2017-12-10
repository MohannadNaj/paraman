<?php

namespace Paraman;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use ParametersModelTrait;

    protected $connection = 'parameters';
    protected $guarded = ['id'];
    protected $appends = ['humanizedCreatedAt', 'humanizedUpdatedAt'];
    protected $casts = ['meta'=>'array'];

    public function category()
    {
        return $this->belongsTo('Paraman\Parameter');
    }

    public function getHumanizedCreatedAtAttribute($value)
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getHumanizedUpdatedAtAttribute($value)
    {
        return Carbon::parse($this->updated_at)->diffForHumans();
    }

    public function getConnection()
    {
        return static::resolveConnection('parameters');
    }
}
