<html>
    <head>
        <style>
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -60px;
                height: 50px;
                color: white;
                line-height: 35px;
				left:0;
				right:0;
				margin:auto;
            }

            footer {
                position: fixed;
                bottom: -60px;
                height: 50px;
                background-color: #752727;
                color: white;
                text-align: center;
                line-height: 35px;
            }
			
			body { font-family: DejaVu Sans, sans-serif; font-size: 14px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
		table td{ width:50%; }
		
        td, th { border: 1px solid #ccc; padding: 8px; }
        .section-title { font-weight: bold; font-size: 16px; margin-top: 20px; padding-bottom:10px}
        ul { margin: 0; padding-left: 18px; }
        img { margin-top: 10px; }
        </style>
    </head>
    <body>
        <header style="text-align: center;">
			<img src="{{ public_path('images/Logo_Panamfarma_horizontal.png') }}" alt="Panam Farma Logo" style="height: 50px; ">
		</header>

       <footer style="width: 708px; padding: 10px 0 20px; background: #fff; ">
  <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
    <tr>
      <td style="text-align: left; width: 50%; border: none; color:#000;">
        <strong>Pan Am Farma</strong>
      </td>
      <td style="text-align: right; width: 50%; border: none;">
        <a href="mailto:Info@panamfarma.com" style="text-decoration: none; color: #000;">Info@panamfarma.com</a> |
        <a href="https://www.panamfarma.com" style="text-decoration: none; color: #000;">www.panamfarma.com</a>
      </td>
    </tr>
  </table>
</footer>

        <main>
             <div class="product-section">
        <div style="text-align: center;" class="section-title">Product Overview</div>
        <table>
             @if(!empty($product->generic_name))
            <tr>
                <td><strong>Generic Name</strong></td>
                <td>{{ $product->generic_name }}</td>
            </tr>
            @endif
            @if(!empty($product->brand_name))
            <tr>
                <td><strong>Brand Name(s)</strong></td>
               <td>{{ $product->brand_name }}</td>
            </tr>
            @endif
			
			 @if(!empty($product->product_type))
            <tr>
                <td><strong>Product Type</strong></td>
               <td>{{ $product->product_type }}</td>
            </tr>
            @endif
			
            @if(!empty($product->form))
            <tr>
                <td><strong>Form</strong></td>
               <td>{{ $product->form }}</td>
            </tr>
            @endif
            @if(!empty($product->strength))
            <tr>
                <td><strong>Strength</strong></td>
                <td>{{ $product->strength }}</td>
            </tr>
            @endif
             @if(!empty($product->therapeutic_class))
            <tr>
                <td><strong>Therapeutic Class</strong></td>
               <td>{{ $product->therapeutic_class }}</td>
            </tr>
             @endif
             @if(!empty($product->atc_code))
            <tr>
                <td><strong>ATC Code</strong></td>
               <td>{{ $product->atc_code }}</td>
            </tr>
             @endif
        </table>
    </div>

    <div class="product-section">
        <div style="text-align: center;" class="section-title">Manufacturing & Regulatory</div>
        <table>
             @if(!empty($product->manufacturer))
            <tr>
                <td><strong>Manufacturer</strong></td>
                 <td>{{ $product->manufacturer }}</td>
            </tr>
             @endif
             @if(!empty($product->gmp_compliance))
            <tr>
                <td><strong>GMP Compliance</strong></td>
               <td>{{ $product->gmp_compliance }}</td>
            </tr>
             @endif
              @if(!empty($product->dmf_cep))
            <tr>
                <td><strong>DMF/CEP</strong></td>
                <td>{{ $product->dmf_cep }}</td>
            </tr>
             @endif
              @if(!empty($product->cofepris))
            <tr>
                <td><strong>COFEPRIS</strong></td>
                 <td>{{ $product->cofepris }}</td>
            </tr>
                @endif
             @if(!empty($product->free_sale_certificate))
            <tr>
                <td><strong>Free Sale Certificate</strong></td>
                 <td>{{ $product->free_sale_certificate }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="product-section">
        <div style="text-align: center;" class="section-title">Logistics & Export</div>
        <table>
             @if(!empty($product->moq))
            <tr>
                <td><strong>MOQ</strong></td>
                 <td>{{ $product->moq }}</td>
            </tr>
             @endif
               @if(!empty($product->shelf_life))
            <tr>
                <td><strong>Shelf Life</strong></td>
               <td>{{ $product->shelf_life }}</td>
            </tr>
             @endif
              @if(!empty($product->storage))
            <tr>
                <td><strong>Storage</strong></td>
                <td>{{ $product->storage }}</td>
            </tr>
            @endif
            @if(!empty($product->incoterms))
            <tr>
                <td><strong>Incoterms</strong></td>
                <td>{{ $product->incoterms }}</td>
            </tr>
            @endif
            @if(!empty($product->lead_time))
            <tr>
                <td><strong>Lead Time</strong></td>
                <td>{{ $product->lead_time }}</td>
            </tr>
            @endif
			@if(!empty($product->image) && file_exists(public_path('storage/' . $product->image)))
         <tr>
            <td><strong>Image</strong></td>
            <td>
    <img src="{{ public_path('storage/' . $product->image) }}" alt="Product Image" width="100">
</td>
    </tr>
@endif
        </table>
    </div>
        </main>
    </body>
</html>