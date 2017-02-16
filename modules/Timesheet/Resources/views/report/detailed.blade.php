@extends('core::layouts.master')

@section('content')
    <div class="clearfix"></div>

    @include('flash::message')

    <h1>Summary Report</h1>

    @foreach ($url->slice(-3) as $filter)
      <ul class="list-inline pull-right">
        <li><a href="{{ $filter->url }}">{{  $filter->name }}</a></li>
      </ul>

    @endforeach

    @include('timesheet::report/_form', ['url' => 'report.detailed'])

    <div class="clearfix"></div>
    <div class="reports-actions">
    	<ul class="list-inline pull-right">
        <li><a href="#" data-toggle="modal" data-target="#myModal" id="bmodal">Save Filter</a><x`/li>
    	<li><a href="{{ route('report.reset', ['d' => 'detailed']) }}">Reset</a></li>
    	</ul>

    	<div class="clearfix"></div>
    </div>

    <hr class="" />

    @if ($timesheets->all())
    @if ($total)
    <div class="timer-total pull-left">Total: <span>{{ gmhours($total) }}</span></div>
    @endif
    <div class="actions">
    	<ul class="list-inline pull-right">
    		<li>
	    		<a href="{{ route('report.download', ['list'] + $urlQuery) }}">
	    			<i class="fa fa-download" aria-hidden="true"></i> CSV
	    		</a>
    		</li>
    	</ul>

    	<div class="clearfix"></div>
    </div>

    <canvas id="report_chart" style="width: 100%; height: 400px;"></canvas>

    <div class="clearfix"></div>

    <p>&nbsp;</p>

    @include('timesheet::_table')

    @else
    	<div class="well">No entries found. <a href="{{ route('report.reset', ['d' => 'detailed']) }}">Reset</a></div>
    @endif

 <!-- Modal -->
          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">  
                <div class="modal-header">
                  <a href="{{ $request->input('filter') }}"><button  type="button" class="close" id="clear" data-dismiss="modal" >&times;</button></a>
                  <h4 class="modal-title">Save Filter</h4>
                </div>
                 <form>
                <div class="modal-body">

                <div class="form-group">
    <!-- FORM POST HERE -->
    <!-- form for post -->    
                <input type="hidden" name="_token" id="sampletoken" value="{{ csrf_token() }}"> 
           {!! Form::open([ 'id' =>'regg']) !!}
              {!! Form::label('name', 'Filter name:') !!}
              {!! Form::text('name',null, ['form-control', 'id' => 'samplename']) !!}             
              {!! Form::button('Save', ['class' => 'btn btn-default btn-primary' , 'id' => 'butt']) !!}       
           {!! Form::close() !!}
    <!-- end form for post -->  
                </div>
                </div>  
              </div>
              </form>
            </div>
          </div>
  <!-- Modal -->
        
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_report.js') }}"></script>
<script type="text/javascript" src="{{ asset('lib/bootstrap/js/bootstrap.min.js')}}"></script>

<script type="text/javascript">
	var start = moment('<?php echo $request->input('start') ? $request->input('start') : date('Y-m-d', strtotime('monday this week')); ?>');
    var end = moment('<?php echo $request->input('end') ? $request->input('end') : date('Y-m-d'); ?>');
    var chartData = {!! json_encode($chartData) !!};

    report.initSearchForm(start, end, '#reportrange');
	report.initChart(chartData, 'report_chart');

</script>

<!-- ajax post -->
<script type="text/javascript">
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  }); 

  $(document).ready(function() {

    $('#clear').click(function(){
      $('#samplename').val("");
    });

    $('#butt').click(function(){
            var filter = document.getElementById('samplename').value;
            if (filter != ""){
       
              var name = $('#samplename').val();
              var user_id = $('#input_user_id').val();
              var ac_start = $('#d_start').val();
              var ac_end = $('#d_end').val();
              var u_id = $('#u_id').val();
              var p_id = $('#p_id').val();
              var c_id = $('#c_id').val();
              var _token = $('#sampletoken').val();
            
              var dataString = "name=" + name +
                               "&ac_start=" + ac_start + 
                               "&ac_end=" + ac_end + 
                               "&ac_user_id=" + user_id + 
                               "&p_id=" + p_id + 
                               "&u_id=" + u_id + 
                               "&c_id=" + c_id +
                               "&_token=" + _token;
              $.ajax({
                type:"POST",
                url: "report/save",
                data: dataString,
                success: function(data){
                  console.log(data);
                }
              });   
              alert ('Filter saved!');  
              location.reload();

        } else {
           alert ('Please enter the name of the filter');
        }
        }); 
});
</script>
<!-- end for ajx post -->

@endsection