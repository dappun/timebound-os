<div id="timeentry-form">
    <div class="row">
        <div class="form-group col-sm-6 col-lg-6">
            {!! Form::label('description', 'Describe your work:') !!}
            {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Describe your work']) !!}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-3 col-lg-3">
            <?php
            $start['date'] = date('Y-m-d');
            $start['time'] = date('H:00');
            if (isset($timesheet) && $timesheet) {
                $time = strtotime($timesheet->start);
                $start['date'] = date('Y-m-d', $time);
                $start['time'] = date('H:i', $time);
            }
            ?>
            <div id="picker-start" class="input-append date">
                {!! Form::label('start', 'Start:') !!}
                {!! Form::text('start_date', $start['date'], ['class' => 'form-control picker-start1']) !!}
                {!! Form::text('start_time', $start['time'], ['class' => 'form-control picker-start2']) !!}
            </div>
        </div>

        <div class="form-group col-sm-3 col-lg-3">
            <?php
            $end['date'] = date('Y-m-d');
            $end['time'] = date('H:00');
            if (isset($timesheet) && $timesheet->end) {
                $time = strtotime($timesheet->end);
                $end['date'] = date('Y-m-d', $time);
                $end['time'] = date('H:i', $time);
            }
            ?>
            
            <div id="picker-end" class="input-append date">
                {!! Form::label('end', 'End:') !!}
                {!! Form::text('end_date', $end['date'], ['class' => 'form-control picker-end1']) !!}
                {!! Form::text('end_time', $end['time'], ['class' => 'form-control picker-end2']) !!}
            </div>
        </div>

        <div class="form-group col-sm-12 col-lg-12">
        Total: <label class="timer-total">00:00</label>
        </div>
    </div>

    <div class="row">

        <div class="form-group col-sm-4 col-lg-2">
            {!! Form::label('project_id', 'Project:') !!}
            {!! Form::select('project_id', $projects, null, ['class' => 'form-control', 'id' => 'timer-project']) !!}
        </div>
        
        <div class="form-group col-sm-2 col-lg-2">
            {!! Form::label('ticket', 'Ticket:') !!}
            {!! Form::text('ticket', null, ['class' => 'form-control', 'placeholder' => 'Ticket Reference']) !!}
        </div>
    </div>

    <br/><br/>


    <div class="row">
        <div class="form-group col-sm-5">
            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            <a href="{!! route('timer.index') !!}" class="btn btn-default">Cancel</a>
        </div>
    </div>
</div>