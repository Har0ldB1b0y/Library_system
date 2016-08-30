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
      'book_id'       => ['=', 'book_id'],
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

        $order_by = isset($filters['order']) ? $filters['order'] : '';

        $filter_by_date = isset($filters['filter_by_date']) ? $filters['filter_by_date'] : '';
        $from = isset($filters['from']) ? $filters['from'] : '';
        $to = isset($filters['to']) ? $filters['to'] : '';
        $barcode = isset($filters['barcode']) ? $filters['barcode'] : '';
        $user = isset($filters['user_id']) ? $filters['user_id'] : '';

        $this->model_filter->setFormData($filters);

        $query = Transaction::with('book', 'user', 'fees');

        if (!Auth::user()->hasRole('admin')){
            $query->where('user_id', Auth::user()->id);
        }

        if ($user != '') {
            $query->where('user_id', $user);
        }

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
        } elseif ($barcode != '') {
            $book = Book::where('barcode', $barcode)->first(['id']);
            $transactions = $this->model_filter->filter($query)
              ->where('book_id', $book->id)
              ->paginate(10);
        } else {
            $transactions = $this->model_filter->filter($query)->orderBy('id', 'ASC')->paginate(10);
        }

//        $wew = [];
//        foreach ($transactions as $t) {
//            dd($t->fees[0]->amount);
//            $wew[] = $t->fees;
//        }
//
//        dd($wew);


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

    public function searchByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        $transactions = Book::with('transactions')->where('barcode', $barcode)->first();

        if ($transactions) {
            $success = true;
        } else {
            $success = false;
        }

        return response()->json(['success' => $success, 'transactions' => $transactions]);
    }

    public function reserve($id)
    {
        $book = Book::findOrFail($id);

        $check_transaction_record = Transaction::select(DB::raw('status, book_id, user_id'))
          ->where('user_id', Auth::user()->id)
          ->where('status', '!=','returned')->get();

        $count = 0;
        foreach($check_transaction_record as $value ) {
            if (!Auth::user()->hasRole('admin') && $value->book_id == $book->id) {
                alert()->warning("Please choose another book with a different title.",  'You already have an active reservation for this book')->persistent('Okay');
                return back();
            }
            $count++;
        }

        if (!Auth::user()->hasRole('admin') && $count >= 2) {
            alert()->warning("Sorry, You have exceeded the number of books that can be reserved through OPAC.")->persistent('Okay');
            return back();
        }

//        if (!Auth::user()->hasRole('admin') && $)

        if ($book->available_quantity > 0) {
            $borrowed_quantity = ($book->available_quantity - 1);

            $borrow_book = Transaction::create([
              'book_id'     => $book->id,
              'user_id'     => Auth::user()->id,
              'quantity'    => $book->available_quantity,
              'type'        => Auth::user()->hasRole('admin') ? 'non-reserved' : 'reserved',
              'status'      => 'reserved',
              'reserved_at' => Carbon::now(),
              'borrowed_at' => null,
              'returned_at' => null,
              'is_expired'  => false,
              'is_overdue'  => false,
              'is_lost'     => false
            ]);

            $borrow_book->save();

            $book->available_quantity = $borrowed_quantity;
            $book->save();
            alert()->success(strtoupper($book->title) . " is now reserved.")->persistent('Okay');
        } else {
            alert()->warning("There are no available copies of this book.")->persistent('Okay');
            return back();
        }

        return redirect('admin/transaction');

    }

    public function approveBookReservation($id)
    {
        $transaction = Transaction::find($id);

        $book = Book::where('id', $transaction->book_id)->first();

        $subs = [];
        foreach ($book->subjects as $subject) {
            $subs[] = $subject->name;
        }

        $return_at = '';

        foreach($subs as $key => $value) {
            if (strpos($value, 'Fiction') !== false) {
                $return_at = Carbon::parse($transaction->reserved_at)->addWeek();
            } elseif($transaction->type == 'non-reserved' && strpos($value, 'Fiction') === false) {
                $return_at = Carbon::now()->addDays(2);
            } else {
                $return_at = Carbon::now()->tomorrow()->hour(9);
            }
        }

        $transaction->status = 'borrowed';
        $transaction->borrowed_at = Carbon::now();
        $transaction->return_at = $return_at;
        $transaction->save();

        return $transaction;
    }

    public function rejectReservation($id)
    {
        $transaction = Transaction::find($id);

        $book = Book::where('id', $transaction->book_id)->first();

        $book->available_quantity += 1;
        $book->save();

        if (Auth::user()->hasRole('admin')) {
            $transaction->status = 'rejected';
        } else {
            $transaction->status = 'canceled';
        }
        $transaction->save();

        return $transaction;
    }

    public function returnBookWithExactAmount($id)
    {
        $transaction = Transaction::find($id);

    }

    public function returnBook(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        dd($transaction->toArray());
    }
}
