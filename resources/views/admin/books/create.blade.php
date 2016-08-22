@inject('materials', 'App\Models\Material')
@inject('authors', 'App\Models\Author')
@inject('subjects', 'App\Models\Subject')


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
                    <h4>Books</h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <a href="{{url('admin/books')}}" class="btn btn-success"><i class="fa fa-1x
                fa-plus-circle"></i> Back</a>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 self-class">
                <p style="font-size: 25px; text-align: center">Add <strong style="color:#3c763d">NEW</strong></p>
                <br>
                <div class="col-md-6">
                    <?php
                        $max_year = 2070;
                        for ($i = 1700; $i <= $max_year; $i++) {
                            $year[$i] = $i;
                        }
                    ?>
                    {!! BootForm::open()->post()->action(url('admin/books')) !!}
                    {!! BootForm::text('Card Number', 'card_number') !!}
                    {!! BootForm::text('Call Number', 'call_number') !!}
                    {!! BootForm::text('Title', 'title') !!}
                    {!! BootForm::select('Author', 'author', ['' => ''] + $authors->getAuthors())->data('tags', true)->multiple() !!}
                    {!! BootForm::select('Subject', 'subject', ['' => ''] + $subjects->getSubjects())->data('tags', true)->multiple() !!}
                    {!! BootForm::select('Material Type', 'material_id', ['' => ''] + $materials->getMaterials())->placeholder('Material Type') !!}
                    {!! BootForm::text('Publisher', 'publisher') !!}
                    {!! BootForm::select('Year Published', 'published_year', ['' => ''] + $year) !!}
                    {!! BootForm::text('Publish Place', 'publish_place') !!}
                    <div class="form-group">
                        <label class="control-label" for="quantity">Copies Available</label>
                        <input type="number" name="quantity" id="quantity" class="form-control">
                    </div>
                    {!! BootForm::text('ISBN', 'isbn') !!}
                    {!! BootForm::select('ETAL', 'etal', ['' => '', 'True' => 'True', 'False' => 'False']) !!}

                </div>
                <div class="col-md-6">
                    {!! BootForm::text('Edition', 'edition') !!}
                    {!! BootForm::text('Physical Description', 'physical_desc') !!}
                    {!! BootForm::text('AETitle', 'aetitle') !!}
                    {!! BootForm::text('STitle', 'stitle') !!}
                    {!! BootForm::select('Book Level', 'book_level', ['' => '', 'CO' => 'CO', 'HS' => 'HS', 'GS' => 'GS']) !!}
                    {!! BootForm::text('Editor', 'editor') !!}
                    {!! BootForm::text('Illustrator', 'illustrator') !!}
                    {!! BootForm::text('Compiler', 'compiler') !!}
                    {!! BootForm::textarea('Note', 'note')->rows(4) !!}
                    {!! BootForm::submit('Save', 'save')->class('btn btn-success form-control') !!}
                </div>
                {!! BootForm::close() !!}
            </div>
        </div>
    </div>
    <br />
    <br />
@endsection

@section('page_js')
    <script type="text/javascript">
        $(function() {
            $("#material_id").select2({
                placeholder: "-- Select Material Type --",
                allowClear: true
            });

            $("#author").select2({
                placeholder: "-- Select Authors --",
                allowClear: true
            });

            $("#subject").select2({
                placeholder: "-- Select Subjects --",
                allowClear: true
            });

            $("#published_year").select2({
                placeholder: "-- Select Year Published --",
                allowClear: true
            });

            $("#etal").select2({
                placeholder: "-- Select if true or false --",
                allowClear: true
            });

            $("#book_level").select2({
                placeholder: "-- Select Book Level --",
                allowClear: true
            });
        });
    </script>
@endsection