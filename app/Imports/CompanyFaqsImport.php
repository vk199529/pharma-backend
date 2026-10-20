<?php

namespace App\Imports;

use App\Models\CompanyFaq;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CompanyFaqsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    
       public function model(array $row)
    {
        // dd($row);
        // die('yes');
        return new CompanyFaq([
            'question' => trim($row['question'] ?? ''),
            'answer' => trim($row['answer'] ?? ''),
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
