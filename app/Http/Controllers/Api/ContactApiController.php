<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactsMerge;
use App\Http\Requests\contact\StoreRequest;
use App\Http\Requests\contact\UpdateRequest;
use App\Http\Requests\contact\MergeRequest;
use Exception;
use  App\Http\Resources\ContactResource;
use  App\Http\Resources\MergeContactResource;
use Illuminate\Support\Facades\Log;

class ContactApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        
        try{
            $data = $this->fillableData($request);

            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('Profile_image', 'public');
                $data['profile_image'] = $imagePath; 
            }
    
            if ($request->hasFile('doc')) {
                $docPath = $request->file('doc')->store('Doc', 'public');
                $data['doc'] = $docPath; 
            }

            if ($request->has('custom_field')) {
                $data['custom_field'] = json_encode(array_values($request->custom_field),JSON_UNESCAPED_UNICODE);
            }


            $contact = Contact::create($data);

            return response()->json(['success' => true, 'message' => 'Contact created successfully', 'data' => new ContactResource($contact)]);

        }catch(Exception $e){
            Log::info("Issue while storing contact for {$data['email']}: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Something went wrong', 'data' => '']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Contact $contact)
    {
        
        try{
            $contact->custom_field = null;
            $contact->save();

            $data = $this->fillableData($request);

            if ($request->hasFile('profile_image')) {
                $imagePath = $request->file('profile_image')->store('Profile_image', 'public');
                $data['profile_image'] = $imagePath; 
            }
    
            if ($request->hasFile('doc')) {
                $docPath = $request->file('doc')->store('Doc', 'public');
                $data['doc'] = $docPath; 
            }

            if ($request->has('custom_field')) {
                $data['custom_field'] = json_encode(array_values($request->custom_field),JSON_UNESCAPED_UNICODE);
            }

            $contact->update($data);

            //check if has merge contact then update merged_contact for master record
            if($contact->mergedContact()->exists())
            {
                $this->merging_logic($contact->mergedContact->contact_uuid, $contact->mergedContact->contact_child_uuid);   
            }

            return response()->json(['success' => true, 'message' => 'Contact updated successfully', 'data' => new ContactResource($contact)]);

        }catch(Exception $e){
            Log::info("Issue while updating contact for {$data['email']}: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Something went wrong', 'data' => '']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        try{
            $contact->delete();

            return response()->json(['success' => true, 'message' => 'Contact deleted successfully', 'data' => '']);

        }catch(Exception $e){
            Log::info("Issue while deleting contact for {$contact['email']}: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Something went wrong', 'data' => '']);
        }
    }

    protected function fillableData($request)
    {
        return $request->only('name','email','Phone','gender','profile_image','doc');
    }

    public function merge_contact(MergeRequest $request)
    {   
        try{
            $master_id = $request->master_id;
            $secondory_contact_id = $request->secondory_contact_id;
        
            //call function to merge
            $this->merging_logic($master_id, $secondory_contact_id);
            return response()->json(['success' => true, 'message' => 'Contact merged successfully', 'data' => new MergeContactResource($merge_contact)]);
        }catch(Exception $e){
            Log::info("Issue while mergeing contact for {$secondory_data['email']}: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Something went wrong', 'data' => '']);
        }
    }

    protected function merging_logic($master_id, $secondory_contact_id)
    {
        $master_data = Contact::find($master_id);
        $secondory_data =  Contact::find($secondory_contact_id);
    
        $mergedEmails = [$master_data->email,$secondory_data->email];
        $mergedPhones = [$master_data->Phone,$secondory_data->Phone];

        $master_custom_fields = json_decode($master_data->custom_field, true) ?? [];
        $secondory_custom_fields = json_decode($secondory_data->custom_field, true) ?? [];

        $merged = collect(array_merge($master_custom_fields, $secondory_custom_fields))
                    ->unique(fn($item) => $item['name'] . '||' . $item['value']) 
                    ->values()
                    ->toArray();
    
        $mergedJson = json_encode($merged, JSON_PRETTY_PRINT);
    
        // Save merged result in new table
        $merge_contact = ContactsMerge::create([
            'contact_uuid' => $master_id,
            'contact_child_uuid' => $secondory_contact_id,
            'email' => implode(',', array_unique($mergedEmails)),
            'Phone' => implode(',', array_unique($mergedPhones)),
            'custom_field' => $mergedJson,
        ]);
    
        // mark secondory as not master
        $secondory_data->is_master = 0;
        $secondory_data->save();
    }

}
