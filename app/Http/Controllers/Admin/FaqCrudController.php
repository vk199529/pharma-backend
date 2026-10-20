<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FaqRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Jobs\ImportFaqsFromCsv;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use App\Exports\FaqsExport;

/**
 * Class FaqCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FaqCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
	 
	 public function export()
{
    return Excel::download(new FaqsExport, 'faqs.csv');
}


	 public function import(Request $request)
{
    $path = $request->file('csv')->store('faq-imports');
    $fullPath = storage_path('app/' . $path);

    // ImportFaqsFromCsv::dispatch($fullPath);
    (new \App\Jobs\ImportFaqsFromCsv($fullPath))->handle();


    \Alert::success('Import started! Data is being processed.')->flash();
    return back();
}


    public function setup()
    {
        CRUD::setModel(\App\Models\Faq::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/faq');
        CRUD::setEntityNameStrings('faq', 'faqs');
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
 /*   CRUD::addColumn([
        'label' => 'Category',
        'type' => 'select',
        'name' => 'faq_category_id', // the column in your db
        'entity' => 'category', // the relationship method in your model
        'model' => 'App\Models\FaqCategory',
        'attribute' => 'name', // the column from the related model
    ]); */

    CRUD::addColumn([
        'name' => 'question',
        'type' => 'text',
    ]);

    CRUD::addColumn([
        'name' => 'answer',
        'type' => 'text',
    ]);
	$this->crud->addButtonFromView('top', 'export_faqs', 'export_faqs_button', 'beginning');

	 CRUD::addButtonFromView('top', 'import_faq', 'import_faq_button', 'beginning');
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
        CRUD::setValidation(FaqRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
/* CRUD::addField([
    'label'     => "Category",
    'type'      => 'select',
    'name'      => 'faq_category_id',
    'entity'    => 'category',
    'model'     => "App\Models\FaqCategory",
    'attribute' => 'name',
]); */

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
/*   CRUD::addColumn([
        'label' => 'Category',
        'type' => 'select',
        'name' => 'faq_category_id', // the column in your db
        'entity' => 'category', // the relationship method in your model
        'model' => 'App\Models\FaqCategory',
        'attribute' => 'name', // the column from the related model
    ]); */

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
	
/* 	CRUD::addColumn([
        'name' => 'embedding_vector',
        'type' => 'json',
    ]); */

}
}
