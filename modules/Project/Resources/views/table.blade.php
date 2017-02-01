<div class="view-filtered-js table-div table-project">
    <div class="button-group filter-button-group">
      <button class="is-checked btn btn-link" data-filter="active">Active</button>
      <button class="btn btn-link" data-filter="notactive">Not Active</button>
    </div>
    <div class="grid list">
        @foreach($projects as $project)
        <?php 
        $status = strtolower(str_replace(' ', '', $project->status));
        ?>

        <div class="element-item line">
            <div class="col-sm-6">
                <div class="name">
                    <a href="{!! route('projects.show', [$project->id]) !!}" class=''>{!! $project->title !!}</a>
                </div>

                <span class="status hide">{{ $status }}</span>
            </div>
            <div class="col-sm-3 client">
                <a href="{!! route('clients.show', [$project->client_id]) !!}" class=''>{!! @$clients[$project->client_id] !!}</a>
            </div>
            <div class="col-sm-3">
                @if ($project->private == 1)
                <label class="label-danger label">{!! $project->privacy !!}</label>
                @else
                <label class="label-success label">{!! $project->privacy !!}</label>
                @endif

                @if ($project->status_id == 1)
                <label class="label-success label status">{!! $project->status !!}</label>
                @else
                <label class="label-danger label status">{!! $project->status !!}</label>
                @endif
            </div>

            <!-- {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('projects.show', [$project->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('projects.edit', [$project->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!} -->
        </div>
        @endforeach
    </div>
</div>

<style type="text/css">
.element-item td {
    width: 25%;
}
</style>

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_project.js') }}"></script>
<script src="{{ asset('lib/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">
    // $("[name='private']").bootstrapSwitch();
</script>

@endsection

@section('style')
<link href="{{ asset('lib/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
@endsection
