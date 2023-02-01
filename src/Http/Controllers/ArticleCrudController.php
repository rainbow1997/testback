<?php

namespace Rainbow1997\Testback\Http\Controllers;

use  \Rainbow1997\Testback\Http\Requests\ArticleRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ArticleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ArticleCrudController extends CrudController
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
        CRUD::setModel(\Rainbow1997\Testback\Models\Article::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/article');
        CRUD::setEntityNameStrings('article', 'articles');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->setupFilters();
        $this->crud->enableExportButtons();


        CRUD::column('title');
        CRUD::column('description');
        CRUD::addColumn([   // 1-n relationship
            'label' => 'Category', // Table column heading
            'type' => 'select',
            'name' => 'select', // the column that contains the ID of that connected entity;
            'entity' => 'category', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => Rainbow1997\Http\Controllers\CategoryCrudController::class, // foreign key model
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('category/' . $related_key . '/show');
                },
            ],
        ]);

        CRUD::column('created_at')->type('datetime');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
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
        CRUD::setValidation(\Rainbow1997\Testback\Http\Requests\ArticleRequest::class);
        CRUD::field('title');
        CRUD::field('description');
        CRUD::addField([
            'label'     => "Category",
            'type'      => 'select',
            'name'      => 'category_id',

            'entity'    => 'category',

            'model'     => \Rainbow1997\Testback\Models\Category::class,
            'attribute' => 'title',
            ]);
        CRUD::field('content')->type('tinymce');
        CRUD::field('created_at')->type('datetime');


        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupFilters()
    {
        $this->crud->addFilter(
            [ // select2_ajax filter
                'name' => 'categoryFilter',
                'type' => 'select2_ajax',
                'label' => 'Categories',
                'placeholder' => 'Pick a category',
                'method' => 'POST',
            ],
            url(route('categorySearch')), // the ajax route
            function ($value) { // if the filter is active
                $this->crud->addClause('where', 'category_id', $value);
            }
        );
        $this->crud->addFilter(
            [ // daterange filter
                'type' => 'date_range',
                'name' => 'date_range',
                'label' => 'Date range',
                // 'date_range_options' => [
                'format' => 'YYYY-MM-DD',
                 'locale' => ['format' => 'YYYY-MM-DD'],
                // 'showDropdowns' => true,
                // 'showWeekNumbers' => true
                // ]
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to);
            }
        );
    }
    protected function setupShowOperation(){
        CRUD::column('title');
        CRUD::column('description');
        CRUD::column('category_id');
        CRUD::column('content')->type('text')->escaped(false);
        CRUD::column('created_at')->type('datetime');

    }
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
