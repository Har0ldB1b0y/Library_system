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
                    {!! BootForm::select('Filter By', 'filter_by', ['' => '-- FILTER SEARCH --', 'books.title' => 'Book Title', 'authors.name' => 'Author', 'subjects.name' => 'Subject'])->addClass('excludeSelect') !!}
                    {!! BootForm::text('Search', 'search') !!}
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