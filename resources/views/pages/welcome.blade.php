@extends('layouts.default')

@section('content')

<style>
.footer-width-fixer {
    display: none;
}

h1.blue-search-heading {
    display: block;
    font-size: 1.5em !important;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    unicode-bidi: isolate;
    color: white;
    padding: 0px !important;
    text-align: center;
}

.header-search-page {
    background: #0e7af3;
    color: white;
    padding: 0.5em;
    text-align: center;
}

.search-container {
    margin: 50px auto;
    max-width: 700px;
    text-align: center;
}

.search-box {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

.suggestions {
    background: white;
    margin-top: 5px;
    border-radius: 8px;
    display: none;
    width: 60%;
    margin: auto;
    max-width: 100%;
}
.suggestion-item {
    padding: 12px;
    border: 1px solid #ddd;
    cursor: pointer;
    margin: 7px 0;
    border-radius: 5px;
    background-color: #f1f1f1;
}

.results {
    margin: 40px auto;
    max-width: 800px;
}

/* .product-section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; } */
.product-section h3 {
    margin-top: 0;
}

.badge {
    display: inline-block;
    background: #28a745;
    color: white;
    padding: 3px 8px;
    margin-right: 5px;
    border-radius: 4px;
    font-size: 12px;
}
.new-form-staic {
    display: unset !important;
    background-color: #ffffff;
    padding: 25px;
    border-radius: 8px;
    max-width: 600px;
    margin: auto;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
	text-align:left !important;
}

.new-form-staic label {
    text-align: left;
    display: block;
    font-size: 18px;
    font-family: Arial, sans-serif;
    font-weight: 600;
    padding: 0 0 10px;
}
.new-form-staic select#queryType {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    resize: vertical;
    outline: navajowhite;
}
.new-form-staic input {
    padding-bottom: 70px !important;
    margin: 0 0 10px;
}
.new-form-staic button {
    display: block;
    margin: 15px  0  0;
}

div#suggestions {
    margin-top: 20px !important;
}
</style>
<div class="header-search-page" style="display:none;">
    <h1 class="blue-search-heading">PanAm Farma - Pharmaceutical B2B Search</h1>
  </div>


 <div class="search-bar new-form-staic search-container">
  <label for="queryType">Select Specific Category:</label>
  <select id="queryType" onchange="updatePlaceholder()">
    <option value="product">📦 Product Inquiry (Name, Molecule, Strength, MOQ, etc.)</option>
    <option value="company">🏢 Company or Regulatory Information</option>
    <option value="logistics">🚚 Shipping / Export / Logistics</option>
    <option value="other">💬 Other / Custom Query</option>
  </select>

  <label for="searchInput">Enter Your Request Below:</label>
  <input type="text" id="searchInput" placeholder="e.g. Do you stock Rituximab 500mg, vial form?" />
  <button type="button" id="askBtn">Submit Enquiry</button>

  <div id="suggestions" class="mt-4"></div>
</div>



  <div id="suggestions" class="suggestions"></div>

<x-modal id="productModal" title="Product Details" :scrollable="true">
    <x-slot name="body">

        {{-- Start Product Card --}}
       <div class="product-header" id="product_title_head">
    <span id="product_heading_text"></span>
    <span id="remove-suggestion" style="float:right;"></span>
</div>


            <div class="product-section ">
                <div class="section-title">Product Overview</div>
                   <table>
      <tr id="row_generic_name" class="product-overview-r"><td><strong>Generic Name</strong></td><td id="generic_name"></td></tr>
      <tr id="row_brand_names" class="product-overview-r"><td><strong>Brand Name(s)</strong></td><td id="brand_names"></td></tr>
      <tr id="row_form" class="product-overview-r"><td><strong>Form</strong></td><td id="form"></td></tr>
      <tr id="row_strength" class="product-overview-r"><td><strong>Strength</strong></td><td id="strength"></td></tr>
      <tr id="row_therapeutic_class" class="product-overview-r"><td><strong>Therapeutic Class</strong></td><td id="therapeutic_class" ></td></tr>
      <tr id="row_atc_code" class="product-overview-r"><td><strong>ATC Code</strong></td><td id="atc_code"></td></tr>
    </table>
            </div>

            <div class="product-section">
                <div class="section-title">Manufacturing & Regulatory</div>
                <table>
        <tr class="product-overview-r" id="row_manufacturer"><td><strong>Manufacturer</strong></td><td id="manufacturer"></td></tr>
        <tr class="product-overview-r" id="row_gmp_compliance"><td><strong>GMP Compliance</strong></td><td id="gmp_compliance"></td></tr>
        <tr class="product-overview-r" id="row_dmf_cep"><td><strong>DMF/CEP</strong></td><td id="dmf_cep"></td></tr>
        <tr class="product-overview-r" id="row_cofepris"><td><strong>COFEPRIS</strong></td><td id="cofepris"></td></tr>
        <tr class="product-overview-r" id="row_free_sale_certificate"><td><strong>Free Sale Certificate</strong></td><td id="free_sale_certificate"></td></tr>
      </table>
            </div>

            <div class="product-section">
                <div class="section-title">Logistics & Export</div>
                 <table>
        <tr class="product-overview-r" id="row_moq"><td><strong>MOQ</strong></td><td id="moq"></td></tr>
        <tr class="product-overview-r" id="row_shelf_life"><td><strong>Shelf Life</strong></td><td id="shelf_life"></td></tr>
        <tr class="product-overview-r" id="row_storage"><td><strong>Storage</strong></td><td id="storage"></td></tr>
        <tr class="product-overview-r" id="row_incoterms"><td><strong>Incoterms</strong></td><td id="incoterms"></td></tr>
        <tr class="product-overview-r" id="row_lead_time"><td><strong>Lead Time</strong></td><td id="lead_time"></td></tr>
      </table>
            </div>

            <div class="product-section">
                <div class="section-title">Documentation Available Upon Request</div>
                 <table>
        <tr class="product-overview-r" id="row_certi_analysis"><td><strong>Certificate of Analysis (PDF)</strong></td><td id="certi_analysis"></td></tr>
        <tr class="product-overview-r" id="row_gmp_certi"><td><strong>GMP Certificate (PDF)</strong></td><td id="gmp_certi"></td></tr>
        <tr class="product-overview-r" id="row_sds_certi"><td><strong>SDS-Safety Data Sheet (PDF)</strong></td><td id="sds_certi"></td></tr>
        <tr class="product-overview-r" id="row_ctd_certi"><td><strong>CTD Summary (PDF)</strong></td><td id="ctd_certi"></td></tr>
        
      </table>
            </div>
			
			          <div class="product-section-image">
    <img src="" alt="Product Image" id="image" width="100">
</div>

<div id="lightboxOverlay" class="lightbox-overlay" onclick="closeLightbox()">
    <img id="lightboxImage" class="lightbox-img" src="" alt="Zoomed Image">
</div>
				

            <div class="action-buttons">
                <button id="request-quotation">RFQ / Cotizar</button> 
                 <!-- <button>Add To Inquiry</button>
                <button>Compare Alternatives</button> -->
				
	<form id="pdfDownloadForm" method="POST" action="{{ route('download.pdf') }}">
    @csrf
    <input type="hidden" name="product_data" id="product_data_input">
    <button type="submit">Download PDF</button>
  </form>
            </div>
        </div>
        {{-- End Product Card --}}

    </x-slot>

    <!-- <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </x-slot> -->
</x-modal>





 <x-modal id="rfqPopup" title="RFQ / Cotizar" :scrollable="true" class="custom-rfq-modal">

    <x-slot name="body">
        <form id="rfqForm">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <input type="hidden" name="product_id" id="rfq_product_id">

            <label>PRODUCT NAME </label>
            <input type="text" name="product_name" id="rfq_product_name" readonly><br><br>

            <label>NAME <span style="color: red;">*</span> (Mandatory)</label>
            <input type="text" name="name"><br>
            <span class="text-danger" id="error_name"></span><br><br>
			
			<label>COMPANY NAME </label>
            <input type="text" name="company"><br>


            <label>EMAIL <span style="color: red;">*</span> (Mandatory)</label>
            <input type="email" name="email"><br>
            <span class="text-danger" id="error_email"></span><br><br>

            <label>Tele / WhatsApp</label>
            <input type="text" name="number" id="number"><br><br>
			 <span class="text-danger" id="error_number"></span><br><br>

            <label>Quantity</label>
            <input type="number" name="quantity"><br><br>

            <label>Destination Country <span style="color: red;">*</span> (Mandatory)</label>
            <select name="country" id="country" class="form-control">
                <option value="">-- Select Country --</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->country_id }}"
                        {{ old('country', $selectedCountry ?? '') == $country->country_id ? 'selected class=active' : '' }}>
                        {{ $country->short_name }}
                    </option>
                @endforeach
            </select>
            <span class="text-danger" id="error_country"></span><br><br>

            <div id="rfqMessage" style="margin-top: 10px; color: green; display: none;">
                Your Molecule Quote Was Submitted, Our staff will review and reply shortly.
            </div>

            <!-- <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" onclick="closeRfqPopup()" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div> -->
</x-slot>
        <x-slot name="footer">
      <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
      <button type="submit" class="btn btn-primary">Send For Quote</button>
    </x-slot>
        </form>
    <
</x-modal>

 <script>
  const BASE_URL = "{{ url('/') }}";
</script>
<script src="{{ asset('js/searchscript.js') }}"></script>

<script>
  const mainImage = document.getElementById('image');
  const lightbox = document.getElementById('lightboxOverlay');
  const lightboxImage = document.getElementById('lightboxImage');

  mainImage.addEventListener('click', function () {
    const src = mainImage.src;
    if (src) {
      lightboxImage.src = src;
      lightbox.classList.add('active');
    }
  });

  function closeLightbox() {
    lightbox.classList.remove('active');
    lightboxImage.src = ''; 
  }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('rfqPopup');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function closeRfqPopup() {
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.hide();
    }

    window.closeRfqPopup = closeRfqPopup; 

    document.getElementById('request-quotation')?.addEventListener('click', function () {
      if (!selectedProduct?.id) {
        alert("Product ID not found.");
        return;
      }

      document.getElementById('rfq_product_id').value = selectedProduct.id;
      document.getElementById('rfq_product_name').value = `${selectedProduct.generic_name} ${selectedProduct.strength}`;

      modalEl.classList.add('custom-rfq-modal');
      const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    });

   
    modalEl.addEventListener('shown.bs.modal', () => {
      const rfqForm = document.getElementById('rfqForm');
      if (!rfqForm.dataset.listenerAttached) {
        rfqForm.addEventListener('submit', function (e) {
          console.log("helloo");
          e.preventDefault();

          ['name', 'email', 'country'].forEach(field => {
            document.getElementById('error_' + field).innerText = '';
          });
		  
		   const number = document.getElementById('number').value.trim();
// const phoneNum = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
const phoneNum = /^\+?\d+$/;


if (number && !number.match(phoneNum)) {
    document.getElementById('error_number').innerText = 'Enter a valid digit number.';
    return false;
}

          const formData = new FormData(this);

          fetch(`${BASE_URL}/submit-rfq`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken
            },
            body: formData
          })
            .then(res => res.json())
            .then(data => {
  if (data.success) {
    document.getElementById('rfqMessage').style.display = 'block';

    setTimeout(() => {
      rfqForm.reset();
      document.getElementById('rfqMessage').style.display = 'none';
      closeRfqPopup();
    }, 3000);
  } else if (data.errors) {
    // Validation errors (422)
    Object.keys(data.errors).forEach(field => {
      const errorMessage = data.errors[field][0];
      const errorElement = document.getElementById('error_' + field);
      if (errorElement) {
        errorElement.innerText = errorMessage;
      }
    });
  } else if (data.message) {
    // Server error (500)
    alert("Server Error: " + data.message);
  }
})
            .catch(async (error) => {
  console.error('Unexpected Error:', error);
  alert('Something went wrong. Please try again later.');
});
        });

        rfqForm.dataset.listenerAttached = 'true';
      }
    });
  });
  
  document.getElementById('pdfDownloadForm').addEventListener('submit', function (e) {
  
  document.getElementById('product_data_input').value = JSON.stringify(selectedProduct);
});
</script>

@stop

