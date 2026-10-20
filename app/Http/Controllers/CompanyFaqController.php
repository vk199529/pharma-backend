<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyFaq ;
use App\Services\EmbeddingService;

class CompanyFaqController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
        ]);

        $embeddingService = app(EmbeddingService::class);
        $userVector = $embeddingService->generate($request->input('question'));

        if (!$userVector) {
            return response()->json(['status' => 'error', 'message' => 'Failed to generate embedding'], 500);
        }

        $results = [];

        foreach (CompanyFaq::whereNotNull('embedding_vector')->get() as $faq) {
            $faqVector = json_decode($faq->embedding_vector, true);
            if (!is_array($faqVector)) continue;

            $similarity = $this->cosineSimilarity($userVector, $faqVector);

            $results[] = [
                'question' => $faq->question,
                'answer' => $faq->answer,
                'similarity' => $similarity,
            ];
        }

        $topFaqs = collect($results)
            ->sortByDesc('similarity')
            ->filter(fn($r) => $r['similarity'] > 0.75)
            ->values()
            ->take(5);

        if ($topFaqs->isEmpty()) {
            $topFaqs = collect($results)->sortByDesc('similarity')->take(3)->values();
        }

        $context = "Relevant FAQs:\n";
        foreach ($topFaqs->take(3) as $faq) {
            $context .= "- Q: {$faq['question']}\n  A: {$faq['answer']}\n";
        }

        $prompt = "User asked: '{$request->input('question')}'. Based on the following, provide a support reply:\n\n{$context}";

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
