<div class="view-filtered-js table-team">
    <div>
        
        <button class="sort btn-link btn pull-right" data-sort="name">Sort by name</button>
        <div class="button-group filter-button-group">
            <button class="is-checked btn btn-link" data-type="status" data-filter="active">Active</button>
            <button class="btn btn-link" data-type="status" data-filter="notactive">Not Active</button>

            <button class="btn btn-link" data-type="role" data-filter="admin">Administrators</button>
            <button class="btn btn-link" data-type="role" data-filter="manager">Project Managers</button>
            <button class="btn btn-link" data-type="role" data-filter="customer">Clients</button>
            <button class="btn btn-link" data-type="role" data-filter="member">Team Members</button>
        </div>

    </div>


    <div class="grid list">
        @foreach($users as $user)
            <?php 
            $status = strtolower(str_replace(' ', '', $user->status));
            ?>

            <div class="element-item col-sm-3">
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

                <span class="role hide">{{ $user->roles()->first()->name }}</span>
                <span class="status hide">{{ $status }}</span>
                

        </div>
        @endforeach
    </div>
</div>

@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_user.js') }}"></script>
@endsection