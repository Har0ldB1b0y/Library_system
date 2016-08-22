@extends('layout')

@section('internal_stylesheet')
    <style type="text/css">
        .self-class {
            font-size: 12px;
            height:100%;
        }
        .search-panel {
            color: #3c763d;
        }
    </style>
@endsection()

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>BOOKS</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <a href="{{url('admin/materials')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Manage Materials</a>

                <a href="{{url('admin/books/create')}}" class="btn btn-success pull-right"><i class="fa fa-1x
                fa-plus-circle"></i> Add New Book</a>
            </div>
            <div class="col-sm-12 self-class">

                <hr>

                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Search Panel</h4>
                    <br>
                    {!! BootForm::open()->action('/admin/books/filter') !!}
                    {!! BootForm::bind($filters) !!}
                    {!! BootForm::text('Call Number', 'call_number') !!}
                    {!! BootForm::text('Card Number', 'card_number') !!}
                    {!! BootForm::text('Title', 'title') !!}
                    {!! BootForm::text('Publisher', 'publisher') !!}
                    {!! BootForm::text('Year Published', 'published_year') !!}
                    {!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'card_number' => 'Card Number', 'call_number' => 'Call Number', 'title' => 'Title', 'publisher' => 'Publisher', 'published_year' => 'Year Published']) !!}
                    {!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending']) !!}
                    {!! BootForm::submit('Search')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
                <br>

                <div class="col-sm-9">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr class="search-panel">
                                <th>Card Number</th>
                                <th>Call Number</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Subject</th>
                                <th>Publisher</th>
                                <th>Year Published</th>
                                <th></th>

                            </tr>
                            @forelse($books as $book)
                                <tr>
                                    <td>{{$book->card_number}}</td>
                                    <td>{{$book->call_number}}</td>
                                    <td>{{$book->title}}</td>
                                    <td>
                                    @foreach ($book->authors as $author)
                                        {{$author->name}} <br>
                                    @endforeach
                                    </td>
                                    <td>
                                    @foreach ($book->subjects as $subject)
                                        {{$subject->name}} <br>
                                    @endforeach
                                    </td>
                                    <td>{{$book->publisher}}</td>
                                    <td>{{$book->published_year}}</td>
                                    <td>
                                        <a href="{{url('admin/users/' . $book->id) . '/edit'}}" role="button" class="btn btn-success btn-xs">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="8" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                    {!! $books->render() !!}
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

@endsection