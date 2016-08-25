<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Bzarzuela\ModelFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    private $model_filter;

    private $filter_rules = [
      'user_id'     => ['=', 'call_number'],
      'book_id'     => ['=', 'card_number'],
      'is_lost'     => ['=', 'is_lost'],
      'is_expired'  => ['=', 'is_expired'],
      'is_overdue'  => ['=', 'is_overdue']
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->model_filter = new ModelFilter('transactions');

        $this->model_filter->setRules($this->filter_rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = $this->model_filter->getFormData();

//        dd($filters);

        $order_by = isset($filters['order']) ? $filters['order'] : '';

        $filter_by_date = isset($filters['filter_by_date']) ? $filters['filter_by_date'] : '';
        $from = isset($filters['from']) ? $filters['from'] : '';
        $to = isset($filters['to']) ? $filters['to'] : '';

        $this->model_filter->setFormData($filters);

        $query = Transaction::with('book', 'user');

        if ($order_by != '') {
            $transactions = $this->model_filter->filter($query)
              ->orderBy($filters['sort'], $order_by)
              ->paginate(10);
        } elseif ($from != '' && $to != '') {
            $transactions = $this->model_filter->filter($query)
              ->where($filter_by_date, '>=', $from . ' 00:00:00')
              ->where($filter_by_date, '<=', $to . ' 23:59:59')
              ->orderBy($filters['sort'], $order_by)
              ->paginate(10);
        } else {
            $transactions = $this->model_filter->filter($query)->orderBy('id', 'ASC')->paginate(10);
        }


        return view('admin.transaction.index', compact('transactions', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transaction.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        return redirect('/admin/transaction');
    }
}
