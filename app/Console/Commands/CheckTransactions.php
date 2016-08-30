<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Fee;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spusm:check-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking of transactions if it is expired or overdue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Checking For Overdue
        $transactions = Transaction::where('status', 'borrowed')->get();

        $count = 0;
        foreach ($transactions as $transaction) {
            $book = Book::with('subjects')->where('id', $transaction->book_id)->first();

            $subs = [];
            foreach ($book->subjects as $subject) {
                $subs[] = $subject->name;
            }

            $amount = null;

            foreach($subs as $key => $value) {
                if (strpos($value, 'Fiction') !== false) {
                    $amount = 8;
                } else {
                    $amount = 10;
                }
            }

            if ($transaction->type == 'reserved') {

                if (Carbon::now() > $transaction->return_at) {
                    $overdue_day_counts = Carbon::now()->diffInDays(Carbon::parse($transaction->return_at));

                    $fees = Fee::where('transaction_id', $transaction->id)->first();

                    if ($fees) {
                        $added_amount = 0;
                        if ($overdue_day_counts !== $fees->overdue_day_counts) {
                            foreach($subs as $key => $value) {
                                if (strpos($value, 'Fiction') !== false) {
                                    $added_amount = 3;
                                } else {
                                    $added_amount = 5;
                                }
                            }
                        }

                        $total_amount = ($overdue_day_counts - 1) * $added_amount;

                        $fees->transaction_id = $transaction->id;
                        $fees->user_id = $transaction->user_id;
                        $fees->type = 'overdue';
                        $fees->receipt_no = '';
                        $fees->amount += $total_amount;
                        $fees->overdue_day_counts = $overdue_day_counts;

                        $fees->save();
                    } else {
                        $transaction->is_overdue = true;
                        $transaction->save();

                        Fee::create([
                           'transaction_id'     => $transaction->id,
                           'user_id'            => $transaction->user_id,
                           'type'               => 'overdue',
                           'amount'             => $amount,
                           'receipt_no'         => '',
                           'overdue_day_counts'  => 1
                        ]);
                    }

                    $count++;
                }
            }
        }

        //Checking and resetting transaction and book quantity if expired
        $expired_transactions = Transaction::where('status', 'reserved')->get();

        foreach($expired_transactions as $expired_transaction) {
            $count_of_days_it_was_reserved = Carbon::now()->diffInDays(Carbon::parse($expired_transaction->reserved_at));

            if ($count_of_days_it_was_reserved >= 2) {
                $book = Book::where('id', $expired_transaction->book_id)->first();

                if ($book) {
                    $book->available_quantity += 1;
                    $book->save();
                }

                $expired_transaction->status = 'expired';
                $expired_transaction->is_expired = true;
                $expired_transaction->expired_at = Carbon::now();

                $expired_transaction->save();
            }
        }

    }
}
