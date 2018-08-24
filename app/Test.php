<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Test
 *
 * @package App
 * @property string $title
*/
class Test extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'result', 'topic_id'];

    public static function boot()
    {
        parent::boot();

        Test::observe(new \App\Observers\UserActionsObserver);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function options()
    {
        return $this->hasManyThrough(QuestionsOption::class, TestAnswer::class, 'test_id', 'id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function getResultOverAttribute()
    {
        return $this->result .'/'. $this->topic->highest_points;
    }

    public function getResultPercentageAttribute()
    {
        return number_format($this->result / $this->topic->highest_points * 100, 0);
    }
}
