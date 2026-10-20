<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ShippingFaqRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Jobs\ImportShippingFaqsFromCsv;
use App\Exports\ShippingFaqsExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ShippingFaqCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ShippingFaqCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;



public function import(Request $request)
{
 
    if (!$request->hasFile('csv')) {
        \Alert::error('No CSV file uploaded.')->flash();
        return back();
    }

    $file = $request->file('csv');
       

    if (!$file->isValid()) {
        \Alert::error('Uploaded CSV file is not valid.')->flash();
        return back();
    }

    $path = $file->store('shipping-faqs');
    
    $fullPath = storage_path('app/' . $path);


    // \App\Jobs\ImportShippingFaqsFromCsv::dispatch($fullPath);
    (new \App\Jobs\ImportShippingFaqsFromCsv($fullPath))->handle();


    \Alert::success('Import started! Your data is being processed.')->flash();
    return back();
}

public function export()
{
    return Excel::download(new ShippingFaqsExport, 'shipping_faqs.csv');
}

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ShippingFaq::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shipping-faq');
        CRUD::setEntityNameStrings('shipping faq', 'shipping faqs');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
       // CRUD::setFromDb(); // set columns from db columns.
		
		 CRUD::addColumn([
        'name' => 'question',
        'type' => 'text',
    ]);

    CRUD::addColumn([
        'name' => 'answer',
        'type' => 'text',
    ]);
	
	$this->crud->addButtonFromView('top', 'export_shipping_faq', 'export_shipping_faq_button', 'beginning');
		$this->crud->addButtonFromView('top', 'import_shipping_faq', 'import_shipping_faq_button', 'beginning');



        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ShippingFaqRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
		CRUD::removeField('embedding_vector');

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

     CRUD::addColumn([
        'name' => 'question',
        'type' => 'text',
		'limit' => 9999, 
    ]);

    CRUD::addColumn([
        'name' => 'answer',
        'type' => 'text',
		'limit' => 9999, 
    ]);

       CRUD::removeColumn('embedding_vector');
    }
}
