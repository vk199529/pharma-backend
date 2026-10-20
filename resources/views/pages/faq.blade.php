@extends('layouts.default')

@section('content')
<div class="container">
    <h2 class="mb-4">Ask a Question</h2>

    <form id="faq-search-form">
        @csrf
        <div class="mb-3">
            <input type="text" name="question" id="faq-question" class="form-control" placeholder="Type your question..." required>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div id="ai-answer" class="alert alert-info mt-4 d-none"></div>

    <div id="faq-results" class="mt-4"></div>
</div>

<script>
document.getElementById('faq-search-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const question = document.getElementById('faq-question').value;
    const res = await fetch('{{ route('faq.search') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ question })
    });

    const data = await res.json();
    const faqContainer = document.getElementById('faq-results');
    const answerBox = document.getElementById('ai-answer');

    faqContainer.innerHTML = '';
    answerBox.classList.add('d-none');

    if (data.status !== 'success') {
        faqContainer.innerHTML = '<p class="text-danger">No matching FAQs found or AI failed.</p>';
        return;
    }

    if (data.answer) {
        answerBox.innerHTML = `<strong>AI Suggestion:</strong> ${data.answer}`;
        answerBox.classList.remove('d-none');
    }

    if (data.faqs.length === 0) {
        faqContainer.innerHTML = '<p>No similar FAQs found.</p>';
        return;
    }
//  <span class="badge bg-light text-dark float-end">Similarity: ${faq.similarity.toFixed(2)}</span>
    data.faqs.forEach(faq => {
        faqContainer.innerHTML += `
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">${faq.question}</h5>
                    <p class="card-text">${faq.answer}</p>
                    <span class="badge bg-secondary">${faq.category}</span>
                   
                </div>
            </div>
        `;
    });
});
</script>
@endsection
