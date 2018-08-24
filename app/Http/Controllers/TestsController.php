<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Test;
use App\TestAnswer;
use App\Topic;
use App\Question;
use App\QuestionsOption;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestRequest;

use App\Rules\Recaptcha;

class TestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('test_taken')->except('index');
    }
    /**
     * Display a new test.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::with('questions', 'options')->has('questions')
            ->has('options')
          ->get();

        return view('tests.index', compact('topics'));
    }

    public function show($id)
    {
        // $topics = Topic::inRandomOrder()->limit(10)->get();

        $questions = Question::with('options')->where('topic_id', $id)->get();

        foreach ($questions as &$question) {
            $question->options = QuestionsOption::where('question_id', $question->id)->inRandomOrder()->get();
        }

        /*
        foreach ($topics as $topic) {
            if ($topic->questions->count()) {
                $questions[$topic->id]['topic'] = $topic->title;
                $questions[$topic->id]['questions'] = $topic->questions()->inRandomOrder()->first()->load('options')->toArray();
                shuffle($questions[$topic->id]['questions']['options']);
            }
        }
        */

        return count($questions) ? view('tests.create', compact('questions')) : redirect()->route('tests.index');
    }

    /**
     * Store a newly solved Test in storage with results.
     *
     * @param  \App\Http\Requests\StoreResultsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => ['required', new Recaptcha]
        ], ['g-recaptcha-response.required' => 'Please tick the Recaptcha']);

        $result = 0;
        $topic_id = 0;

        $test = Test::create([
            'user_id' => Auth::id(),
            'result'  => $result,
        ]);

        foreach ($request->input('questions', []) as $key => $question) {
            $question_option = QuestionsOption::with('question.topic')->find($request->input('answers.' . $question));
            $points = $question_option->points;
            $topic_id = $question_option->question->topic->id;

            $result += $points;

            TestAnswer::create([
                'user_id'     => Auth::id(),
                'test_id'     => $test->id,
                'question_id' => $question,
                'option_id'   => $request->input('answers.'.$question),
                'points'     => $points
            ]);
        }

        $test->update(['result' => $result, 'topic_id' => $topic_id]);

        return redirect()->route('results.show', [$test->id])->withMessage(trans('quickadmin.quiz_completed'));
    }
}
