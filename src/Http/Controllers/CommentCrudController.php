<?php

namespace Rainbow1997\Testback\Http\Controllers;

use App\Models\User;
use Rainbow1997\Testback\Http\Requests\CommentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Rainbow1997\Testback\Models\Article;
use Rainbow1997\Testback\Models\Comment;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
//    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::setModel(Comment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/comment');
        CRUD::setEntityNameStrings('comment', 'comments');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addButtonFromModelFunction('line', 'Target Content', 'getTarget', 'beginning');
        $this->setupFilters();
        $this->crud->enableExportButtons();
        CRUD::column('fullname');
        CRUD::column('content')->type('text');

        CRUD::addColumn([   // 1-n relationship
            'label' => 'User', // Table column heading
            'type' => 'select',
            'name' => 'select', // the column that contains the ID of that connected entity;
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'email', // foreign key attribute that is shown to user
            'model' => User::class, // foreign key model
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user/' . $related_key . '/show');
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
    protected function setupShowOperation(){
        CRUD::column('fullname');
        CRUD::column('content')->type('text');
        CRUD::addColumn([   // 1-n relationship
            'label' => 'User', // Table column heading
            'type' => 'select',
            'name' => 'select', // the column that contains the ID of that connected entity;
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => 'email', // foreign key attribute that is shown to user
            'model' => User::class, // foreign key model
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user/' . $related_key . '/show');
                },
            ],
        ]);        CRUD::column('created_at')->type('datetime');
    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {

        CRUD::setValidation(\Rainbow1997\Testback\Http\Requests\CommentRequest::class);
        CRUD::field('fullname');
        CRUD::field('content')->type('textarea');



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
                $this->crud->query = $this->crud->query->whereHas('commentable',function($query) use($value){
                    return $query->where('category_id', $value);
                });
            }
        );

        $this->crud->addFilter(
            [ // select2_ajax filter
                'name' => 'articleFilter',
                'type' => 'select2_ajax',
                'label' => 'Articles',
                'placeholder' => 'Pick an article',
                'method' => 'POST',
            ],
            url(route('articleSearch')), // the ajax route
            function ($value) { // if the filter is active
                $this->crud->query = $this->crud->query
                    ->where('commentable_type','=',Article::class)->where('commentable_id','=',$value);


            }
        );
    }
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
