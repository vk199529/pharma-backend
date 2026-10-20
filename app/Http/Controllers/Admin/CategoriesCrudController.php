<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoriesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoriesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoriesCrudController extends CrudController
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
    public function setup()
    {
        CRUD::setModel(\App\Models\Categories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/categories');
        CRUD::setEntityNameStrings('categories', 'categories');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::setFromDb(); // set columns from db columns.

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
        CRUD::setValidation(CategoriesRequest::class);
      //  CRUD::setFromDb(); // set fields from db columns.

CRUD::addField([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'text',
    ]);

    CRUD::addField([
        'name' => 'description',
        'label' => 'Description',
        'type' => 'textarea',
    ]);
   
     CRUD::addField([
    'name' => 'image',
    'label' => 'Category Image',
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


        CRUD::addField([
            'name' => 'parent_id',
            'label' => 'Parent Category',
            'type' => 'select',
            'entity' => 'parent',
            'attribute' => 'name',
            'model' => "App\Models\Categories",
            'allows_null' => true,
        ]);



  CRUD::addField([
    'name' => 'slug',
    'label' => 'Slug',
    'type' => 'text',
    'hint' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</br>
    It will be generated automatically if you leave this field empty.',
    
]);

  CRUD::addField([
    'name' => 'sort_order',
    'label' => 'Sort order',
    'type' => 'number',

]);
 
/*   CRUD::addField([
    'name' => 'status',
    'label' => 'Status',
    'type' => 'checkbox',

]); */
CRUD::addField([
    'name'      => 'status',
    'label'     => 'Status',
    'type'      => 'switch',
     'onLabel' => '✓',
    'offLabel' => '✕',
    'color'     => 'success', 
	 'default'   => 1,
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
       // CRUD::setFromDb();

       CRUD::addColumn([
        'name' => 'name',
        'label' => 'Name',
        'type' => 'text',
    ]);

    CRUD::addColumn([
        'name' => 'description',
        'label' => 'Description',
        'type' => 'textarea',
    ]);
   
     CRUD::addColumn([
    'name' => 'image',
    'label' => 'Category Image',
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


        CRUD::addColumn([
            'name' => 'parent_id',
            'label' => 'Parent Category',
            'type' => 'select',
            'entity' => 'parent',
            'attribute' => 'name',
            'model' => "App\Models\Categories",
            'allows_null' => true,
        ]);



  CRUD::addColumn([
    'name' => 'slug',
    'label' => 'Slug',
    'type' => 'text',
    'hint' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</br>
    It will be generated automatically if you leave this field empty.',
    
]);

  CRUD::addColumn([
    'name' => 'sort_order',
    'label' => 'Sort order',
    'type' => 'number',

]);
 


        CRUD::addColumn([
    'name'  => 'status',
    'label' => 'Status',
    'type'  => 'boolean',
    'options' => [0 => 'Draft', 1 => 'Active'],
]);

    }
	
}
