<a href="#" 
   onclick="event.preventDefault(); document.getElementById('product-import-form').querySelector('input[type=file]').click();" 
   class="btn btn-sm btn-success">
    <i class="la la-upload"></i> Import Products
</a>

<form id="product-import-form" 
      action="{{ route('product.import') }}" 
      method="POST" 
      enctype="multipart/form-data" 
      style="display:none;">
    @csrf
    <input type="file" 
           name="import_file" 
           accept=".csv" 
           onchange="document.getElementById('product-import-form').submit();" />
</form>