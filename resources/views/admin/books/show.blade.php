@extends('layout')

@section('internal_stylesheet')
    <style type="text/css">
        .self-class {
            font-size: 12px;
            height:100%;
        }
    </style>
@endsection()

@section('content')
    <div id="content-header" style="border-bottom: 0px;">
        <div class="header-bg">
            <div class="container">
                <div class="col-lg-12">
                    <h4>BOOK DETAILS</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{url('/')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Back</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php
            $authors = [];
            foreach ($book->authors as $author) {
                $authors[] = $author->name;
            }

            $subjects = [];
            foreach ($book->subjects as $subject) {
                $subjects[] = $subject->name;
            }

            ?>
            <div class="col-sm-8 col-sm-offset-2 self-class">
                <p style="font-size: 25px; text-align: center"><strong style="color:#3c763d">{{strtoupper($book->title)}} </strong></p>
                <br>
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Card Number</th>
                        <td>{{ $book->card_number }}</td>
                    </tr>
                    <tr>
                        <th>Card Number</th>
                        <td>{{ $book->call_number }}</td>
                    </tr>
                    <tr>
                        <th>Title</th>
                        <td>{{ $book->title }}</td>
                    </tr>
                    <tr>
                        <th>Authors</th>
                        <td>{{ implode(', ', $authors) }}</td>
                    </tr>
                    <tr>
                        <th>Subjects</th>
                        <td>{{ implode(', ', $subjects) }}</td>
                    </tr>
                    <tr>
                        <th>Publisher</th>
                        <td>{{ $book->publisher }}</td>
                    </tr>
                    <tr>
                        <th>Year Published</th>
                        <td>{{ $book->published_year }}</td>
                    </tr>
                    <tr>
                        <th>Total Copies</th>
                        <td>{{ $book->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Available Copies</th>
                        <td>{{ $book->available_quantity }}</td>
                    </tr>
                </table>
                {!! BootForm::open()->post()->action(url('admin/reserve-books/' . $book->id)) !!}
                {!! BootForm::submit('Reserve', 'reserve')->class('btn btn-success form-control') !!}
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    <br />
    <br />
@endsection