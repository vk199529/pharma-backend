<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Illuminate\Support\Str;
use App\Jobs\ProcessCsvJob;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
      use \App\Http\Controllers\Admin\Operations\BrowserPrintOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');

        $this->setupBrowserPrintDefaults();
        $this->setupBrowserPrintRoutes('product', config('backpack.base.route_prefix').'.product', self::class);

    }

               public function export()
                {
				return Excel::download(new ProductsExport, 'products.csv');
				}

                  public function exportCsv()
					{
						return Excel::download(new ProductsExport, 'products.csv', \Maatwebsite\Excel\Excel::CSV, [
							'Content-Type' => 'text/csv; charset=UTF-8',
						]);
					}
					
	public function importCsv(Request $request)
{
   
    $request->validate([
        'import_file' => 'required|mimes:csv,txt',
    ]);
 
    // Save the uploaded file to storage
    $file = $request->file('import_file');
    $fileName = $file->getClientOriginalName();
    $path = $file->storeAs('imports', $fileName);
    //  dd($path);
    // die("yes");

    
    ProcessCsvJob::dispatch(storage_path('app/' . $path));

  
Artisan::call('queue:work', ['--stop-when-empty' => true]);



    \Alert::success('Import started! Data is being processed.')->flash();
    return back();
}

			 
			 public function copy($id)
{
    $original = \App\Models\Product::findOrFail($id);

    $copied = $original->replicate();
    $copied->generic_name = $original->generic_name . ' (Copy)';
    $copied->save();

    \Alert::success('Copied successfully!')->flash();

    return redirect()->back();
}

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
 protected function setupListOperation()
{
	CRUD::addColumn([
    'name' => 'bulk_select',
    'label' => '<input type="checkbox" id="select-all">',
    'type' => 'closure',
    'escaped' => false,
    'function' => function ($entry) {

        return '<input type="checkbox"
                class="bulk-checkbox"
                name="selected_products[]"
                value="'.$entry->id.'">';
    },
]);

	$this->crud->addButtonFromView(
    'top', // 👈 top bar me show hoga
    'export_csv',
    'export_csv',
    'end'
);

$this->crud->addButtonFromView(
    'top',
    'import_csv_button',
    'import_csv_button',
    'beginning'
);

$this->crud->addButtonFromView(
    'top',
    'bulk_edit',
    'bulk_edit',
    'end'
);


    // IMPORTANT: disable responsive hiding
    $this->crud->enableResponsiveTable(false);
	
	 // 👇 ADD THIS
    $this->crud->addButtonFromView(
        'line',            
        'copy_button',   
        'copy_button',    
        'beginning'   
    );

    // PRIMARY COLUMNS
    CRUD::addColumn([
        'name'  => 'generic_name',
        'label' => 'Generic Name',
    ]);

    
	CRUD::addColumn([
        'name'  => 'brand_names',
        'label' => 'Brand Name',
    ]);

	CRUD::addColumn([
        'name'  => 'product_type',
        'label' => 'Product Type',
    ]);

   
    CRUD::addColumn([
        'name'     => 'price',
        'label'    => 'Price',
        'type'     => 'number',
        'prefix'   => '$ ',
        'decimals' => 2,
    ]);

    // ⭐ WAC (AFTER PRICE)
    CRUD::addColumn([
        'name'     => 'wac',
        'label'    => 'WAC',
        'type'     => 'number',
        'prefix'   => '$ ',
        'decimals' => 2,
    ]);

    // SECONDARY COLUMNS
    CRUD::addColumn([
        'name'  => 'strength',
        'label' => 'Strength',
    ]);

    CRUD::addColumn([
        'name'  => 'form',
        'label' => 'Form',
    ]);

    CRUD::addColumn([
        'name'  => 'manufacturer_name',
        'label' => 'Mfg. Name',
    ]);
}


    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);
        CRUD::setFromDb();
		
		CRUD::addField([
			'name' => 'product_type',
			'label' => 'Product Type',
			'type' => 'select_from_array',
			'options' => [
				'Originator Brand' => 'Originator Brand',
				'Generic' => 'Generic',
				'Biosimilar' => 'Biosimilar',
				'Biocomparable' => 'Biocomparable',
				'API Only' => 'API Only',
				'Special Import/Named Patient' => 'Special Import/Named Patient',
				'Govt Procurement Only' => 'Govt Procurement Only',
			],
			'allows_null' => true,
		 ]);
		
      CRUD::addField([
        'name' => 'price',
        'label' => 'Price',
        'type' => 'number',
		'prefix' => '$',
        'attributes' => ["step" => "0.01"],  
        'decimals' => 2,
        'dec_point' => '.',
        'thousands_sep' => ',',                     
     ]);
	
	  CRUD::addField([
       'name' => 'wac',
       'label' => 'WAC (Wholesale Acquisition Costs)',
       'type' => 'number',
       'prefix' => '$',
       'attributes' => [
        'step' => '0.01',
        ],
       'decimals' => 2,
       'dec_point' => '.',
       'thousands_sep' => ',',
     ]);

	 
         CRUD::addField([
			'label'     => 'Category',
			'type'      => 'select',
			'name'      => 'category_id',
			'entity'    => 'category', 
			'model'     => 'App\Models\Categories',
			'attribute' => 'name',
			'allows_null' => true,
			'hint' => 'Select the category for the product'
		]);

	
	CRUD::addField([
    'name' => 'image',
    'label' => 'Product Image',
    'type' => 'upload',
    'upload' => true,
    'disk' => 'public',
    'hint' => 'Only JPG and PNG images are allowed.',
    'wrapperAttributes' => [
        'enctype' => 'multipart/form-data',
    ],
    'attributes' => [
        'accept' => 'image/png,image/jpeg',
    ],
]);

CRUD::removeField('gmp_certificate');
CRUD::addField([
    'name'  => 'special_notes',
    'label' => 'Special Notes',
    'type'  => 'textarea', // regular textarea field
    'attributes' => [
        'id' => 'ckeditor', // give it an ID so CKEditor can hook into it
    ],
]);




   CRUD::modifyField('coa', [
    'label' => 'C.O.A.',
]);

   CRUD::modifyField('sds', [
    'label' => 'MSDS / SDS',
]);

   CRUD::modifyField('download_dossier', [
    'label' => 'Dossier Available',
]);

   CRUD::modifyField('rfq', [
    'label' => 'RFQ Available',
]);
// Status Switch
CRUD::addField([
    'name'      => 'status',
    'label'     => 'Product Status',
    'type'      => 'switch',
    'onLabel' => '✓',
    'offLabel' => '✕',
    'color'     => 'success',
    'default'   => 1
]);

CRUD::addField([
    'name'      => 'show_on_homepage',
    'label'     => 'Show on Homepage',
    'type'      => 'switch',
    'onLabel' => '✓',
    'offLabel' => '✕',
    'color'     => 'success',
    'default'   => 1
]);


  CRUD::modifyField('manufacturer_name', [
    'label' => 'Mfg. Name',
]);

  CRUD::modifyField('manufacturer_country', [
    'label' => 'Mfg. Country',
]);
CRUD::removeField('embedding_vector');
CRUD::removeField('perfex_id');

   CRUD::modifyField('incoterms', [
    'label' => 'Incoterm-HSN Code',
]);
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
{
    CRUD::addColumn(['name' => 'generic_name', 'label' => 'Generic Name','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'brand_names', 'label' => 'Brand Names','type'  => 'textarea']);
    CRUD::addColumn([
		'name'  => 'product_type',
		'label' => 'Product Type',
		'type'  => 'textarea',
    ]);
    CRUD::addColumn(['name' => 'form', 'label' => 'Form','type'  => 'textarea']);

    CRUD::addColumn([
        'label'     => "Category",
        'type'      => "select",
        'name'      => 'category_id', 
        'entity'    => 'category',   
        'attribute' => 'name',       
        'model'     => "App\Models\Categories",
		'type'      => 'textarea'
    ]);

    CRUD::addColumn(['name' => 'strength', 'label' => 'Strength','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'therapeutic_class', 'label' => 'Therapeutic Class','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'atc_code', 'label' => 'ATC Code','type' => 'textarea']);
    CRUD::addColumn(['name' => 'manufacturer_name', 'label' => 'Mfg. Name','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'manufacturer_country', 'label' => 'Mfg. Country','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'gmp_status', 'label' => 'GMP Status','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'dmf_number', 'label' => 'DMF Number','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'cofepris_status', 'label' => 'Cofepris Status','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'free_sale_certificate', 'label' => 'Free Sale Certificate','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'moq', 'label' => 'MOQ','type'  => 'textarea','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'shelf_life', 'label' => 'Shelf Life','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'storage', 'label' => 'Storage','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'incoterms', 'label' => 'Incoterm-HSN Code','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'lead_time', 'label' => 'Lead Time','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'coa', 'label' => 'C.O.A.','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'sds', 'label' => 'MSDS / SDS','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'ctd_summary', 'label' => 'CTD Summary','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'rfq', 'label' => 'RFQ Available','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'contact_regulatory_advisor', 'label' => 'Contact Regulatory Advisor','type'  => 'textarea']);
    CRUD::addColumn(['name' => 'download_dossier', 'label' => 'Dossier Available','type'  => 'textarea']);

    CRUD::addColumn([
        'name' => 'price',
        'label' => 'Price',
        'type' => 'number',
        'prefix' => '$',
		'decimals' => 2,
    'dec_point' => '.', 
    'thousands_sep' => ',',
	'type'  => 'textarea'
    ]);

    CRUD::addColumn([
    'name' => 'wac',
    'label' => 'WAC (Wholesale Acquisition Costs)',
    'type' => 'number',
    'prefix' => '$',
   
    'decimals' => 2,
    'dec_point' => '.', 
    'thousands_sep' => ',',
	'type'  => 'textarea'
]);

CRUD::addColumn([
    'name'     => 'special_notes',
    'label'    => 'Special Notes',
    'type'     => 'closure',
    'escaped'  => false,
    'function' => function($entry) {
        // We decode the entities so &trade; becomes ™, 
        // but we DON'T strip_tags so <strong> stays <strong>
        return html_entity_decode($entry->special_notes, ENT_QUOTES, 'UTF-8');
    },
]);

    CRUD::addColumn([
        'name'  => 'image',
        'label' => 'Product Image',
        'type'  => 'image',
        'prefix' => 'storage/',
    ]);

    CRUD::addColumn([
        'name'    => 'status',
        'label'   => 'Status',
        'type'    => 'boolean',
        'options' => [0 => 'Draft', 1 => 'Active'],
    ]);
	
	  CRUD::addColumn([
        'name'    => 'show_on_homepage',
        'label'   => 'Show on Homepage',
        'type'    => 'boolean',
        'options' => [0 => 'Draft', 1 => 'Active'],
    ]);
	
/* 	CRUD::addColumn([
    'name' => 'perfex_id',
    'label' => 'Perfex ID'
]); */

}



}
