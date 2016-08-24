<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Bzarzuela\ModelFilter;
use Illuminate\Http\Request;

use App\Http\Requests;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->model_filter = new ModelFilter('students');
        $this->model_filter->setRules([
            'first_name' => ['like'],
            'last_name' => ['like'],
            'student_id' => ['='],
            'student_level_id' => ['='],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Student::create([
            'first_name' =>'afasd',
            'last_name' =>'afasd',
            'middle_name' =>'afasd',
            'gender' =>'M',
            'student_level_id' => 2,
            'user_id' => 1,
        ]);
        $filters = $this->model_filter->getFormData();

        $query = Student::query();

        $students = $this->model_filter->filter($query)->paginate(20);

        return view('admin.students.index',compact('students','filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student = Student::create($request->all());
        alert()->success('New student created');
        return redirect('/admin/students/'.$student->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students.show',compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filter(Request $request)
    {
        $this->model_filter->setFormData($request->except('_token'));

        return redirect('/admin/students');
    }
}
