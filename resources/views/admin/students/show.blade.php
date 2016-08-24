@extends('layout')

@section('title', '')
@section('header')
    STUDENT DETAILS
@endsection()
@section('content')
    <div class="col-sm-10 col-md-offset-1 ">
        <small>

            <div class="col-md-6">

                <div class="col-md-3">
                    <img src="/images/student-icon.png" alt="" width="100%">

                </div>
                <div class="panel panel-success">
                    <div class="panel-heading">STUDENT INFORMATION</div>
                    <div class="panel-body">
                        <div class="col-md-9">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="40%"><b>Name</b></td>
                                    <td>{{ $student->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Student ID</b></td>
                                    <td>{{ $student->student_id }}</td>
                                </tr>
                                <tr>
                                    <td><b>Level</b></td>
                                    <td>{{ $student->StudentLevel->name }}</td>
                                </tr>
                                <tr>
                                    <td><b>Grade/Year Level</b></td>
                                    <td>{{ $student->year_level }}</td>
                                </tr>
                                <tr>
                                    <td><b>Gender</b></td>
                                    <td>{{ $student->gender }}</td>
                                </tr>
                                <tr>
                                    <td><b>Email Address</b></td>
                                    <td>{{ $student->email }}</td>
                                </tr>
                                <tr>
                                    <td><b>Mobile Number</b></td>
                                    <td>{{ $student->mobile_number }}</td>
                                </tr>
                                <tr>
                                    <td><b>Course</b></td>
                                    <td>{{ $student->course_id }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-3">
                        <img src="/images/key.png" alt="" width="70%" style="margin: 10px">
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Login Credentials</div>
                        <div class="panel-body">
                            TODO: Display Login credential
                            <br>
                            <b>USERNAME: </b>
                            <br>
                            <b>PASSWORD: </b>
                            <br>
                            <button class="btn btn-default btn-xs pull-right" onclick="confirmation()">Reset Password</button>
                            <button class="btn btn-default btn-xs pull-right" onclick="confirmation()">Generate Password</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <div class="col-md-12">
                    <div class="col-md-3">
                        <img src="/images/history.png" alt="" width="70%" style="margin: 10px">
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">Transactions</div>
                        <div class="panel-body">
                            TODO: Display Student Transactions
                            <table class="table table-bordered">
                                <tr>
                                    <th>Book</th>
                                    <th>Date reserved</th>
                                    <th>Approved By</th>
                                    <th>Date Approved</th>
                                    <th>Date returned</th>
                                </tr>
                                @for($i=1;$i<=10;$i++)
                                    <tr>
                                        <td>Sample Book {{ $i }}</td>
                                        <td>{{ \Carbon\Carbon::now()->addDay($i) }}</td>
                                        <td>test</td>
                                        <td>test</td>
                                        <td>{{ \Carbon\Carbon::now()->addDay($i+1) }}</td>
                                    </tr>
                                @endfor

                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </small>
    </div>

        {{--<div class="col-sm-3 search-panel">--}}
            {{--<h3>{{ $student->name }}</h3>--}}
        {{--</div>--}}

    <script type="text/javascript">
        function confirmation() {
            swal({
                        title: "CONFIRMATION",
                        text: "Are you sure you want to do this?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel plx!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            swal("Okay na kunwari lang!", "SAMPLE ONLY WILL CHANGE THIS SOON");
                        } else {
                            swal("Cancelled", " ACTION CANCELLED", "error");
                        }
                    });
        }
    </script>
@stop