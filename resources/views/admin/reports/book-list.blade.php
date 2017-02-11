@extends('layout-clean')

@section('content')
    <h2 style="text-align: center">Book List</h2>
    <div class="book-list" style="font-size: 10px;">
        <table class="table">
            <thead>
            <tr>
                <th>Barcode</th>
                <th>Card Number</th>
                <th>Call Number</th>
                {{--<th>Title</th>--}}
                {{--<th>Author</th>--}}
                {{--<th>Subject</th>--}}
                <th>Publisher</th>
                <th>Year Published</th>
            </tr>
            </thead>
            <tbody>
            @foreach($books as $book)
                <tr style="vertical-align: top">
                    <td style="width: 144px;">
                        <img style="text-align: left" src="data:image/png;base64,{!! DNS1D::getBarcodePNG($book->barcode, 'C39', 0.75, 33) !!}" />
                        <div style="width: 144px; text-align: center">{{$book->barcode}}</div>
                        {{--<div class="barcode" style="height:60px">{{$book->barcode}}</div>--}}
                    </td>
                    <td style="text-align:center;width: 100px">{{$book->card_number}}</td>
                    <td style="text-align:center;width: 130px;">{{$book->call_number}}</td>
                    {{--<td>{{$book->title}}</td>--}}
                    {{--<td>--}}
                        {{--@foreach ($book->authors as $author)--}}
                            {{--{{$author->name}} <br>--}}
                        {{--@endforeach--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--@foreach ($book->subjects as $subject)--}}
                            {{--{{$subject->name}} <br>--}}
                        {{--@endforeach--}}
                    {{--</td>--}}
                    <td style="text-align:center;width: 90px;">{{$book->publisher}}</td>
                    <td style="text-align:center;width: 100px;">{{$book->published_year}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection

@section('page_js')
    <script src="/js/jquery-barcode.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".barcode").each(function (i, e) {
                $(e).barcode($(e).text(), "code128", { showHRI: false} );
            });
        });
    </script>
@stop