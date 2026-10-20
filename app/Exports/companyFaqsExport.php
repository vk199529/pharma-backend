<?php

namespace App\Exports;

use App\Models\CompanyFaq;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class companyFaqsExport implements FromCollection, WithHeadings
{
    
      public function collection()
    {
        return CompanyFaq::select('question', 'answer')->get();
    }

    public function headings(): array
    {
        return ['question', 'answer'];
    }
}
