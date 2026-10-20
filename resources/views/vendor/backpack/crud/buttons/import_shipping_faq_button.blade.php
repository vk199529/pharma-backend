<a href="#" onclick="event.preventDefault(); document.getElementById('import-form').querySelector('input[type=file]').click();" class="btn btn-sm btn-success">
    <i class="la la-upload"></i> Import Shipping FAQs
</a>

<form id="import-form" action="{{ route('shipping-faq.import') }}" method="POST" enctype="multipart/form-data" style="display:none;">
    @csrf
    <input type="file" name="csv" accept=".csv" onchange="document.getElementById('import-form').submit();" />
</form>
