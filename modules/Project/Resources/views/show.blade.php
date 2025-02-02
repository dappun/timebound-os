@extends('core::layouts.master')

@section('content')
	<div class="row">
	    <div class="col-sm-6">
	    	<h1>{{ $project->name }}</h1>
	    	{!! $project->description !!}
	    </div>

	    <div class="col-sm-6">
	    	<!-- <div class="form-group pull-right">
	    		{!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'patch']) !!}
			    {!! Form::label('private', 'Project Privacy') !!}<br/>
			    {!! Form::checkbox('private') !!}
			    {!! Form::close() !!}
			</div> -->
	    </div>
    </div>

    <div class="row">
	    <div class="col-sm-4">
	    	<div class="tile">
		    	<div class="hl">{{ count($contributors) }}</div>
		    	<div class="sl">Contributors</div>
	    	</div>
	    </div>

	    <div class="col-sm-4">
	    	<div class="tile">
	    		<div class="hl">{{ $hours }}</div>
		    	<div class="sl">Hours Worked</div>
	    	</div>
	    </div>

	    <div class="col-sm-4">
	    	<div class="tile">
	    		<div class="hl">Info</div>
		    	<div class="sl">
	    		
		    		<ul>
		    			<li>Client: <a href="{!! route('clients.show', $project->client_id) !!}">{{ $project->clientName() }}</a></li>
		    			<li>Started: {{ date('Y-m-d', strtotime($project->created_at)) }}</li>
		    		</ul>

	    		</div>
		    	
	    	</div>
	    </div>
    </div>

    <!-- <div class="row">
    	{!! Form::model($project, ['route' => ['projects.update', $project->id], 'method' => 'patch']) !!}
    	<div class="col-sm-6">
    		
    		{!! Form::label('user_id', 'Add team') !!}<br/>
		    {!! Form::text('user_id', null, ['class' => 'form-control', 'placeholder' => 'Enter name or email']) !!}

		    
    	</div>
    	<div class="col-sm-3">
    		<label>&nbsp;</label><br/>
    		{!! Form::submit('Add', ['class' => 'btn btn-primary']) !!}
    	</div>
    	{!! Form::close() !!}
    </div> -->

    <div class="row">
    	<div class="col-sm-8">
    		<h1>Timesheet</h1>
    		<label>For the last 12 months</label>
    		<hr/>
    		@include('timesheet::_table')
    	</div>
    	<div class="col-sm-4">
    		<h1>Contributors</h1>
    		<label>For the last 12 months</label>
    		<hr/>
    		<canvas id="report_chart" style="width: 100%; height: 400px;"></canvas>
    	</div>
    </div>


@endsection


@section('scripts')
<script src="{{ asset('lib/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">
    // $("[name='private']").bootstrapSwitch();
</script>
<script type="text/javascript" src="{{ asset('dist/page_project_show.js') }}"></script>
<script type="text/javascript">
	var chartData = {!! json_encode($contributors) !!};
    project.initChart(chartData, 'report_chart');
</script>

@endsection

@section('style')
<link href="{{ asset('lib/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
@endsection
