<!DOCTYPE html>
<html>

<head>
    <!-- <title>{{ $title }}</title> -->
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }        h1 { margin-bottom: 20px; }        .field { margin-bottom: 10px; }        .label { font-weight: bold; }        .field {    display: flex;    justify-content: space-between;    border-bottom: 1px solid #ddd;    padding: 12px;}
    </style>
</head>

<body>
    <!-- <h1>{{ $title }}</h1> -->
    <!-- // echo '<pre>';// print_r($entry);// echo '</pre>'; -->
    <!-- <div class="field"><span class="label">ID:</span> {{ $entry->id }}</div> -->
    <div class="field"><span class="label">Generic Name:</span> {{ $entry->generic_name }}</div>
    <div class="field"><span class="label">Brand Names:</span> {{ $entry->brand_names }}</div>
	<div class="field"><span class="label">Product Type:</span> {{ $entry->product_type }}</div>
    <div class="field"><span class="label">Form:</span> {{ $entry->form }}</div>
    <div class="field"><span class="label">Strength:</span> {{ $entry->strength }}</div>
    <div class="field"><span class="label">Therapeutic Class:</span> {{ $entry->therapeutic_class }}</div>
    <div class="field"><span class="label">ATC Code:</span> {{ $entry->atc_code }}</div>
    <div class="field"><span class="label">Manufacturer Name:</span> {{ $entry->manufacturer_name }}</div>
    <div class="field"><span class="label">Manufacturer Country:</span> {{ $entry->manufacturer_country }}</div>
    <div class="field"><span class="label">GMP Status:</span> {{ $entry->gmp_status }}</div>
    <div class="field"><span class="label">DMF Number:</span> {{ $entry->dmf_number }}</div>
    <div class="field"><span class="label">COFEPRIS Status:</span> {{ $entry->cofepris_status }}</div>
    <div class="field"><span class="label">Free Sale Certificate:</span> {{ $entry->free_sale_certificate }}</div>
    <div class="field"><span class="label">MOQ:</span> {{ $entry->moq }}</div>
    <div class="field"><span class="label">Shelf Life:</span> {{ $entry->shelf_life }}</div>
    <div class="field"><span class="label">Storage:</span> {{ $entry->storage }}</div>
    <div class="field"><span class="label">Incoterms:</span> {{ $entry->incoterms }}</div>
    <div class="field"><span class="label">Lead Time:</span> {{ $entry->lead_time }}</div>
    <div class="field"><span class="label">COA:</span> {{ $entry->coa }}</div>
    <div class="field"><span class="label">SDS:</span> {{ $entry->sds }}</div>
    <div class="field"><span class="label">CTD Summary:</span> {{ $entry->ctd_summary }}</div>
    <div class="field"><span class="label">RFQ:</span> {{ $entry->rfq }}</div>
    <div class="field"><span class="label">Contact Regulatory Advisor:</span> {{ $entry->contact_regulatory_advisor }}</div>
    <div class="field"><span class="label">Download Dossier:</span> {{ $entry->download_dossier }}</div>
    <!-- <div class="field"><span class="label">Created At:</span> {{ $entry->created_at }}</div>    <div class="field"><span class="label">Updated At:</span> {{ $entry->updated_at }}</div> -->
    <script type="text/javascript">
        window.print();        window.onafterprint = function () {            window.close();        };
    </script>
</body>

</html>