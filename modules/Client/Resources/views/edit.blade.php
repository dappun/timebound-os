@extends('core::layouts.master')

@section('content')
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Edit Customer</h1>
            </div>
        </div>

        <hr/>

        @include('flash::message')
        @include('core::components/error')

        <div class="row">
            {!! Form::model($client, ['route' => ['clients.update', $client->id], 'method' => 'patch']) !!}

            @include('client::fields')

            {!! Form::close() !!}
        </div>
@endsection
