<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventNature;
use App\UserLog;
use Validator;
use Auth;
use Illuminate\Validation\Rule;

class EventNatureController extends Controller
{
    public function index()
    {
        $eventnatures = EventNature::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.events')->with([
            'eventnatures' => $eventnatures,
        ]);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addEventNature' => 'bail|required|unique:tbleventnature,nature',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.events.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $eventnature = new EventNature;
        $eventnature->nature = $request->input('addEventNature');
        $eventnature->save();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Event Nature - ' . $eventnature->id . ' - ' . $eventnature->nature,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.events.index')->with([
            'success' => 'Event Type is successfully added to the database.',
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editEventNature' => 'bail|required|unique:tbleventnature,nature,'.$id,
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.events.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $eventnature = EventNature::find($id);
        $eventnature->nature = $request->input('editEventNature');

        if ($eventnature->isDirty()) {
            $eventnature->save();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Updated Event Nature - ' . $eventnature->id . ' - ' . $eventnature->nature,
                'date' => date('Y-m-d h:i:s'),
            ]);

            return redirect()->route('admin.events.index')->with([
                'success' => 'Event Type is successfully updated.',
            ]);
        }

        return redirect()->route('admin.events.index')->with([
            'warning' => 'No changes have been made.',
        ]);;
    }
    
    public function destroy($id)
    {
        $eventnature = EventNature::find($id);
        $eventnature->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Deactivated Event Nature - ' . $eventnature->id . ' - ' . $eventnature->nature,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.events.index')->with([
            'success' => 'Event Type is successfully removed from the database.',
        ]);
    }

    public function restore($id)
    {
        $eventnature = EventNature::withTrashed()->where('id', $id)->first();
        $eventnature->restore();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Activated Event Nature - ' . $eventnature->id . ' - ' . $eventnature->nature,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.events.index')->with(['success' => 'Event Type is successfully restored from the database.']);

    }


    public function getEventNature(Request $request)
    {
        $eventnature = EventNature::find($request->id);

        return $eventnature;
    }
}
