<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Auth;

class TestTaken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $parameters = $request->route()->parameters();

        $topic_id = $parameters['test'];

        $is_tested = Auth::user()->tests()->whereTopicId($topic_id)->first();

        if($is_tested)
            return redirect()
                ->route('tests.index')
                ->withErrorMessage('Error accessing test, you have already finish this topic.');

        return $next($request);
    }
}
