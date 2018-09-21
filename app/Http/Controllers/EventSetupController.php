<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventSetup;
use Validator;

class EventSetupController extends Controller
{
    public function index()
    {
        $eventsetups = EventSetup::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.setup')->with([
            'eventsetups' => $eventsetups,
        ]);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addSetupType' => 'bail|required|unique:tbleventsetup,setup',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.setup.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $eventsetup = new EventSetup;
        $eventsetup->setup = $request->input('addSetupType');
        $eventsetup->save();

        return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully added to the database.']);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editSetupType' => 'bail|required|unique:tbleventsetup,setup,'.$id,
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.setup.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $eventsetup = EventSetup::find($id);
        $eventsetup->setup = $request->input('editSetupType');

        if ($eventsetup->isDirty()) {
            $eventsetup->save();
            
            return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully updated.']);
        }

        return redirect()->route('admin.setup.index')->with(['warning' => 'No changes have been made.']);
    }

    
    public function destroy($id)
    {
        EventSetup::find($id)->delete();
        return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully deleted.']);
    }


    public function restore($id)
    {
        EventSetup::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully restored.']);
    }


    public function getEventSetup(Request $request)
    {
        $eventsetup = EventSetup::find($request->id);

        return $eventsetup;
    }
}
