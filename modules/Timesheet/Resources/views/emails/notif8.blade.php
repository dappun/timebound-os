@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'Overtime Notifications',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <div style="padding: 10px;">
	    @if ($rows)
	    	<ul>
	    	@foreach ($rows as $line)
	    	<li>{!! $line !!}</li>
	    	@endforeach
	    	</ul>
	    @endif
    </div>

    @include('beautymail::templates.sunny.contentEnd')


    <?php /*@include('beautymail::templates.sunny.button', [
            'title' => 'Click me',
            'link' => 'http://google.com'
    ])*/
    ?>

@stop