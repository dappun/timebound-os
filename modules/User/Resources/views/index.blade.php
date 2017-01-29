@extends('core::layouts.master')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h1 class="pull-left">Team</h1>
        <a class="btn btn-primary pull-right" style="margin-top: 25px;" href="{!! route('admin.users.create') !!}">Add New</a>
    </div>
</div>

@include('flash::message')

<br/><br/>

<div class="row">
	<div class="col-sm-12">

        @include('user::table')

	</div>
</div>




@stop

