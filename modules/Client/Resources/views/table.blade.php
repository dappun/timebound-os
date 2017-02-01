<table class="table table-responsive" id="clients-table">
    <thead>
        <th>Name</th>
        <th>Contact</th>
        <th>Status</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($clients as $customer)
        <tr>
            <td>
                <a href="{!! route('clients.show', [$customer->id]) !!}" class=''>
                    {!! $customer->name !!}
                </a>
            </td>
            <td>{!! $customer->contact_person !!}</td>
            <td>{!! $customer->status !!}</td>
            <td>
                {!! Form::open(['route' => ['clients.destroy', $customer->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('clients.edit', [$customer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
