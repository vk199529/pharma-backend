<!-- <form action="{{ route('companyfaq.import') }}" method="POST" enctype="multipart/form-data" style="display:inline;">
    @csrf
    <input type="file" name="csv" required>
    <button class="btn btn-success"><i class="la la-upload"></i> Import FAQs</button>
</form> -->

<a href="#" onclick="event.preventDefault(); document.getElementById('companyfaq-import-form').querySelector('input[type=file]').click();" class="btn btn-sm btn-success">
    <i class="la la-upload"></i> Import Company FAQs
</a>

<form id="companyfaq-import-form" action="{{ route('companyfaq.import') }}" method="POST" enctype="multipart/form-data" style="display: none;">
    @csrf
    <input type="file" name="csv" accept=".csv" onchange="document.getElementById('companyfaq-import-form').submit();" />
</form>

