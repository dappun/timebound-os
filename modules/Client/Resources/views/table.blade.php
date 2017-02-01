<div class="view-filtered-js table-div">
    <div class="button-group filter-button-group">
      <button class="is-checked btn btn-link" data-filter="active">Active</button>
      <button class="btn btn-link" data-filter="notactive">Not Active</button>
    </div>
    <div class="grid list">
        @foreach($clients as $customer)
        <?php 
        $status = strtolower(str_replace(' ', '', $customer->status));
        ?>

        <div class="element-item line">
            <div class="col-sm-6">
                <div class="name">
                    <a href="{!! route('clients.show', [$customer->id]) !!}" class=''>
                        {!! $customer->name !!}
                    </a>
                </div>

                <span class="status hide">{{ $status }}</span>
            </div>
            <div class="col-sm-3 client">
                {!! $customer->contact_person !!}
            </div>
            <div class="col-sm-3">
                @if ($customer->status_id == 1)
                <label class="label-success label status">{!! $customer->status !!}</label>
                @else
                <label class="label-danger label status">{!! $customer->status !!}</label>
                @endif
            </div>

            <!-- {!! Form::open(['route' => ['clients.destroy', $customer->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('clients.edit', [$customer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!} -->
        </div>
        @endforeach
    </div>
</div>


@section('scripts')
<script type="text/javascript" src="{{ asset('dist/page_client.js') }}"></script>
@endsection