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
                    @if (Auth::user()->hasRole('admin'))
                        <h4>TRANSACTIONS</h4>
                    @else
                        <h4>MY TRANSACTIONS</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 self-class">
                {{-- Transaction Details Modal --}}
                <div id="view-details" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h5 class="modal-title">TRANSACTION DETAILS</h5>
                                </div>
                                <div class="modal-body">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Return Books Modal --}}

                <div id="return-book" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h5 class="modal-title">RETURN BOOK</h5>
                            </div>
                            <div class="modal-body">
                                <p></p>
                                {!! BootForm::open() !!}
                                {!! BootForm::hidden('trans-id') !!}
                                {!! BootForm::text('Amount', 'amount') !!}
                                {!! BootForm::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="col-sm-3 search-panel">
                    <h4 class="text-success">Search Panel</h4>
                    <br>
                    {!! BootForm::open()->action('/admin/transaction/filter') !!}
                    {!! BootForm::bind($filters) !!}
                    @if (Auth::user()->hasRole('admin'))
                        {!! BootForm::text('Barcode', 'barcode') !!}
                        {!! BootForm::select('Users', 'user_id') !!}
                    @endif
                    {!! BootForm::select('Borrowed/Reserved Book', 'book_id')->style('width:100%') !!}
                    {!! BootForm::select('Filter By Date', 'filter_by_date', ['reserved_at' => 'Reserved At', 'borrowed_at' => 'Borrowed At', 'returned_at' => 'Returned At'])->style('width:100%') !!}
                    {!! BootForm::date('From', 'from') !!}
                    {!! BootForm::date('To', 'to') !!}
                    {!! BootForm::select('Is Lost Book', 'is_lost', ['' => '', '1' => 'True', '0' => 'False'])->style('width:100%') !!}
                    {!! BootForm::select('Is Expired Reservation', 'is_expired', ['' => '', '1' => 'True', '0' => 'False']) ->style('width:100%')!!}
                    {!! BootForm::select('Is Overdue', 'is_overdue', ['' => '', '1' => 'True', '0' => 'False'])->style('width:100%') !!}
                    {!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'book_id' => 'Borrowed/Reserved Book', 'reserved_at' => 'Reserved At', 'borrowed_at' => 'Borrowed At', 'returned_at' => 'Returned At'])->style('width:100%') !!}
                    {!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending'])->style('width:100%') !!}
                    {!! BootForm::submit('Search')->id('search')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
                <br>

                <div class="col-sm-9">
                    <div class="table-responsive">
                        <table class="table table-condensed table-bordered">
                            <tr class="search-panel">
                                <th>Borrowed/Reserved Books</th>
                                <th>Status</th>
                                <th>Reserved At</th>
                                <th>Borrowed At</th>
                                <th>Returned At</th>
                                <th>Is Lost</th>
                                <th>Is Expired Reservation</th>
                                <th>Is Overdue</th>
                                <th></th>

                            </tr>

                            @forelse($transactions as $transaction)
                                <?php
                                    $table_color = '';

                                    if($transaction->status == 'borrowed') {
                                        $table_color = 'info';
                                    } elseif($transaction->status == 'expired') {
                                        $table_color = 'warning';
                                    } elseif($transaction->status == 'returned') {
                                        $table_color = 'success';
                                    } elseif($transaction->status == 'canceled' || $transaction->status == 'rejected') {
                                        $table_color = 'danger';
                                    }

//                                    var_dump($transaction->toArray());
//                                    var_dump(!empty($transaction->fees) ? $transaction->fees[0]->amount :
//                                            '');
//                                    var_dump(isset($transaction->fees[0]->amount) ? $transaction->fees[0]->amount : '');
                                ?>



                                <tr class="{{ $table_color }}">
                                    <td>{{$transaction->book->title}}</td>
                                    <td>{{ ucwords($transaction->status) }}</td>
                                    <td>{{@$transaction->reserved_at}}</td>
                                    <td>{{@$transaction->borrowed_at}}</td>
                                    <td>{{@$transaction->returned_at}}</td>

                                    <td>
                                        {!! $transaction->is_lost ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>'!!}
                                    </td>
                                    <td>
                                        {!! $transaction->is_expired ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' !!}
                                    </td>
                                    <td>
                                        {!! $transaction->is_overdue ? '<span class="label label-success">Yes</span>' : '<span class="label label-default">No</span>' !!}
                                    </td>
                                    <td>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        @if($transaction->status == 'reserved')
                                            @if (Auth::user()->hasRole('admin'))
                                                    <a href="/admin/approve-reservation/{{$transaction->id}}"
                                                       class="btn btn-primary btn-xs btn-block approve-reservation">Approve</a>
                                            @endif
                                            @if (Auth::user()->hasRole('admin'))
                                                <a href="/admin/reject-reservation/{{$transaction->id}}"
                                                   class="btn btn-danger btn-xs btn-block
                                                       reject-reservation">Reject</a>

                                            @else
                                                <a href="/admin/reject-reservation/{{$transaction->id}}"
                                                   class="btn btn-danger btn-xs btn-block
                                                       reject-reservation">Cancel</a>
                                            @endif
                                        @endif

                                         @if (Auth::user()->hasRole('admin'))
                                            @if (!is_null($transaction->borrowed_at))
                                                @if ($transaction->is_overdue == false)
                                                  <a href="/admin/return-books-exact/{{$transaction->id}}" class="btn btn-warning btn-xs btn-block return-book">Return</a>
                                                @else
                                                  <a class="btn btn-warning btn-xs btn-block" data-toggle="modal"
                                                       data-target="#return-book" data-trans-id="{{ $transaction->id }}"
                                                       data-trans-amount="{{ !empty($transaction->fees) ? $transaction->fees : '' }}
                                                               ">Return</a>
                                                @endif
                                            @endif
                                        @endif
                                        <a class="btn btn-success btn-xs btn-block" data-toggle="modal"
                                                data-target="#view-details">Details</a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="9" class="text-danger">No records found</th>
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
@section('page_js')
    <script>
        $(function (){
            $("#barcode").focus();

            $("#book_id").select2({
                placeholder: "-- Select Book Title --",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '/books/search',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    }
                }
            });

            $("#user_id").select2({
                placeholder: "-- Select Name --",
                allowClear: true,
                minimumInputLength: 3,
                ajax: {
                    url: '/users/search',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page
                        };
                    }
                }
            });

            $('.approve-reservation').on('click', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');

                swal({
                    title: "Are you sure to approve this reservation?",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                }, function () {
                    $.post(url, {_token: $('input[name=_token]').val()}, function () {
                        swal({
                            title: "Success!",
                            text: "Reservation successfully approved",
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        window.setTimeout(function(){
                            window.location.href = "/admin/transaction";
                        }, 3000);
                    });
                });
            });

            $('.return-book').on('click', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');

                swal({
                    title: "Are you sure to return this book?",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: 'Yes'
                }, function(){
                    $.post(url, {_token: $('input[name=_token]').val(), amount: inputValue}, function () {
                        swal({
                            title: "Success!",
                            text: "The book is successfully returned",
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

////
//                        window.setTimeout(function(){
//                            window.location.href = "/admin/transaction";
//                        }, 3000);
                    });
                });
            });

            $('#return-book').on('show.bs.modal', function(e) {
                var transId = $(e.relatedTarget).data('trans-id');
                var amount = $(e.relatedTarget).data('trans-amount');

                var arr = jQuery.parseJSON(amount);

                $(e.currentTarget).find('input[name="trans-id"]').val(transId);
                $(e.currentTarget).find('input[name="amount"]').val(arr[0].amount);
            });

            $('.reject-reservation').on('click', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');

                swal({
                    title: "Are you sure to cancel/reject this reservation?",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: "Yes",
                    closeOnConfirm: true
                }, function () {
                    $.post(url, {_token: $('input[name=_token]').val()}, function () {
                        swal({
                            title: "Success!",
                            text: "Reservation successfully canceled/rejected",
                            type: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        window.setTimeout(function(){
                            window.location.href = "/admin/transaction";
                        }, 3000);
                    });
                });
            });
        });
    </script>
@endsection