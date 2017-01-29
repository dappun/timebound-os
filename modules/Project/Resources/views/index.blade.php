@extends('core::layouts.master')

@section('content')
        <h1 class="pull-left">Projects</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px" href="{!! route('projects.create') !!}">Add New</a>

        <div class="clearfix"></div>

        <hr/>
        @include('flash::message')

        <div class="clearfix"></div>

        @include('project::table')
        
@endsection
