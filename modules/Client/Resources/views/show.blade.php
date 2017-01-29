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
	    	<div class="well">
	    	{{ count($contributors) }}
	    	<br/>
	    	Contributors

	    	</div>
	    </div>

	    <div class="col-sm-4">
	    	<div class="well">
	    	{{ $hours }}
	    	<br/>
	    	Hours Worked
	    	</div>
	    </div>

	    <div class="col-sm-4">
	    	<div class="well">
	    		<h4>Info</h4>
	    		
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
	    		
		    	<br/>
	    	</div>
	    </div>
    </div>

    <div class="row">
    	<div class="col-sm-8">
    		<h1>Contributors</h1>
    		<label>For the last 12 months</label>
    		<hr/>
    		<div id="piechart" style="width: 100%; height: 400px;"></div>
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
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable({!! json_encode($contributors) !!});

    var options = {
      title: '',
      pieHole: 0.4,
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
  }
</script>
@endsection
