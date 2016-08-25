<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
                    $books = $query->where('title', 'LIKE', "%$search%");
                    break;
                case 'authors.name' :
                    $books = $query->whereHas('authors', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });

                    break;
                case 'subjects.name' :
                    $books = $query->whereHas('subjects', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
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
