<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Book;
use App\Models\Report;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDO;
use Illuminate\Support\Facades\File;

class GenerateBookList extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $report;
    private $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Report $report, array $request)
    {
        $this->report = $report;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->report->status = Report::GENERATING;
        $this->report->type = 'Book List';
        $this->report->save();

        ini_set('memory_limit', '1G');

        $query = Book::with('authors', 'subjects');

        $request_values = ['call_number', 'card_number', 'published_year'];

        foreach ($request_values as $request_value) {
            if ($this->request[$request_value] != '') {
                $query = $query->where($request_value, $this->request[$request_value]);
            }
        }

        if (isset($this->request['title']) && $this->request['title'] != '') {
            $query->where('title', 'LIKE', "%" . $this->request['title'] . '%');
        }

        if (isset($this->request['publisher']) && $this->request['publisher'] != '') {
            $query->where('publisher', 'LIKE', "%" . $this->request['publisher'] . '%');
        }

        if (isset($this->request['order']) && $this->request['order'] != '') {
            $books = $query->orderBy($this->request['sort'], $this->request['order'])->get();
        } else {
            $books = $query->get();
        }

        $pdf    = \PDF::loadView('admin.reports.book-list', compact('books'));

        $pdf->output();
        $dom_pdf    = @$pdf->getDomPDF();
        $canvas = @$dom_pdf ->get_canvas();
        $canvas->page_text(270, 810, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        Storage::makeDirectory('book_list/' . date('Y-m-d H:i:s') . " - {$this->report->id}");
        $final_filename = storage_path("app/book_list/" . date('Y-m-d H:i:s') . " - {$this->report->id}/book_list{$this->report->id}.pdf");

        if (file_exists($final_filename)) {
            unlink($final_filename);
        }

        $pdf->setPaper('a4', 'portrait')->save($final_filename);

        $this->report->file_size = File::size($final_filename);
        $this->report->filename = $final_filename;
        $this->report->status = Report::READY;
        $this->report->save();
    }
}
