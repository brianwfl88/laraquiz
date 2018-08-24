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
					Highest Score: {{ $topic->highest_points }}
				</div>
				<h4 style="width: 60%;">{{ $topic->title }}</h4>
			</div>
			<div class="panel-footer text-right">
				@if($topic->is_test_taken)
				<button class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top"  title="You already have taken the test">
					<i class="fa fa-ban"></i>
				</button>
				@else
				<a class="btn btn-sm btn-primary" href="{{ route('tests.show', $topic->id) }}">Start</a>
				@endif
			</div>
		</div>

	</div>
	@endforeach

</div>

@endsection

@section('javascript')
<script type="text/javascript">
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})
</script>
@endsection