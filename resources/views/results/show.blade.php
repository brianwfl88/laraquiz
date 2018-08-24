@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.results.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.view-result')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        @if(Auth::user()->isAdmin())
                        <tr>
                            <th>@lang('quickadmin.results.fields.user')</th>
                            <td>{{ $test->user->name or '' }} ({{ $test->user->email or '' }})</td>
                        </tr>
                        @endif
                        <tr>
                            <th>@lang('quickadmin.results.fields.topic')</th>
                            <td>{{ $test->topic->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.results.fields.date')</th>
                            <td>{{ $test->created_at or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.results.fields.result')</th>
                            <td>
                                {{ $test->result_over }}

                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <?php $i = 1 ?>
                    @foreach($results as $result)
                        <table class="table table-bordered table-striped">
                            <tr class="test-option">
                                <th style="width: 10%">Question #{{ $i }}</th>
                                <th>{{ $result->question->question_text or '' }}</th>
                            </tr>
                            @if ($result->question->code_snippet != '')
                                <tr>
                                    <td>Code snippet</td>
                                    <td><div class="code_snippet">{!! $result->question->code_snippet !!}</div></td>
                                </tr>
                            @endif
                            <tr>
                                <td>Options</td>
                                <td>
                                    <ul>
                                    @foreach($result->question->options as $option)
                                        <li style="@if ($option->correct == 1) font-weight: bold; @endif
                                            @if ($result->option_id == $option->id) text-decoration: underline @endif">{{ $option->option }}
                                            @if ($option->correct == 1) <em>(correct answer)</em> @endif
                                            @if ($result->option_id == $option->id) <em>(your answer)</em> @endif
                                        </li>
                                    @endforeach
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td>Answer Explanation</td>
                                <td>
                                {!! $result->question->answer_explanation  !!}
                                    @if ($result->question->more_info_link != '')
                                        <br>
                                        <br>
                                        Read more:
                                        <a href="{{ $result->question->more_info_link }}" target="_blank">{{ $result->question->more_info_link }}</a>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    <?php $i++ ?>
                    @endforeach
                    
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('tests.index') }}" class="btn btn-default">Take another quiz</a>
            <a href="{{ route('results.index') }}" class="btn btn-default">See all my results</a>
        </div>
    </div>
    <style type="text/css">
    .progress {
        display: inline-block;
        width: 60%;
        max-width: 400px;
        vertical-align: middle;
        margin-bottom: 0;
        margin-left: 15px;
    }

    .progress-bar {
        color: #424242;
        transition: all .01s;
    }
    </style>
@endsection

@section('javascript')
<script type="text/javascript">
$(function(){

    var percentage = '{{ $test->result_percentage }}' * 1;
    var run = 0;

    var start_progress = function(){
        var bgColor = run < 40 ? '#ff2300' : run < 65 ? '#fdaa11' : run < 85 ? '#e7ea02' : '#00ce00';

        $('.progress-bar').attr({ 'aria-valuenow': run }).css({ 'width': run+'%', 'background-color': bgColor }).text(run + '%');

        if(percentage > 0)
        {
            setTimeout(start_progress, 30);
            percentage--;
            run++;
        }
    };

    start_progress();

});
</script>

@endsection
