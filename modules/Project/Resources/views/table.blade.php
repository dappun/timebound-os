<div class="view-filtered-js table-project">
    <div class="button-group filter-button-group">
      <button class="is-checked btn btn-link" data-filter=".active">Active</button>
      <button class="btn btn-link" data-filter=".notactive">Not Active</button>
    </div>


    <div class="grid">
        @foreach($projects as $project)
        <?php 
        $elemClasses = strtolower(str_replace(' ', '', $project->status));
        ?>
        <table class="table table-responsive element-item {!! $elemClasses !!}">
            <tr>
                <td>
                    <a href="{!! route('projects.show', [$project->id]) !!}" class=''>{!! $project->title !!}</a>
                </td>
                <td>
                    <a href="{!! route('clients.show', [$project->client_id]) !!}" class=''>{!! @$clients[$project->client_id] !!}</a>
                </td>
                <td>
                    @if ($project->private == 1)
                    <label class="label-danger label">{!! $project->privacy !!}</label>
                    @else
                    <label class="label-success label">{!! $project->privacy !!}</label>
                    @endif

                    @if ($project->status_id == 1)
                    <label class="label-success label">{!! $project->status !!}</label>
                    @else
                    <label class="label-danger label">{!! $project->status !!}</label>
                    @endif
                    
                </td>
                
                    <!-- {!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('projects.show', [$project->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('projects.edit', [$project->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!} -->
                
            </tr>
        </table>
        @endforeach
    </div>
</div>

<style type="text/css">
.element-item td {
    width: 25%;
}
</style>

@section('scripts')
<script src="{{ asset('lib/isotope.min.js') }}"></script>
<script type="text/javascript">
$(function() {
    var iso = new Isotope( '.grid', {
      itemSelector: '.element-item',
      layoutMode: 'fitRows',
      filter: '.active'
    });

    // filter functions
    var filterFns = {
      // show if number is greater than 50
      numberGreaterThan50: function( itemElem ) {
        var number = itemElem.querySelector('.number').textContent;
        return parseInt( number, 10 ) > 50;
      },
      // show if name ends with -ium
      ium: function( itemElem ) {
        var name = itemElem.querySelector('.name').textContent;
        return name.match( /ium$/ );
      }
    };

    var filtersElem = document.querySelector('.filter-button-group');
    filtersElem.addEventListener( 'click', function( event ) {
      // only work with buttons
      if ( !matchesSelector( event.target, 'button' ) ) {
        return;
      }

      var filterValue = event.target.getAttribute('data-filter');
      // use matching filter function
      filterValue = filterFns[ filterValue ] || filterValue;
      iso.arrange({ filter: filterValue });
    });

    // change is-checked class on buttons
    $('.button-group .btn').on('click', function(e) {
        $('.button-group .is-checked').removeClass('is-checked');
        $(this).addClass('is-checked');
    });

});    
</script>

<script src="{{ asset('lib/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
<script type="text/javascript">
    $("[name='private']").bootstrapSwitch();
</script>

@endsection

@section('style')
<link href="{{ asset('lib/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
@endsection