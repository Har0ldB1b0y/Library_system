<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{

    /**
     * PagesController constructor.
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('pages.index', compact('request'));
    }

    public function search(Request $request)
    {
        $query = Book::with('authors', 'subjects');

        if (!is_null($request->get('filter_by')) && !is_null($request->get('search'))) {
            $filter_by = $request->get('filter_by');
            $search = $request->get('search');

            switch ($filter_by) {
                case 'books.title' :
                    $book_title = Book::select('title')->where('id', $search)->first();

                    $books = $query->where('title', 'LIKE', "%$book_title->title%");
                    break;
                case 'authors.name' :
                    $author_name = Author::select('name')->where('id', $search)->first();

                    $books = $query->whereHas('authors', function ($query) use ($author_name) {
                        $query->where('name', 'like', "%$author_name->name%");
                    });

                    break;
                case 'subjects.name' :
                    $subject_name = Subject::select('name')->where('id', $search)->first();

                    $books = $query->whereHas('subjects', function ($query) use ($subject_name) {
                        $query->where('name', 'like', "%$subject_name->name%");
                    });

                    break;
                default :
                    $books = $query;
            }
        }

        $data = $books->paginate(10);

        return view('pages.index', compact('request', 'data'));
    }

}
