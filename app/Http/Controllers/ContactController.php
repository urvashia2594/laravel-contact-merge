<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use DataTables;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
  
            $data = Contact::where('is_master',1)->latest()->get();
  
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('gender', function ($row) {
                        if($row->gender == '1')
                        {
                            return "Male";
                        }elseif($row->gender == '2'){
                            return "Female";
                        }else{
                            return "Other";
                        }
                    })
                    ->addColumn('image', function($row){
                        if ($row->profile_image) {
                            return '<img src="' . asset('storage/' . $row->profile_image) . '" width="50" height="50">';
                        }
                        return 'No Image';
                    })
                    ->addColumn('document', function($row){
                        if ($row->doc) {
                            return '<a href="' . asset('storage/' . $row->doc) . '" target="_blank">View</a>';
                        }
                        return 'No File';
                    })
                    // ->addColumn('custom', function($row){
                    //     if ($row->custom_field) {
                    //         $fields = json_decode($row->custom_field, true);
                    //         return collect($fields)->map(fn($item) => $item['name'] . ': ' . $item['value'])->implode('<hr>');
                    //     }
                    //     return '—';
                    // })
                    ->addColumn('merge', function ($row) {
                        if(!$row->mergedContact()->exists())
                            return '<a href="javascript:void(0)" onclick="show_master(\'' . $row->id . '\')">Merge</a>';
                        return 'Merged';
                    })
                    ->addColumn('action', function($row){
                            $btn  = '<div class="d-flex">';
                            $btn.= '<a href="'.route('contact.edit',$row->id).'" class="btn btn-primary btn-sm">Edit</a>';
    
                            $btn.= '<button class="btn btn-sm btn-danger delete-contact-btn ms-2" data-id="'.$row->id.'">Delete</button>';

                            $btn.=   '<a href="'.route('contact.show',$row->id).'" class="btn btn-sm btn-info ms-2" >Show</a>';
                            $btn.= '</div>';
        
                            return $btn;
                    })
                    ->rawColumns(['image','action','document','merge'])
                    ->make(true);
        }
        
        return view('contact.index');
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $contact = new Contact();
        return view('contact.create',[
            'contact' => $contact
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact )
    {
        $contact->load('mergedContact','mergedContact.child');
        return view('contact.show',compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Contact $contact )
    {
        return view('contact.edit',[
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
