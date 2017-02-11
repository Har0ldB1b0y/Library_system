@extends('layout-clean')

@section('content')
    <h2 style="text-align: center">Attendance Report</h2>
    <div class="book-list" style="font-size: 10px;">
        <table class="table">
            <thead>
            <tr>
                <th>UserID</th>
                <th>Username</th>
                <th>Log In At</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($data as $value)
                <tr>
                    <td style="text-align:center;width: 220px">{{ $value->user->user_id }}</td>
                    <td style="text-align:center;width: 250px">{{ $value->user->name }}</td>
                    <td style="text-align:center;width: 220px">{{ $value->created_at }}</td>
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