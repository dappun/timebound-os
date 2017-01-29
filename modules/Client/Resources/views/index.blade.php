@extends('core::layouts.master')

@section('content')
        <h1 class="pull-left">Clients</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('clients.create') !!}">Add New</a>

        <div class="clearfix"></div>

        <hr/>

    	@include('flash::message')

        <div class="clearfix"></div>

        @if ($clients)
        @include('client::table')
        @endif
        
@endsection
