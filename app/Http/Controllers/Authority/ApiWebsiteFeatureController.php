<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ApiWebsiteFeatureController extends Controller
{
    private $feature;

    public function __construct(){
        $this->feature=Contact::where('attribute' , "feature");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->feature->paginate(10);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'value' => "required|string",
        ]);
        Contact::create(['attribute'=>'feature','value'=>$request->value]);
        return response(['message' => "Successfully added feature"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->feature->find($id);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => "required|string",
        ]);
        $this->feature->find($id)->update(['value'=>$request->value]);
        return response(['message' => "Successfully updated feature"], 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->feature->find($id)->delete();
        return response(['message' => "Successfully deleted feature"], 201);
    }

    public function get_feature_image(){
        return url('frontend/images/wam-application-view.PNG');
    }

    public function update_feature_image(Request $request){
        $request->validate([
            'feature_image' => "required",
        ]);
        $request->file('feature_image')->move('frontend/images/', 'wam-application-view.PNG');
        return response(['message' => "Successfully updated feature image"], 201);
    }
}
