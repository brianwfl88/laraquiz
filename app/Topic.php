<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Topic
 *
 * @package App
 * @property string $title
*/
class Topic extends Model
{
    use SoftDeletes;

    protected $fillable = ['title'];

    public static function boot()
    {
        parent::boot();

        Topic::observe(new \App\Observers\UserActionsObserver);
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'topic_id')->withTrashed();
    }

    public function options()
    {
        return $this->hasManyThrough(QuestionsOption::class, Question::class);
    }

    public function test()
    {
        return $this->hasOne(Test::class);
    }

    public function getHighestPointsAttribute()
    {
        return $this->questions->sum('max_point');
    }

    public function getTotalQuestionAttribute()
    {
        return $this->questions()->count();
    }

    public function getIsTestTakenAttribute()
    {
        return $this->test && $this->test->is_taken;
    }
}
