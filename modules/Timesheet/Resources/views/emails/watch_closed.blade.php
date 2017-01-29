@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $title,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <p>Exceeded 24 hours. Automatically closing the timesheets below.</p>
    
    <div style="padding: 10px;">
	    @if ($timesheets)
	    	<ul>
	    	@foreach ($timesheets as $line)
	    	<li>#{{ $line->id }}: {{ $line->description }} ({{ $line->duration }}) - <a href="{{ route('timer.edit', $line->id) }}">edit</a></li>
	    	@endforeach
	    	</ul>
	    @endif
    </div>

    @include('beautymail::templates.sunny.contentEnd')

@stop