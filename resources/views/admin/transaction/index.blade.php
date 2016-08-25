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
                    <h4>TRANSACTIONS</h4>
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
                    {!! BootForm::open()->action('/admin/transaction/filter') !!}
                    {!! BootForm::bind($filters) !!}
                    {!! BootForm::select('Borrowed/Reserved Book', 'book_id')->style('width:100%') !!}
                    {!! BootForm::select('Filter By Date', 'filter_by_date', ['reserved_at' => 'Reserved At', 'borrowed_at' => 'Borrowed At', 'returned_at' => 'Returned At'])->style('width:100%') !!}
                    {!! BootForm::date('From', 'from') !!}
                    {!! BootForm::date('To', 'to') !!}
                    {!! BootForm::select('Is Lost Book', 'is_lost', ['' => '', '1' => 'True', '0' => 'False'])->style('width:100%') !!}
                    {!! BootForm::select('Is Expired Reservation', 'is_expired', ['' => '', '1' => 'True', '0' => 'False']) ->style('width:100%')!!}
                    {!! BootForm::select('Is Overdue', 'is_overdue', ['' => '', '1' => 'True', '0' => 'False'])->style('width:100%') !!}
                    {!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'book_id' => 'Borrowed/Reserved Book', 'reserved_at' => 'Reserved At', 'borrowed_at' => 'Borrowed At', 'returned_at' => 'Returned At'])->style('width:100%') !!}
                    {!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending'])->style('width:100%') !!}
                    {!! BootForm::submit('Search')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
                <br>

                <div class="col-sm-9">
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed table-bordered">
                            <tr class="search-panel">
                                <th>Borrowed/Reserved Books</th>
                                <th>Reserved At</th>
                                <th>Borrowed At</th>
                                <th>Returned At</th>
                                <th>Is Lost</th>
                                <th>Is Expired Reservation</th>
                                <th>Is Overdue</th>

                            </tr>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->book->title}}</td>
                                    <td>{{@$transaction->reserved_at}}</td>
                                    <td>{{@$transaction->borrowed_at}}</td>
                                    <td>{{@$transaction->returned_at}}</td>

                                    <td>
                                        {!! $transaction->is_lost ? '<span class="label label-success">True</span>' : '<span class="label label-default">False</span>'!!}
                                    </td>
                                    <td>
                                        {!! $transaction->is_expired ? '<span class="label label-success">True</span>' : '<span class="label label-default">False</span>' !!}
                                    </td>
                                    <td>
                                        {!! $transaction->is_overdue ? '<span class="label label-success">True</span>' : '<span class="label label-default">False</span>' !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="8" class="text-danger">No records found</th>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                    <div class="text-center">
                        {!! $transactions->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

@endsection