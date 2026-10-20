<?php

namespace App\Imports;

use App\Models\ShippingFaq;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ShippingFaqsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
       
        return new ShippingFaq([
            'question' => trim($row['question'] ?? ''),
            'answer' => trim($row['answer'] ?? ''),
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }
}

