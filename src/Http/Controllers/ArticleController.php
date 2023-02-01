<?php

namespace Rainbow1997\Testback\Http\Controllers;
use Illuminate\Http\Request;
use Rainbow1997\Testback\Models\Category;

class ArticleController
{
    public function categorySearch(Request $request)
    {

        $term = $request->input('term');
        $options = Category::where('title', 'like', '%' . $term . '%')->limit(500)->pluck('title', 'id');

        return $options;

    }
}
