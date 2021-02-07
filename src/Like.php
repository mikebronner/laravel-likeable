<?php

namespace Conner\Likeable;

use GeneaLabs\LaravelOverridableModel\Contracts\OverridableModel;
use GeneaLabs\LaravelOverridableModel\Traits\Overridable;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @mixin \Eloquent
 * @property Likeable likeable
 * @property string user_id
 * @property string likeable_id
 * @property string likeable_type
 */
class Like extends Eloquent implements OverridableModel
{
    use Overridable;

    protected $table = 'likeable_likes';
    public $timestamps = true;
    protected $fillable = ['likeable_id', 'likeable_type', 'user_id'];

    /**
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}
