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
                    {!! BootForm::select('Filter By', 'filter_by', ['' => '-- FILTER SEARCH --', 'keyword' => 'Keyword', 'name' => 'Title', 'author' => 'Author', 'subject' => 'Subject'])->addClass('excludeSelect') !!}
                    {!! BootForm::text('Search', 'search') !!}
                    {{--<div class="form-group search-panel">--}}
                        {{--<label class="control-label col-sm-2">Search: </label>--}}

                        {{--<div class="col-sm-6">--}}
                            {{--<div class="input-group input-group-sm">--}}
                                {{--<input type="text" class="form-control" aria-describedby="sizing-addon1">--}}
                                {{--<span class="input-group-addon" id="sizing-addon1"><i class="glyphicon--}}
                                {{--glyphicon-search"></i></span>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="col-sm-4">--}}
                            {{--<select class="form-control" name="filter" id="filter">--}}
                                {{--<option value="">-- FILTER --</option>--}}
                                {{--<option value="keyword">Keyword</option>--}}
                                {{--<option value="title">Title</option>--}}
                                {{--<option value="author">Author</option>--}}
                                {{--<option value="subject">Subject</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                    {{--</div>--}}
                    {!! BootForm::close() !!}

                </div>
            </div>
            <br>

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
                    <tr>
                        <td>Card Number</td>
                        <td>Call Number</td>
                        <td>Title</td>
                        <td>Autdor</td>
                        <td>Subject</td>
                        <td>Publisher</td>
                        <td>Date Published</td>
                        <td>Action</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>
@stop