<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ApiWebsiteContractEmailController extends Controller
{
    private $feature;

    public function __construct(){
        $this->feature=Contact::where('attribute' , "notify-email");
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
            'value' => "required|email",
        ]);
        Contact::create(['attribute'=>'notify-email','value'=>$request->value]);
        return response(['message' => "Successfully added email"], 201);
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
            'value' => "required|email",
        ]);
        $this->feature->find($id)->update(['value'=>$request->value]);
        return response(['message' => "Successfully updated email"], 201);

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
        return response(['message' => "Successfully deleted email"], 201);
    }
}
