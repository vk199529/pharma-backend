<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EmbeddingService
{
    protected string $model = 'text-embedding-3-large';

    public function generate(string $text): ?array
    {
        $text = trim($text);

        if (empty($text)) {
            return null;
        }

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/embeddings', [
                'model' => $this->model,
                'input' => $text,
            ]);

        if ($response->successful() && isset($response['data'][0]['embedding'])) {
            return $response['data'][0]['embedding'];
        }

        return null;
    }
}
