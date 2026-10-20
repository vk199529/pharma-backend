<!-- <form action="{{ route('faq.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv" accept=".csv" required>
    <button type="submit">Import FAQs</button>
</form> -->

<a href="#" onclick="event.preventDefault(); document.getElementById('faq-import-form').querySelector('input[type=file]').click();" class="btn btn-sm btn-success">
    <i class="la la-upload"></i> Import FAQs
</a>

<form id="faq-import-form" action="{{ route('faq.import') }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="file" name="csv" accept=".csv" onchange="document.getElementById('faq-import-form').submit();" />
</form>

