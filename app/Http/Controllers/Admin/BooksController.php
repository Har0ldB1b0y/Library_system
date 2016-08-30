<?php

namespace App\Http\Controllers\Admin;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bzarzuela\ModelFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BooksController extends Controller
{
    private $model_filter;

    private $filter_rules = [
      'call_number' => ['=', 'call_number'],
      'card_number' => ['=', 'card_number'],
      'title' => ['like', 'title'],
      'publisher' => ['like', 'publisher'],
      'published_year' => ['=', 'published_year'],
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->model_filter = new ModelFilter('books');

        $this->model_filter->setRules($this->filter_rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('Access Denied!');
            return back();
        }

        $filters = $this->model_filter->getFormData();

        $order_by = isset($filters['order']) ? $filters['order'] : '';

        $this->model_filter->setFormData($filters);

        $query = Book::with('authors', 'subjects');

//        dd($query->get()->toArray());

        if ($order_by != '') {
            $books = $this->model_filter->filter($query)->orderBy($filters['sort'], $order_by)->paginate(10);
        } else {
            $books = $this->model_filter->filter($query)->orderBy('id', 'ASC')->paginate(10);
        }

        return view('admin.books.index', compact('books', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Books');
            return back();
        }

        $barcode = '';
        $check_barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));

        $book = Book::where('barcode', '=', $check_barcode)->first();

        if (!$book) {
            $barcode = date('Ymd').sprintf("%06d", mt_rand(1, 999999));
        } else {
            $barcode = $check_barcode;
        }

        return view('admin.books.create', compact('barcode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\BookRequest $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Books');
            return back();
        }

        $author_arr = $request->get('author');
        $subject_arr = $request->get('subject');

        $author_ids = [];
        $subject_ids = [];

        foreach ($author_arr as $author) {
            $check_author = Author::where('name', $author)->first();

            if (!$check_author) {
                $author_data = Author::firstOrCreate([
                  'name' => $author,
                ]);

                $author_ids[] = $author_data->id;
             } else {
                $author_ids[] = $check_author->id;
            }
        }

        foreach ($subject_arr as $subject) {
            $check_subject = Subject::where('name', $subject)->first();

            if (!$check_subject) {
                $subject_data = Subject::firstOrCreate([
                   'name' => $subject,
                ]);

                $subject_ids[] = $subject_data->id;
            } else {
                $subject_ids[] = $check_subject->id;
            }
        }

        $books = Book::create($request->except('_token', 'author', 'subject'));
        $books->save();

        $books->authors()->sync($author_ids);
        $books->subjects()->sync($subject_ids);

        alert()->success(strtoupper($books->title) . " successfully added");

        return redirect('admin/books');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with('authors', 'subjects')->findOrFail($id);

        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->hasRole('admin')) {
            alert()->error('You are not allowed to manage Books');
            return back();
        }
        $book = Book::find($id);

        $subjects = Subject::lists('name', 'id')->toArray();

        if (!$book) {
            alert()->error('Book: ' . $id . ' not found');

            return back();
        }

        return view('admin.books.edit', compact('book', 'subjects'));
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

        $author_arr = $request->get('authors');
        $subject_arr = $request->get('subjects');

        $author_ids = [];
        $subject_ids = [];

        foreach ($author_arr as $author) {
            $check_author = Author::where('name', $author)->first();

            if (!$check_author) {
                $author_data = Author::firstOrCreate([
                  'name' => $author,
                ]);

                $author_ids[] = $author_data->id;
            } else {
                $author_ids[] = $check_author->id;
            }
        }

        foreach ($subject_arr as $subject) {
            $check_subject = Subject::where('name', $subject)->first();

            if (!$check_subject) {
                $subject_data = Subject::firstOrCreate([
                  'name' => $subject,
                ]);

                $subject_ids[] = $subject_data->id;
            } else {
                $subject_ids[] = $check_subject->id;
            }
        }

        $book   = Book::with('authors', 'subjects')->find($id);
        $book->update($request->all());

        $book->save();

        $book->authors()->sync($author_ids);
        $book->subjects()->sync($subject_ids);

        alert()->success("{$book->title} successfully edited");
        return redirect('admin/books');
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

        return redirect('/admin/books');
    }
}
