@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => $title,
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <div>
        <p>Hi {!! $name !!},</p>

        @foreach ($paragraphs as $paragraph)
        <p>{!! $paragraph !!}</p>
        @endforeach
        

        <p>Best,<br/>
        {{ env('SITE_EMAIL_NAME', 'Your team') }}</p>

        <p><small>Why are we sending this? We take security very seriously and we want to keep you in the loop on important actions in your account.</small></p>
    </div>

    @include('beautymail::templates.sunny.contentEnd')

@stop