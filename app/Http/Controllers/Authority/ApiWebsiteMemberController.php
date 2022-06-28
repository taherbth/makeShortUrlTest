<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class ApiWebsiteMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Member[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Member::all();
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
            'image' => "required|mimes:jpeg,jpg,png",
            'role' => "required|string",
            'facebook' => "url|nullable",
            'linkedin' => "url|nullable",
            'instagram' => "url|nullable",
            'is_admin' => "numeric|between:0,1",
        ]);
        $file = $request->file('image');
        $banner_name = uniqid() . $file->getClientOriginalName();
        $file->storeAs('', $banner_name, 's3_website_assets');
        $request=$request->toArray();
        $request['image']='https://wam-bucket.s3.us-east-2.amazonaws.com/app/website/assets/'.$banner_name;
        Member::create($request);

        return response(['message' => "Successfully added member"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Member::findorfail($id);
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
            'name' => "string",
            'image' => "mimes:jpeg,jpg,png",
            'role' => "string",
            'facebook' => "url|nullable",
            'linkedin' => "url|nullable",
            'instagram' => "url|nullable",
            'is_admin' => "numeric|between:0,1",
        ]);
        $is_admin=1;
        if(!$request->has('is_admin')){
            $is_admin = 0;
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $banner_name = uniqid() . $file->getClientOriginalName();
            $file->storeAs('', $banner_name, 's3_website_assets');
            $request=$request->toArray();
            $request['image']='https://wam-bucket.s3.us-east-2.amazonaws.com/app/website/assets/'.$banner_name;
        }else{
            $request=$request->toArray();
        }
        $request['is_admin']=$is_admin;
        Member::findorfail($id)->update($request);
        return response(['message' => "Successfully updated member"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Member::findorfail($id)->delete();
        return response(['message' => "Successfully deleted member"], 201);
    }
}
