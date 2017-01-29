<div class="view-filtered-js table-team">
    <div class="button-group filter-button-group">
        <button class="is-checked btn btn-link" data-filter=".active">Active</button>
        <button class="btn btn-link" data-filter=".notactive">Not Active</button>

        <button class="btn btn-link" data-filter=".admin">Administrators</button>
        <button class="btn btn-link" data-filter=".manager">Project Managers</button>
        <button class="btn btn-link" data-filter=".customer">Clients</button>
        <button class="btn btn-link" data-filter=".member">Team Members</button>
    </div>


    <div class="grid">
        @foreach($users as $user)
            <?php 
            $elemClasses = strtolower(str_replace(' ', '', $user->status));
            
            if ($user->roles()->count()) {
                $elemClasses .= ' ' . $user->roles()->first()->name;
            }
            ?>

            <div class="element-item {!! $elemClasses !!} col-sm-3">

                <div class="btn-edit">
                <a href="{!! route('admin.users.edit', [$user->id]) !!}" class='btn btn-primary btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i> edit</a>
                </div>

                {!! Form::open(['route' => ['admin.users.destroy', $user->id], 'method' => 'delete', 'class' => 'btn-del']) !!}
                <div class='btn-group action'>
                    <!-- <a href="{!! route('admin.users.show', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> -->
                    {!! Form::button('<i class="glyphicon glyphicon-remove"></i>', ['type' => 'submit', 'class' => 'btn btn-link btn-danger btn-xs', 'onclick' => "return confirm('Are you sure you want to delete {$user->name}? This action will be permanent.')"]) !!}
                </div>
                {!! Form::close() !!}

                <img src="{!! url($user->profile_image) !!}" alt="">
                <br/>
        
                <div class="name">
                    {!! $user->last_name . ', ' . $user->first_name !!}<br/>
                    <label>{!! $user->name !!}</label>
                </div>
                

        </div>
        @endforeach
    </div>
</div>

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
@endsection