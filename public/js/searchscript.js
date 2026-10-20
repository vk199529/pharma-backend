 const base_path = '{{ url('/') }}';

const askBtn = document.getElementById('askBtn');

const searchInput = document.getElementById('searchInput');
const suggestionsDiv = document.getElementById('suggestions');
const productCardDiv = document.getElementById('pro-card');
const resultsDiv = document.getElementById('results');

  function updatePlaceholder() {
  const selection = queryType.value;
  let placeholder = '';
  switch (selection) {
    case 'product':
      placeholder = 'e.g. Do you stock Insulin Glargine 100IU/ml in vial or pen?';
      break;
    case 'company':
      placeholder = 'e.g. Please send your COFEPRIS and ISO/GMP certifications.';
      break;
    case 'logistics':
      placeholder = 'e.g. What Incoterms do you offer for cold-chain shipments to Brazil?';
      break;
    case 'other':
      placeholder = 'e.g. We are interested in becoming a regional distributor.';
      break;
  }
  searchInput.placeholder = placeholder;
}

   const queryType = document.getElementById('queryType');
    console.log("queryType");

jQuery('body').on('click', '.suggestion-item', function() {
		jQuery('.suggestion-item').removeClass('active');
		jQuery(this).addClass('active');
});

let remainingProducts = [];
askBtn.addEventListener('click', function () {
  const query = searchInput.value.trim();
  const category = queryType.value;
  if (!query) return;

  // Show animated searching state
  suggestionsDiv.innerHTML = `
    <div style="display:flex;align-items:center;gap:8px;">
      <div class="rotating-emoji">⏳</div>
      <span>Searching...</span>
    </div>
  `;


  let endpoint = '';
  if (category === 'product') {
    endpoint = `/products-smart-search?ss=${encodeURIComponent(query)}`;
  } else if (category === 'other') {
    endpoint = `/faq/search?question=${encodeURIComponent(query)}`;
  } else if (category === 'company'){
     endpoint = `/company-faq-search?question=${encodeURIComponent(query)}`;
  } else if (category === 'logistics') {
  endpoint = `/shipping-faq-search?question=${encodeURIComponent(query)}`;
}


  fetch(`${BASE_URL}${endpoint}`)
    .then(res => res.json())
    .then(data => {
      suggestionsDiv.innerHTML = '';
      remainingProducts = [];

      // ✅ PRODUCT MODE
      if (category === 'product' && data.products) {
        if (data.answer) {
          const answerBox = document.createElement('pre');
          answerBox.className = 'ai-response';
          answerBox.textContent = data.answer;
          suggestionsDiv.appendChild(answerBox);
        }

        const topThree = data.products.slice(0, 3);
        remainingProducts = data.products.slice(3);

        const heading = document.createElement('div');
        heading.className = 'product-heading';
        heading.textContent = 'Here is a list of products relevant to your search';
        suggestionsDiv.appendChild(heading);

        topThree.forEach(p => {
          const div = document.createElement('div');
          div.className = 'suggestion-item';
          div.textContent = `${p.generic_name} / ${p.brand_names} / ${p.therapeutic_class})`;
          div.onclick = () => displayResults(p);
          suggestionsDiv.appendChild(div);
        });

        if (remainingProducts.length > 0) {
          const showMoreBtn = document.createElement('button');
          showMoreBtn.className = 'show-more-btn';
          showMoreBtn.textContent = `Show More Products (${remainingProducts.length})`;
          showMoreBtn.onclick = showRemainingProducts;
          suggestionsDiv.appendChild(showMoreBtn);
        }

        return;
      }

      // ✅ FAQ MODE
      if (category === 'other' && data.faqs) {
        if (data.answer) {
          const answerBox = document.createElement('pre');
          answerBox.className = 'ai-response';
          answerBox.textContent = data.answer;
          suggestionsDiv.appendChild(answerBox);
        }

       /*  data.faqs.forEach(faq => {
          const div = document.createElement('div');
          div.className = 'suggestion-item';
          div.innerHTML = `<strong>Q:</strong> ${faq.question}<br><strong>A:</strong> ${faq.answer}`;
          suggestionsDiv.appendChild(div);
        }); */

        return;
      }


         // ✅ company MODE
      if (category === 'company' && data.faqs) {
        if (data.answer) {
          const answerBox = document.createElement('pre');
          answerBox.className = 'ai-response';
          answerBox.textContent = data.answer;
          suggestionsDiv.appendChild(answerBox);
        }

      /*   data.faqs.forEach(faq => {
          const div = document.createElement('div');
          div.className = 'suggestion-item';
          div.innerHTML = `<strong>Q:</strong> ${faq.question}<br><strong>A:</strong> ${faq.answer}`;
          suggestionsDiv.appendChild(div);
        }); */

        return;
      }
	  
	       // ✅ LOGISTICS FAQ MODE
if (category === 'logistics' && data.faqs) {
  if (data.answer) {
    const answerBox = document.createElement('pre');
    answerBox.className = 'ai-response';
    answerBox.textContent = data.answer;
    suggestionsDiv.appendChild(answerBox);
  }

/*   data.faqs.forEach(faq => {
    const div = document.createElement('div');
    div.className = 'suggestion-item';
    div.innerHTML = `<strong>Q:</strong> ${faq.question}<br><strong>A:</strong> ${faq.answer}`;
    suggestionsDiv.appendChild(div);
  }); */

  return;
}

      // If no match
      suggestionsDiv.innerHTML = '<div class="suggestion-item">No results found.</div>';
    })
    .catch(err => {
      console.error(err);
      suggestionsDiv.innerHTML = '❌ Error fetching data.';
    });
});
/* askBtn.addEventListener('click', function () {
      const query = searchInput.value.trim();
    if (!query) return;
 suggestionsDiv.innerHTML = `
  <div style="display:flex;align-items:center;gap:8px;">
    <div class="rotating-emoji">⏳</div>
    <span>Searching...</span>
  </div>
`;


console.log(query);

console.log('event triggered');

	  getfilteredproducts(query);
      suggestionsDiv.style.display = 'block';
    });

function getfilteredproducts(query) {
    const xhr = new XMLHttpRequest();
    const url = `${BASE_URL}/products-smart-search?ss=${encodeURIComponent(query)}`;
    xhr.open("GET", url, true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                suggestionsDiv.innerHTML = '';
                remainingProducts = [];

                if (response.products && response.products.length > 0) {
                    const topThree = response.products.slice(0, 3);
                    remainingProducts = response.products.slice(3);
                    console.log("topThree-->",topThree);
                    console.log("remainingProducts-->",remainingProducts);


                     // Show AI answer
                if (response.answer) {
                    const answerBox = document.createElement('pre'); 
                    answerBox.className = 'ai-response';
                    answerBox.textContent = response.answer;
                    suggestionsDiv.appendChild(answerBox);
                }
                   
						 const productHeading = document.createElement('div');
						productHeading.className = 'product-heading';
						productHeading.innerText = 'Here is a list of products relevant to your search';
						suggestionsDiv.appendChild(productHeading);


              //topThree
                    topThree.forEach(product => {
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        div.textContent = `${product.generic_name} / ${product.brand_names} / ${product.therapeutic_class}s`;
                        div.onclick = () => displayResults(product);
                        suggestionsDiv.appendChild(div);
                    });
               //remainingProducts
                    if (remainingProducts.length > 0) {
                        const showMoreBtn = document.createElement('button');
                        showMoreBtn.textContent = `Show More Products (${remainingProducts.length})`;
                        showMoreBtn.className = 'show-more-btn';
                        showMoreBtn.onclick = showRemainingProducts;
                        suggestionsDiv.appendChild(showMoreBtn);
                    }
                } else {
                    suggestionsDiv.innerHTML = '<div class="suggestion-item">No products found.</div>';
                }

               

                suggestionsDiv.style.display = 'block';
            } catch (e) {
                console.error('Invalid JSON:', e);
                suggestionsDiv.innerHTML = '⚠️ Error while parsing response.';
            }
        }
    };

    xhr.send();
} */

function showRemainingProducts() {
    remainingProducts.forEach(product => {
        const div = document.createElement('div');
        div.className = 'suggestion-item';
        div.textContent = `${product.generic_name} / ${product.brand_names} / ${product.therapeutic_class}`;
        div.onclick = () => displayResults(product);
        suggestionsDiv.appendChild(div);
    });

    // Remove the "Show More" button after click
    const button = document.querySelector('.show-more-btn');
    if (button) button.remove();
}
/* function getfilteredproducts(query){
	let xhr = new XMLHttpRequest();
	// Making our connection  
	let url = `${BASE_URL}/products-smart-search?ss=${encodeURIComponent(query)}`;
	xhr.open("GET", url, true);
	// function execute after request is successful 
    xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
        try {
            const response = JSON.parse(xhr.responseText);

            console.log(response);
            // If the response is an array of products

            if (Array.isArray(response.products)) {

				suggestionsDiv.innerHTML = "";

        var total = response.products.length

        console.log("total-->",total);

                if(total > 0){

                response.products.forEach(product => {

					  const div = document.createElement('div');

						div.className = 'suggestion-item';

						div.textContent = `${product.generic_name} / ${product.brand_names}`;

						div.onclick = () => displayResults(product);

						suggestionsDiv.appendChild(div);

                });

              } else {



                const customProText = document.createElement('div');

                customProText.textContent = 'No Products Found';

                 customProText.className = 'product-filter';

                 suggestionsDiv.appendChild(customProText);

              }

				suggestionsDiv.style.display = 'block';

            } else if (response.status === 'error') {

                console.warn('Error:', 'No product found');

            }

        } catch (e) {

            console.error('Invalid JSON:', e);

        } 

    } 

};

    // Sending our request 

    xhr.send();

	} */

let selectedProduct = {};
    function displayResults(items) {
  selectedProduct = items;
const modalId = 'productModal';
var med_strength = "";
if(items.strength != null && items.strength.trim().length > 0){
  med_strength = "– "+items.strength;
}  
document.getElementById(`${modalId}Label`).innerText = `${items.generic_name} ${med_strength}`;

  // Update modal content
  // document.getElementById('product_heading_text').innerText = `${items.generic_name} – ${items.strength}`;
  
jQuery('.product-overview-r').hide();

document.getElementById('generic_name').innerText = items.generic_name || '';

if(items.generic_name != null && items.generic_name.trim().length > 0){
document.getElementById('generic_name').innerText = items.generic_name || '';
jQuery('#row_generic_name').show();
}

if(items.brand_names != null && items.brand_names.trim().length > 0){
document.getElementById('brand_names').innerText = items.brand_names || '';
jQuery('#row_brand_names').show();
}

if(items.form != null && items.form.trim().length > 0){
document.getElementById('form').innerText = items.form || '';
  jQuery('#row_form').show();
}

if(items.strength != null && items.strength.trim().length > 0){
document.getElementById('strength').innerText = items.strength || '';
  jQuery('#row_strength').show();
}
if(items.therapeutic_class != null && items.therapeutic_class.trim().length > 0){
document.getElementById('therapeutic_class').innerText = items.therapeutic_class || '';
  jQuery('#row_therapeutic_class').show();
}
if(items.atc_code != null && items.atc_code.trim().length > 0){
document.getElementById('atc_code').innerText = items.atc_code || '';
  jQuery('#row_atc_code').show();
}


if(items.manufacturer_name != null && items.manufacturer_name.trim().length > 0){
document.getElementById('manufacturer').innerText = items.manufacturer_name || '';
  jQuery('#row_manufacturer').show();
}
if(items.gmp_status != null && items.gmp_status.trim().length > 0){
document.getElementById('gmp_compliance').innerText = items.gmp_status || '';
  jQuery('#row_gmp_compliance').show();
}
if(items.dmf_cep != null && items.dmf_cep.trim().length > 0){
document.getElementById('dmf_cep').innerText = items.dmf_cep || '';
  jQuery('#row_dmf_cep').show();
}
if(items.cofepris_status != null && items.cofepris_status.trim().length > 0){
document.getElementById('cofepris').innerText = items.cofepris_status || '';
  jQuery('#row_cofepris').show();
}
if(items.free_sale_certificate != null && items.free_sale_certificate.trim().length > 0){
document.getElementById('free_sale_certificate').innerText = items.free_sale_certificate || '';
  jQuery('#row_free_sale_certificate').show();
}

if(items.moq != null && items.moq.trim().length > 0){
document.getElementById('moq').innerText = items.moq || '';
  jQuery('#row_moq').show();
}
if(items.shelf_life != null && items.shelf_life.trim().length > 0){
document.getElementById('shelf_life').innerText = items.shelf_life || '';
  jQuery('#row_shelf_life').show();
}
if(items.storage != null && items.storage.trim().length > 0){
document.getElementById('storage').innerText = items.storage || '';
  jQuery('#row_storage').show();
}
if(items.incoterms != null && items.incoterms.trim().length > 0){
document.getElementById('incoterms').innerText = items.incoterms || '';
  jQuery('#row_incoterms').show();
}
if(items.lead_time != null && items.lead_time.trim().length > 0){
document.getElementById('lead_time').innerText = items.lead_time || '';
  jQuery('#row_lead_time').show();
}
if(items.lead_time != null && items.lead_time.trim().length > 0){
document.getElementById('lead_time').innerText = items.lead_time || '';
  jQuery('#row_lead_time').show();
}

if(items.coa != null && items.coa.trim().length > 0){
document.getElementById('certi_analysis').innerText = items.coa || '';
  jQuery('#row_certi_analysis').show();
}

if(items.gmp_certificate != null && items.gmp_certificate.trim().length > 0){
document.getElementById('gmp_certi').innerText = items.gmp_certificate || '';
  jQuery('#row_gmp_certi').show();
}

if(items.sds != null && items.sds.trim().length > 0){
document.getElementById('sds_certi').innerText = items.sds || '';
  jQuery('#row_sds_certi').show();
}

if(items.ctd_summary != null && items.ctd_summary.trim().length > 0){
document.getElementById('ctd_certi').innerText = items.ctd_summary || '';
  jQuery('#row_ctd_certi').show();
}



  const imageElement = document.getElementById('image');
  if (items.image) {
    imageElement.src = BASE_URL+'/storage/' + items.image + '?t=' + new Date().getTime();
    imageElement.style.display = 'block';
  } else {
    imageElement.src = '';
    imageElement.style.display = 'none';
  }

  // Open Bootstrap modal
  const modal = new bootstrap.Modal(document.getElementById('productModal'));
  modal.show();
}
