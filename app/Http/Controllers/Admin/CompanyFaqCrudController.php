<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompanyFaqRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Jobs\ImportCompanyFaqsFromCsv;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\companyFaqsExport;

/**
 * Class CompanyFaqCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CompanyFaqCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

  public function import(Request $request)
{
    $path = $request->file('csv')->store('company-faqs');
    $fullPath = storage_path('app/' . $path);

    // For now, run it directly
    (new ImportCompanyFaqsFromCsv($fullPath))->handle();

    \Alert::success('Import started! Data is being processed.')->flash();
    return back();
}

   public function export()
   {
    return Excel::download(new companyFaqsExport, 'company_faqs.csv');
  }


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CompanyFaq::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/company-faq');
        CRUD::setEntityNameStrings('company faq', 'company faqs');
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
		$this->crud->addButtonFromView('top', 'export_company_faqs', 'export_company_faqs_button', 'beginning');
$this->crud->addButtonFromView('top', 'import_company_faqs', 'import_company_faqs_button', 'beginning');

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
        CRUD::setValidation(CompanyFaqRequest::class);
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
