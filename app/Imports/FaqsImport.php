<?php
namespace App\Imports;

use App\Models\Faq;
use App\Models\FaqCategory;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class FaqsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        
        return new Faq([
            'question' => trim($row['question']),
            'answer' => trim($row['answer']),
            
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
