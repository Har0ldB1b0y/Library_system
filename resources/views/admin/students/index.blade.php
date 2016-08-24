@inject('StudentLevel','App\Models\StudentLevel')
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
@section('header')
    STUDENTS LIST
@endsection()
@section('content')
    <div class="col-sm-10 col-md-offset-1 self-class">
            <div class="col-sm-3 search-panel">
                <h4 class="text-success">Filter Records</h4>
                <br>
                {!! BootForm::open()->action('/admin/students/filter')->post() !!}
                {!! BootForm::bind(@$filters) !!}
                {!! BootForm::text('Student Number', 'student_id')->addClass('input-sm') !!}
                {!! BootForm::text('First Name', 'first_name')->addClass('input-sm') !!}
                {!! BootForm::text('Last Name', 'last_name')->addClass('input-sm') !!}
                {!! BootForm::select('Level', 'student_level_id',$StudentLevel->getLevels()) !!}
                {{--{!! BootForm::text('Publisher', 'publisher') !!}--}}
                {{--{!! BootForm::text('Year Published', 'published_year') !!}--}}
                {{--{!! BootForm::select('Sort By', 'sort', ['id' => 'ID', 'card_number' => 'Card Number', 'call_number' => 'Call Number', 'title' => 'Title', 'publisher' => 'Publisher', 'published_year' => 'Year Published']) !!}--}}
                {{--{!! BootForm::select('Order By', 'order', ['' => '-- Select One --', 'ASC' => 'Ascending', 'DESC' => 'Descending']) !!}--}}
                {!! BootForm::submit('Filter')->addClass('btn btn-success pull-right') !!}
                {!! BootForm::close() !!}
            </div>
            <br>

            <div class="col-sm-9">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed table-bordered">
                        <tr class="search-panel">
                            <th>Student Number</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Level</th>
                            <th>Grade/Year</th>
                            <th>Action</th>

                        </tr>
                        @forelse($students as $student)
                            <tr>
                                <td>{{$student->student_id}}</td>
                                <td>{{$student->name}}</td>
                                <td>{{$student->gender}}</td>
                                <td>{{@$student->StudentLevel->name}}</td>
{{--                                    <td>{{@$student->year_level}}</td>--}}
                                <td></td>
                                <td>
                                    <a href="{{url('admin/students/' . $student->id)}}" role="button" class="btn btn-default btn-xs">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="9" class="text-danger">No records found</th>
                            </tr>
                        @endforelse
                    </table>
                </div>
                {!! $students->render() !!}
            </div>
        </div>

    <br>
    <br>
    <div class="modal fade" role="dialog" id="createStudentModal">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-success">Create New Student Form</h4>
                </div>
                <div class="modal-body">
                    <small>
                        {!! BootForm::openHorizontal(['md' => [3,9]])->action('/admin/students')->post() !!}
                        {!! BootForm::text('First Name','first_name')->addClass('input-sm')->require() !!}
                        {!! BootForm::text('Middle Name','middle_name')->addClass('input-sm')!!}
                        {!! BootForm::text('Last Name','last_name')->addClass('input-sm')->require() !!}
                        {!! BootForm::text('Suffix','suffix')->addClass('input-sm')->require() !!}
                        {!! BootForm::select('Gender','gender',[
                            '' => '-- Select Gender --',
                            'M' => 'Male',
                            'F' => 'Female',
                        ])->addClass('input-sm')->require()->style('width:100%') !!}
                        {!! BootForm::select('Student Level', 'student_level_id',['' => '-- Select Level --']+$StudentLevel->getLevels())->style('width:100%') !!}

                    </small>
                </div>
                <div class="modal-footer">
                    {!! BootForm::submit('Save')->addClass('btn btn-success') !!}
                    {!! BootForm::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection