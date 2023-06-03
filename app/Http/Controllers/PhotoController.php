<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    private $_photo;
    public function __construct(Photo $photo)
    {
        $this->_photo  = $photo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $photo = $this->_photo->createPhoto($request);
        return $photo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }
    public function getPhotosByRun($id){
        $photos = $this->_photo->getPhotosByRun($id);
        return $photos;

    }
    public function getAllUrlSignature(Request $request)
    {
        $urls = array();
        for ($i = 0; $i < count($request->data); $i++) {
            $image =  Storage::temporaryUrl(
                $request->data[$i],
                now()->addMinutes(5)
            );
            array_push($urls, $image);
        }
        return $urls;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request)
    {
        $photo = $this->_photo->updatePhoto($request);
        return $photo;
    }*/
    public function update(Request $request, Photo $photo)
    {

        \Illuminate\Support\Facades\Log::debug('Request Payload:', $request->all());
        $id = $request->input('id');
        $description = $request->input('description');
        $report = $request->input('report');
        $hours = $request->input('hours');
        $image = $request->file('image');


        $photo->id = $id;
        $photo->description = $description;
        $photo->report = $report;
        $photo->hours = $hours;


        if ($image) {
            $imagePath = $image->store('photos');
            $photo->image = $imagePath;
        }


        if ($photo->save()) {
            return [
                'ok' => true,
                'message' => 'Photo was updated successfully',
                'value' => $photo,
            ];
        } else {
            // Return an error response if the photo failed to save
            return [
                'ok' => false,
                'message' => 'Failed to update the photo',
                'value' => 0,
            ];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = $this->_photo->deletePhoto($id);
        return $photo;
    }
}
