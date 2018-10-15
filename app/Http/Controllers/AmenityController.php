<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, App\UserLog;
use Validate;
use App\Amenity;
use Validator;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.amenities')->with(['amenities' => $amenities]);
    }
    
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addamenityName' => 'bail|required|unique:tblamenities,amenity',
            'addamenityDesc' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.amenities.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $amenity = new Amenity;
        $amenity->amenity = $request->input('addamenityName');
        $amenity->description = $request->input('addamenityDesc');
        $amenity->save();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Amenity - ' . $amenity->id . ' - ' . $amenity->amenity,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.amenities.index')->with(['success' => 'Amenity successfully added.']);
    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editamenityName' => 'bail|required|unique:tblamenities,amenity,'.$id,
            'editamenityDesc' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.amenities.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $amenity = Amenity::find($id);
        $amenity->amenity = $request->input('editamenityName');
        $amenity->description = $request->input('editamenityDesc');

        if ($amenity->isDirty()) {
            $amenity->save();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Updated Amenity - ' . $amenity->id . ' - ' . $amenity->amenity,
                'date' => date('Y-m-d h:i:s'),
            ]);

            return redirect()->route('admin.amenities.index')->with(['success' => 'Amenity has been updated.']);
        }

        return redirect()->route('admin.amenities.index')->with(['warning' => 'No changes have been made.']);
    }
    

    public function destroy($id)
    {
        $amenity = Amenity::find($id);
        $amenity->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Deactivated Amenity - ' . $amenity->id . ' - ' . $amenity->amenity,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.amenities.index')->with(['success' => 'Amenity successfully deleted']);
    }


    public function restore($id)
    {
        $amenity = Amenity::withTrashed()->where('id', $id)->first();
        $amenity->restore();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Activated Amenity - ' . $amenity->id . ' - ' . $amenity->amenity,
            'date' => date('Y-m-d h:i:s'),
        ]);
        
        return redirect()->route('admin.amenities.index')->with(['success' => 'Amenity successfully restored.']);
    }

    public function getAmenity(Request $request) {
        $amenity = Amenity::withTrashed()->where('id', $request->id)->first();

        return $amenity;
    }
}