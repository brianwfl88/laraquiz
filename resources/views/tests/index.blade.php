@extends('layouts.app')

@section('content')

<h3 class="page-title">@lang('quickadmin.select_topic')</h3>

<div class="row">

	@foreach($topics as $topic)
	<div class="col-md-3">
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<div class="pull-right text-right">
					Questions: {{ $topic->total_question }}<br />
					Total Score: {{ $topic->max_points }}
				</div>
				<h4>{{ $topic->title }}</h4>
			</div>
			<div class="panel-footer text-right">
				<a class="btn btn-sm btn-primary" href="{{ route('tests.show', $topic->id) }}">Start</a>
			</div>
		</div>

	</div>
	@endforeach

</div>

@endsection