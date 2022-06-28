<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ApiWebsiteContactController extends Controller
{
    public function get_contact()
    {
        $data = [];
        $contact_list =  Contact::all();
        foreach ($contact_list as $key => $item) {
            $data[$item->attribute] = $item->value;
        }

        return $data;
    }


    public function update_contact(Request $request)
    {
        $request->validate([
            'address' => "string",
            'email' => "email",
            'facebook' => "url",
            'linkedin' => "url",
            'twitter' => "url",
            'instagram' => "url",
            'text' => "string",
            'notify-email' => "email"
        ]);

        try {

            if($request->text)
                Contact::where('attribute' , "text")->first()->update([ 'value' => $request->text]);
            if($request->address)
                Contact::where('attribute' , "address")->first()->update([ 'value' => $request->address]);
            if($request->phone)
                Contact::where('attribute' , "phone")->first()->update([ 'value' => $request->phone]);
            if($request->email)
                Contact::where('attribute' , "email")->first()->update([ 'value' => $request->email]);
            if($request->facebook)
                Contact::where('attribute' , "facebook")->first()->update([ 'value' => $request->facebook]);
            if($request->linkedin)
                Contact::where('attribute' , "linkedin")->first()->update([ 'value' => $request->linkedin]);
            if($request->twitter)
                Contact::where('attribute' , "twitter")->first()->update([ 'value' => $request->twitter]);
            if($request->instagram)
                Contact::where('attribute' , "instagram")->first()->update([ 'value' => $request->instagram]);
            if($request->tnc)
                Contact::where('attribute' , "tnc")->first()->update([ 'value' => $request->tnc]);
            if($request->abouttext)
                Contact::where('attribute' , "abouttext")->first()->update([ 'value' => $request->abouttext]);


            return response(['message' => "Successfully updated contact"], 200);
        } catch (\Throwable $th) {
            return response(['message' => "Unknown error occured"], 500);
        }
    }
}
