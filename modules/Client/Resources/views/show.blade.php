@extends('core::layouts.master')

@section('content')
	<div class="row">
	    <div class="col-sm-6">
	    	<h1>{{ $client->name }}</h1>
	    	<label>{{ $client->description }}</label>
	    	<label>since {{ date('Y-m-d', strtotime($client->created_at)) }} </label>
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
		    			<li>Contact person: {{ $client->contact_perso }}</li>
		    			<li>Email: {{ $client->email }}</li>
		    			<li>Address: {{ $client->address }}</li>
		    			<li>Website: <a href="{{ $client->web }}">{{ $client->web }}</a></li>
		    			<li>Phone:
		    				@if ($client->phone1 && $client->phone2)
		    				<br/>{{ $client->phone1 }}
		    				<br/>{{ $client->phone2 }}
		    				@else
		    				{{ $client->phone1 }}
		    				{{ $client->phone2 }}
		    				@endif
		    			</li>
		    			
		    		</ul>
		    	</div>
	    	</div>
	    </div>
    </div>

    <div class="row">
    	<div class="col-sm-8">
    		<h1>Contributors</h1>
    		<label>For the last 12 months</label>
    		<hr/>
    		<canvas id="report_chart" style="width: 100%; height: 400px;"></canvas>
    	</div>
    	<div class="col-sm-4">
    		<h1>Projects</h1>
    		@if ($client->projects())
    			<ul>
    			@foreach($client->projects()->get() as $project)
    			<li><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a></li>
    			@endforeach()
    			</ul>
    		@endif
    	</div>
    </div>

@endsection


@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_project_show.js') }}"></script>
<script type="text/javascript">
	var chartData = {!! json_encode($contributors) !!};
    project.initChart(chartData, 'report_chart');
</script>
@endsection
