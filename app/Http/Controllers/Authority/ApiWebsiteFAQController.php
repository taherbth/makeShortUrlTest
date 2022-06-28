<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiWebsiteFAQController extends Controller
{
    public function index()
    {
        return Faq::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => "required|string",
            'answer' => "required|string"
        ]);

        try {
            Faq::insert($validatedData + ['created_at' => Carbon::now()]);
            return response(['message' => "Successfully added FAQ"], 201);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }

    }

    public function show(Faq $faq)
    {
        return $faq;
    }

    public function update(Request $request, Faq $faq)
    {
        $validatedData = $request->validate([
            'question' => "required|string",
            'answer' => "required|string"
        ]);

        try {
            $faq->update($validatedData);
            return response(['message' => "Successfully updated FAQ"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();
            return response(['message' => "Successfully deleted FAQ"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }
}
