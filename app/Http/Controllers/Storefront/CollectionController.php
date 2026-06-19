<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Collection;
use Lunar\Models\Url;

class CollectionController extends Controller
{
    public function index()
    {
        // Fetch all collections along with their associated products and thumbnails
        $collections = Collection::with([
            'media',
            'products.thumbnail',
            'products.prices.currency'
        ])->get();

        //dd($collections);

        // Pass the entire collections list to your storefront view
        return view('storefront.collection-show', compact('collections'));
    }
}