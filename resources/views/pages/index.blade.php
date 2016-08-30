@extends('layout')

@section('content')
<div id="content-header" style="border-bottom: 0px;">
    <div class="header-bg">
        <div class="container">
            <div class="col-lg-12">
                <h4>OPAC - Web Online Public Access Catalog</h4>
            </div>
        </div>
    </div>
</div>
<div class="container self-class">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel" style="border-width: 2px;border-color:#5cb85c">
                <div class="panel-body">
                    {!! BootForm::openHorizontal(['sm' => [1,11]])->addClass('search-panel') !!}
                    {!! BootForm::bind($request) !!}
                    {!! BootForm::select('Filter By', 'filter_by', ['books.title' => 'Book Title', 'authors.name' => 'Author', 'subjects.name' => 'Subject'])->style('width:100%') !!}
                    {!! BootForm::select('Search', 'search')->style('width:100%') !!}
                    {!! BootForm::close() !!}

                </div>
            </div>
            <br>
            @if (isset($data))

            <div class="table-responsive">

                <table class="table table-hover table-bordered table-condensed">
                    <thead>
                    <tr class="search-panel">
                        <th>Card Number</th>
                        <th>Call Number</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Subject</th>
                        <th>Publisher</th>
                        <th>Date Published</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($data as $value)
                        <tr>
                            <td>{{$value->card_number}}</td>
                            <td>{{$value->call_number}}</td>
                            <td>{{$value->title}}</td>
                            <td>
                            @foreach ($value->authors as $author)
                                {{$author->name}} <br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($value->subjects as $subject)
                                    {{$subject->name}} <br>
                                @endforeach
                            </td>
                            <td>{{$value->publisher}}</td>
                            <td>{{$value->published_year}}</td>
                            <td>
                                <a href="{{url('admin/books/' . $value->id)}}" role="button" class="btn btn-success btn-xs">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="8" class="text-danger">No records found</th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-center">

            {!! $data->render() !!}
            </div>
            @endif
        </div>

    </div>

</div>
@stop

@section('page_js')
    <script type="text/javascript">
        $(function() {
            $('#search').on('select2:select', function() {
                $(this).closest('form').submit();
            });

            function search(url) {
                $("#search").select2({
                    allowClear: true,
                    minimumInputLength: 3,
                    ajax: {
                        url: url,
                        data: function (params) {
                            return {
                                q: params.term,
                                page: params.page
                            };
                        }
                    }
                });
            }
            var filter_by = $("#filter_by");


            var search_url = filter_by.val().substring(0, filter_by.val().indexOf("."));

            search("/" + search_url + "/search");

            filter_by.on('change', function() {
                if (filter_by.val() == 'books.title') {
                    search('/books/search');

                } else if (filter_by.val() == 'authors.name') {
                    search('/authors/search');

                } else if (filter_by.val() == 'subjects.name') {
                    search('/subjects/search');
                }
            });

        });
    </script>
@endsection