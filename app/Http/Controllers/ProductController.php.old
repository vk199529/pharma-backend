<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function generateEmbeddings()
    {
        set_time_limit(300);
        $products = Search::whereNull('embedding_vector')->get();
        $count = 0;

        foreach ($products as $product) {
            $text = trim("{$product->generic_name} {$product->brand_names} {$product->therapeutic_class}");
            if (empty($text)) continue;

            $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/embeddings', [
                'model' => 'text-embedding-3-large',
                'input' => $text,
            ]);

            if ($response->successful() && isset($response['data'][0]['embedding'])) {
                $product->embedding_vector = json_encode($response['data'][0]['embedding']);
                $product->save();
                $count++;
            }
        }

        return response()->json(['status' => 'done', 'count' => $count]);
    }

    public function search(Request $request)
{
    $term = $request->query('ss');
    if (empty($term)) {
        return response()->json(['status' => 'error', 'message' => 'Empty search']);
    }
	$temperature = 0.83;
    // Get embedding from OpenAI
    $embeddingResponse = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/embeddings', [
        'model' => 'text-embedding-3-large',
        'input' => $term,
    ]);

    if (!$embeddingResponse->ok()) {
        return response()->json(['status' => 'error', 'message' => 'Embedding failed'], 500);
    }

    $queryVector = $embeddingResponse['data'][0]['embedding'];

    $products = Search::whereNotNull('embedding_vector')->get();
    $results = collect();

    foreach ($products as $product) {
        $vector = json_decode($product->embedding_vector, true);
        if (!is_array($vector) || count($vector) !== count($queryVector)) continue;

        $similarity = $this->cosineSimilarity($queryVector, $vector);
        $product->score = round($similarity, 3);
        $results->push($product);
    }

    
  $relevantProducts = $results->filter(fn ($p) => $p->score >= $temperature)->sortByDesc('score')->values();
	//$relevantProducts = $results->filter(fn ($p) => $p->score >= $temperature)->sortByDesc('score')->values();

   
    if ($relevantProducts->count() === 0) {
        $relevantProducts = $results->sortByDesc('score')->take(5)->values();
    }

   
    $topForGPT = $relevantProducts->take(3);
    $context = "Relevant Products:\n";
    foreach ($topForGPT as $p) {
        $context .= "- {$p->generic_name} ({$p->brand_names}) in class: {$p->therapeutic_class}\n";
    }

   $prompt = "A customer asked: '{$term}'. Based on the product list below, write a helpful and professional B2B pharma response (you may use basic HTML formatting if helpful):\n\n{$context}\n\nFor any Commercial, Technical, or Regulatory inquiries, please feel free to contact us directly via email at::<a href='mailto:Info@PanAmFarma.com'>Info@PanAmFarma.com</a><br>";



    $chatResponse = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful B2B pharmaceutical assistant.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => $temperature,
    ]);

    $answer = $chatResponse->successful()
        ? $chatResponse['choices'][0]['message']['content']
        : 'Sorry, I could not generate a response.';

    $answer = preg_replace('/Dear \[.*?\],\s*/i', '', $answer);
    $answer = preg_replace('/Best regards,.*(\[.*?\]\s*)+/is', '', $answer);
	$answer = preg_replace('/Warm regards,.*(\[.*?\]\s*)+/is', '', $answer);
    $answer = trim($answer);

    return response()->json([
        'status' => 'success',
        'products' => $relevantProducts,
        'answer' => $answer,
    ]);
}

    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = $magA = $magB = 0.0;
        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
            $magA += $a[$i] ** 2;
            $magB += $b[$i] ** 2;
        }
        return ($magA && $magB) ? $dot / (sqrt($magA) * sqrt($magB)) : 0.0;
    }
}
