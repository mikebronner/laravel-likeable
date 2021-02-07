<?php

namespace Conner\Likeable;

use GeneaLabs\LaravelOverridableModel\Contracts\OverridableModel;
use GeneaLabs\LaravelOverridableModel\Traits\Overridable;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @mixin \Eloquent
 * @property Likeable likeable
 */
class LikeCounter extends Eloquent implements OverridableModel
{
    use Overridable;

    protected $table = 'likeable_like_counters';
    public $timestamps = false;
    protected $fillable = ['likeable_id', 'likeable_type', 'count'];

    /**
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Delete all counts of the given model, and recount them and insert new counts
     *
     * @param $modelClass
     */
    public static function rebuild($modelClass)
    {
        if (empty($modelClass)) {
            throw new \Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
        }

        $likeModel = Like::model();
        $builder = $likeModel::query()
            ->select(\DB::raw('count(*) as count, likeable_type, likeable_id'))
            ->where('likeable_type', $modelClass)
            ->groupBy('likeable_id');

        $results = $builder->get();

        $inserts = $results->toArray();

        \DB::table((new static)->table)->insert($inserts);
    }
}
