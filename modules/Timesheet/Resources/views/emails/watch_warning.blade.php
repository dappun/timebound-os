@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $title,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <p>You have running timesheets that exceeded the threshold. Timesheets are automatically closed after 24 hours.</p>
    
    <div style="padding: 10px;">
	    @if ($timesheets)
	    	<ul>
	    	@foreach ($timesheets as $line)
            <?php $duration = gmhours(computeDuration($line->start, date('Y-m-d H:i:s'))); ?>
	    	<li>#{{ $line->id }}: {{ $line->description }} ({{ $duration }}) - <a href="{{ route('timer.edit', $line->id) }}">edit</a></li>
	    	@endforeach
	    	</ul>
	    @endif
    </div>

    @include('beautymail::templates.sunny.contentEnd')

@stop