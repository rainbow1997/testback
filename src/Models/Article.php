<?php

namespace Rainbow1997\Testback\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Article extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'articles';
     protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = [];
     protected $fillable = ['title','description','category_id','content','slug','image'];
     //the image is not in db columns list it's just virtual for compatible with backpack upload operations
     protected $attributes = ['image'];
    // protected $hidden = [];
    // protected $dates = [];
    public static function boot()
    {
        parent::boot();
        static::deleted(function($obj) {
            \Storage::disk('public_folder')->delete($obj->image);
        });
    }
    /*
     *
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getBackpackUrl()
    {
        return backpack_url('article');
    }
    public function getComments()
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="' . backpack_url('comment') . '/?articleFilter=' . $this->id . '" data-toggle="tooltip" title="See the comments of the article."><i class="la la-list"></i> Comments</a>';
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class,'imageable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getImageAttribute(){
        $images = [];
        foreach($this->images as $image)
            $images[] = url($image->file);

        return $images;
    }
    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($values)
    {
        $values = json_decode($values,true);
                foreach($values as $value) {
                    $image = new Image;
                    $image->file = $value;
                    $this->images()->save($image);
                }
        }

}
