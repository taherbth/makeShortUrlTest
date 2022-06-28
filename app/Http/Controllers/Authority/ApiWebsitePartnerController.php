<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ApiWebsitePartnerController extends Controller
{
    private $feature;

    public function __construct(){
        $this->feature=Contact::where('attribute' , "partner");
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
            'name' => "required|string",
            'value' => "required|mimes:jpeg,jpg,png",
        ]);
        $file = $request->file('value');
        $banner_name = uniqid() . $file->getClientOriginalName();
        $file->storeAs('', $banner_name, 's3_website_assets');

        Contact::create([
            'attribute'=>'partner',
            'value'=>'https://wam-bucket.s3.us-east-2.amazonaws.com/app/website/assets/'.$banner_name,
            'name'=>$request->name
        ]);
        return response(['message' => "Successfully added partner"], 201);
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
            'name' => "string|required_without:value",
            'value' => "mimes:jpeg,jpg,png|required_without:name",
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('value');
            $banner_name = uniqid() . $file->getClientOriginalName();
            $file->storeAs('', $banner_name, 's3_website_assets');
            $this->feature->find($id)->update([
                'attribute'=>'partner',
                'value'=>'https://wam-bucket.s3.us-east-2.amazonaws.com/app/website/assets/'.$banner_name
            ]);
        }

        if ($request->has('name')) {
            $this->feature->find($id)->update([
                'attribute'=>'partner',
                'name'=>$request->name
            ]);
        }


        return response(['message' => "Successfully updated partner"], 201);

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
        return response(['message' => "Successfully deleted partner"], 201);
    }

}
