<?php

namespace Rainbow1997\Testback\Http\Controllers;
use Illuminate\Http\Request;
use Rainbow1997\Testback\Models\Category;
use Rainbow1997\Testback\Models\Article;
class ArticleController
{
    public function categorySearch(Request $request)
    {

        $term = $request->input('term');
        $options = Category::where('title', 'like', '%' . $term . '%')->limit(500)->pluck('title', 'id');

        return $options;

    }   ///////yeah its duplicate codes but in this time it doesn't matter!
    public function articleSearch(Request $request)
    {

        $term = $request->input('term');
        $options = Article::where('title', 'like', '%' . $term . '%')->limit(500)->pluck('title', 'id');

        return $options;

    }
}
