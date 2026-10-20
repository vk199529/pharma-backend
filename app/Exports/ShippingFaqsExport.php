<?php

namespace App\Exports;

use App\Models\ShippingFaq;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShippingFaqsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ShippingFaq::select('question', 'answer')->get();
    }

    public function headings(): array
    {
        return ['question', 'answer'];
    }
}
