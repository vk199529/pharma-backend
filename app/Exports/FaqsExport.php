<?php

namespace App\Exports;

use App\Models\Faq;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FaqsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Faq::select('question', 'answer')->get();
    }

    public function headings(): array
    {
        return ['question', 'answer'];
    }
}
