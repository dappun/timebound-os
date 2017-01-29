@extends('core::layouts.master')

@section('content')
    <div class="clearfix"></div>

    @include('flash::message')
    @include('core::components/error')

    <div class="clearfix"></div>

    <a href="{{ route('timer.create') }}" class="mini-link pull-right">Add Manually</a>
    <div id="timer">
    	{!! Form::open(['route' => 'timer.stopwatch', 'id' => 'timer-form']) !!}

		<div class="form-group col-sm-12">
			{!! Form::hidden('id', $timesheet->id, ['id' => 'timer-id']) !!}
            {!! Form::hidden('start', $timesheet->start, ['id' => 'timer-start']) !!}

		    {!! Form::text('description', $timesheet->description, ['placeholder' => 'What are you working on?', 'id' => 'timer-desc', 'tabindex' => 1]) !!}
		    {!! Form::select('project_id', $projectOptions, $timesheet->project_id, ['tabindex' => 2, 'id' => 'timer-project']) !!}
		    {!! Form::text('ticket', $timesheet->ticket, ['placeholder' => 'Ticket reference', 'tabindex' => 3, 'id' => 'timer-ticket']) !!}
		    <span class="counter">
		    	<span id="sw_h">00</span>:
		    	<span id="sw_m">00</span>:
			    <span id="sw_s">00</span>
			    <span id="sw_ms" class="hide">00</span>
		    </span>
		    @if ($timesheet->id)
		    {!! Form::submit('Stop', ['class' => 'btn btn-default btn-primary btn-danger', 'id' => 'timer-btn']) !!}
		    @else
		    {!! Form::submit('Start', ['class' => 'btn btn-default btn-primary', 'id' => 'timer-btn']) !!}
		    @endif
		</div>

    	{!! Form::close() !!}
    </div>

	<div class="clearfix"></div>

	<!-- Date Navigator -->
	<div id="date-navigator" v-cloak>
		<div class="date-nav-current">
			<button class="btn btn-sm" v-on:click="toggleNavigator">
				@{{ rangeLabel }}
			</button>
		</div>
		<div class="separator">&nbsp;</div>
		<div class="date-nav-pager">
			<button v-on:click="prevDateRange">
				<i class="fa fa-caret-left"></i>
			</button>
			<button v-on:click="nextDateRange">
				<i class="fa fa-caret-right"></i>
			</button>
		</div>
		<div class="clearfix"></div>		
		<div class="date-navigator-calendars"  v-show="isOpenCalendars">
			<div id="date-nav-range"></div>
			<div class="dates-selectable">
				<ul class="list-inline">
					<li v-for="date in datesSelectable">
						<button class="btn btn-default" v-bind:class="[ (currentRangeIndex - 1) == $index ? 'active' : '' ]" v-on:click="selectDateRange( $index + 1 )">
							@{{ date.name }}
						</button>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>

	<div id="timer-table">
		@include('timesheet::components.timer_table')
	</div>
    
	
@endsection

@section('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', {packages: ['corechart', 'bar']});
</script>

@if (!$timesheets->isEmpty())
<script type="text/javascript">
	
    google.charts.setOnLoadCallback(loadChart);

    function loadChart() 
    {
        var data = google.visualization.arrayToDataTable([
            ['Date', 'Hour'],
            <?php foreach ($summary as $date => $entry) {
                $hours = $entry['total'] / 60 / 60;
                $day = date('D, M d', strtotime($date));
                echo "['$day', $hours],";
            }
            ?>
        ]);

        drawMaterial(data);
    }

    function drawMaterial(data) 
    {
		var options = {
			title: 'This Week',
			hAxis: {
				title: 'Date'
			},
			vAxis: {
				title: 'Total Hours',
			}
		};

		var material = new google.charts.Bar(document.getElementById('chart_div'));
		material.draw(data, options);
    }
</script>
@endif

<script type="text/javascript">
    var urlDashboard = '{{ route('timer.index') }}';
</script>

<script type="text/javascript" src="{{ asset('/lib/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/notify.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/stopwatch.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/timeentry.index.js') }}"></script>

@endsection