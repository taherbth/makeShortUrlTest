<?php

namespace App\Http\Controllers\Authority;

use Carbon\Carbon;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ApiWebsiteBlogController extends Controller
{

    public function index()
    {
        return Blog::paginate(10);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => "required|string",
            'article' => "required|string",
            'banner' => "required|mimes:jpeg,jpg,png"
        ]);

        try {
            $file = $request->file('banner');
            $banner_name = uniqid() . $file->getClientOriginalName();
            $file->storeAs('', $banner_name, 's3_website_assets');

            Blog::insert(['banner' => $banner_name, 'created_at' => Carbon::now()] + $validatedData);
            return response(['message' => "Successfully added Blog"], 201);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }

    }

    public function show(Blog $blog)
    {
        return $blog;
    }

    public function update(Request $request, Blog $blog)
    {
        $validatedData = $request->validate([
            'title' => "required|string",
            'article' => "required|string",
            'banner' => "nullable|mimes:jpeg,jpg,png"
        ]);

        try {
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $banner_name = uniqid() . $file->getClientOriginalName();
                $file->storeAs('', $banner_name, 's3_website_assets');

                $blog->update(['banner' => $banner_name] + $validatedData);
            }else{
                $blog->update($validatedData);
            }

            return response(['message' => "Successfully updated Blog"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            Storage::disk('s3_website_assets')->delete($blog->banner);
            $blog->delete();
            return response(['message' => "Successfully deleted Blog"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }
}
