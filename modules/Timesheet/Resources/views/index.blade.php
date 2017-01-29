@extends('core::layouts.master')

@section('content')
    @include('flash::message')
    @include('core::components/error')

    <div class="clearfix"></div>

    <a href="{{ route('timer.create') }}" class="mini-link pull-right">Add Manually</a>

    <div id="timer" v-cloak>
        {!! Form::open(['route' => 'api.timer.stopwatch', 'id' => 'timer-form', 
            'v-on:submit.prevent' => 'onTimerSubmit']) !!}

        <div class="form-group col-sm-12">
            {!! Form::hidden('id', null, ['id' => 'timer-id', 'v-model' => 'ts.id']) !!}
            {!! Form::hidden('project_id', null, ['v-model' => 'ts.project_id']) !!}
            
            <div class="row">
                <div class="col-sm-5 col-timer col-desc">
                {!! Form::text('description', null, [
                    'placeholder' => 'What are you working on?', 
                    'id' => 'timer-desc', 
                    'tabindex' => 1, 
                    'v-model' => 'ts.description',
                    'v-validate data-vv-rules' => 'required']
                    ) !!}
                    <span class="has-error" v-show="errors.has('description')">@{{ errors.first('description') }}</span>
                </div>
                <div class="col-sm-3 col-timer col-project">
                    <select2 :options="ts.projects" v-model="ts.project_id" tabindex=2>
                      <!-- <option disabled value="0">Select one</option> -->
                    </select2>
                </div>
                <div class="col-sm-1 col-timer col-ticket">
                {!! Form::text('ticket', null, [
                    'placeholder' => 'Ticket reference', 
                    'tabindex' => 3, 
                    'id' => 'timer-ticket',
                    'v-model' => 'ts.ticket'
                    ]) !!}
                </div>
                <div class="col-sm-2 col-timer col-sw">
                <span class="counter">
                    <span id="sw_h">@{{ sw.h }}</span>:
                    <span id="sw_m">@{{ sw.m }}</span>:
                    <span id="sw_s">@{{ sw.s }}</span>
                    <span id="sw_ms" class="hide"></span>
                </span>
                </div>
                <div class="col-sm-1 col-timer col-btn">
                <input class="btn btn-default btn-primary" id="timer-btn" v-bind:class="{ 'btn-danger': btn.active }" type="submit" v-model="btn.text">
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>

    <div id="history" v-cloak>
        <div class="timer-total">Total: <span>@{{ total.display }}</span></div>
        <div id="history-list" v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" infinite-scroll-distance="10">
            @include('timesheet::components/history')
            <div class="loader-image" v-bind:class="{ 'hide': !loader_show }">
                <div class="spinner">
                  <div class="rect1"></div>
                  <div class="rect2"></div>
                  <div class="rect3"></div>
                  <div class="rect4"></div>
                  <div class="rect5"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

<script type="text/x-template" id="select2-template">
    <select>
        <slot></slot>
    </select>
</script>

<script type="text/javascript" src="{{ asset('dist/page_timer.js') }}"></script>
<script type="text/javascript">
appTimer.ts.projects = {!! json_encode($projectOptions) !!};
</script>

@endsection