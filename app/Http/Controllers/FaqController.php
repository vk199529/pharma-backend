<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Services\EmbeddingService;

class FaqController extends Controller
{
    public function index()
    {
        return view('pages.faq');
    }

     public function search(Request $request)
{
    // Accept either `question` or fallback to `ss`
    $question = $request->input('question') ?? $request->input('ss');
    $request->merge(['question' => $question]); // so validation works

    // Validate user input
    $request->validate([
        'question' => 'required|string|max:500',
    ]);

    // Generate vector from user input
    $embeddingService = app(\App\Services\EmbeddingService::class);
    $userVector = $embeddingService->generate($question);

    if (!$userVector) {
        return response()->json(['status' => 'error', 'message' => 'Failed to generate embedding'], 500);
    }

    $results = [];

    foreach (\App\Models\Faq::whereNotNull('embedding_vector')->get() as $faq) {
        $faqVector = json_decode($faq->embedding_vector, true);
        if (!is_array($faqVector) || count($faqVector) !== count($userVector)) continue;

        $similarity = $this->cosineSimilarity($userVector, $faqVector);

        $results[] = [
            'question' => $faq->question,
            'answer' => $faq->answer,
            'category' => $faq->category->name ?? '-',
            'similarity' => $similarity,
        ];
    }

    // Sort by similarity
    $topFaqs = collect($results)
        ->sortByDesc('similarity')
        ->filter(fn($r) => $r['similarity'] > 0.75)
        ->values()
        ->take(5);

    // Fallback if none above threshold
    if ($topFaqs->isEmpty()) {
        $topFaqs = collect($results)->sortByDesc('similarity')->take(3)->values();
    }

    // Context for GPT
    $context = "Relevant FAQs:\n";
    foreach ($topFaqs->take(3) as $faq) {
        $context .= "- Q: {$faq['question']}\n  A: {$faq['answer']}\n";
    }

    $prompt = "A user asked: '{$question}'. Based on the following FAQs, write a helpful and professional support response:\n\n{$context}";

    $chatResponse = \Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-4o',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful customer support assistant.'],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.7,
    ]);

    $aiAnswer = $chatResponse->successful()
        ? trim($chatResponse['choices'][0]['message']['content'])
        : 'Sorry, I could not generate a response.';
		
		 $aiAnswer = preg_replace('/Dear \[.*?\],\s*/i', '', $aiAnswer);
        $aiAnswer = preg_replace('/Best regards,.*(\[.*?\]\s*)+/is', '', $aiAnswer);
        $aiAnswer = trim($aiAnswer);

    return response()->json([
        'status' => 'success',
        'faqs' => $topFaqs,
        'answer' => $aiAnswer,
    ]);
}

    private function cosineSimilarity(array $vec1, array $vec2): float
    {
        $dot = $normA = $normB = 0.0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dot += $vec1[$i] * $vec2[$i];
            $normA += $vec1[$i] ** 2;
            $normB += $vec2[$i] ** 2;
        }

        return $normA && $normB ? $dot / (sqrt($normA) * sqrt($normB)) : 0;
    }
}
