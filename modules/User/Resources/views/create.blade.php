@extends('core::layouts.master')

@section('content')
	
<div class="row">
    <div class="col-sm-12">
        <h1>New Account</h1>
    </div>
</div>

<hr/>

@include('flash::message')
@include('core::components/error')

{!! Form::open(['route' => 'admin.users.store']) !!}
<div class="row">
    <div class="form-group col-sm-12">
        <label>Account</label>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('name', 'Username:') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('email', 'Email:') !!}
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('password', 'Password:') !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('password_confirmation', 'Confirm Password:') !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
        <hr />
        <label>Bio</label>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('first_name', 'First Name:') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('last_name', 'Last Name:') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-12">
        <hr />
        <label>Permission</label>
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('status_id', 'Status:') !!}
            {!! Form::select('status_id', [0 => 'Inactive', 1 => 'Active'], null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group col-sm-6">
            {!! Form::label('role', 'Access:') !!}
            {!! Form::select('role', $roles, null, ['class' => 'form-control']) !!}
        </div>

         <!-- Submit Field -->
        <div class="form-group col-sm-12">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('admin.users.index') !!}" class="btn btn-default">Cancel</a>
        </div>
</div>

{!! Form::close() !!}


</div>
@stop