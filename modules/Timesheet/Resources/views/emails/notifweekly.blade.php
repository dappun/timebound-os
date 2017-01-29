@extends('beautymail::templates.sunny')

@section('content')

    @include ('beautymail::templates.sunny.heading' , [
        'heading' => 'Weekly Summary',
        'level' => 'h1',
    ])

    @include('beautymail::templates.sunny.contentStart')

    <div style="padding: 10px;">
        <table>
            <thead>
            <tr>
                @foreach ($header as $name)
                    <td>{!! $name !!}</td>
                @endforeach
            </tr>
            </thead>

            <tbody>
                @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $content)
                    <td>{{ $content }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @include('beautymail::templates.sunny.contentEnd')


    <?php /*@include('beautymail::templates.sunny.button', [
            'title' => 'Click me',
            'link' => 'http://google.com'
    ])*/
    ?>

@stop