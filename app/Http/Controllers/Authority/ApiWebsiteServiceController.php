<?php

namespace App\Http\Controllers\Authority;

use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ApiWebsiteServiceController extends Controller
{
    public function index()
    {
        return Service::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => "required|string",
            'description' => "required|string",
            'banner' => "required|mimes:jpeg,jpg,png",
            'button_name' => "required_with:button_address|string",
            'button_address' => "required_with:button_name|url",
        ]);

        try {
            $file = $request->file('banner');
            $banner_name = uniqid() . $file->getClientOriginalName();
            $file->storeAs('', $banner_name, 's3_website_assets');

            Service::insert(['banner' => $banner_name, 'created_at' => Carbon::now()] + $validatedData);
            return response(['message' => "Successfully added Service"], 201);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }

    }

    public function show(Service $service)
    {
        return $service;
    }

    public function update(Request $request, Service $service)
    {
        $validatedData = $request->validate([
            'title' => "string",
            'description' => "string",
            'banner' => "mimes:jpeg,jpg,png",
            'button_name' => "required_with:button_address|string",
            'button_address' => "required_with:button_name|url",
        ]);

        try {
            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $banner_name = uniqid() . $file->getClientOriginalName();
                $file->storeAs('', $banner_name, 's3_website_assets');

                $service->update(['banner' => $banner_name] + $validatedData);
            }else{
                $service->update($validatedData);
            }

            return response(['message' => "Successfully updated Service"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }

    public function destroy(Service $service)
    {
        try {
            Storage::disk('s3_website_assets')->delete($service->banner);
            $service->delete();
            return response(['message' => "Successfully deleted Service"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }
}
