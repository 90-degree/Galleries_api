<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GalleriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchFilterData = $request->searchFilter;
        $authorFilterData = $request->authorFilter;
        $galleriesQuery = Gallery::with('imageUrls:gallery_id,url')
            ->searchFilter($searchFilterData)
            ->authorFilter($authorFilterData);
        $galleries = $galleriesQuery->paginate(10);

        return response()->json([$galleries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGalleryRequest $request)
    {
        $galleryData = $request->validated();
        $gallery = Auth::user()->galleries()->create($galleryData);
        foreach ($galleryData['image_url_list'] as $url) {
            $gallery->imageUrls()->create(['url' => $url]);
        }
        $gallery->imageUrls;

        return response()->json([$gallery]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->imageUrls;
        return response()->json([$gallery]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateGalleryRequest $request, $id)
    {
        $galleryData = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $gallery->update($galleryData);
        $gallery->imageUrls()->delete();
        foreach ($galleryData['image_url_list'] as $url) {
            $gallery->imageUrls()->updateOrCreate(['url' => $url]);
        }
        $gallery->imageUrls;

        return response()->json([$gallery]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        
        return response()->json([
            'delete' => true,
            'gallery' => $gallery
        ]);
    }
}
