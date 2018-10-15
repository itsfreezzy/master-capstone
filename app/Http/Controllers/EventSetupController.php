<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventSetup;
use Validator;
use App\UserLog, Auth;

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

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Event Setup - ' . $eventsetup->id . ' - ' . $eventsetup->setup,
            'date' => date('Y-m-d h:i:s'),
        ]);

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

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Updated Event Setup - ' . $eventsetup->id . ' - ' . $eventsetup->setup,
                'date' => date('Y-m-d h:i:s'),
            ]);
            
            return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully updated.']);
        }

        return redirect()->route('admin.setup.index')->with(['warning' => 'No changes have been made.']);
    }

    
    public function destroy($id)
    {
        $eventsetup = EventSetup::find($id);
        $eventsetup->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Deactivated Event Setup - ' . $eventsetup->id . ' - ' . $eventsetup->setup,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully deleted.']);
    }


    public function restore($id)
    {
        $eventsetup = EventSetup::withTrashed()->where('id', $id)->first();
        $eventsetup->restore();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Activated Event Setup - ' . $eventsetup->id . ' - ' . $eventsetup->setup,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.setup.index')->with(['success' => 'Event Setup is successfully restored.']);
    }


    public function getEventSetup(Request $request)
    {
        $eventsetup = EventSetup::find($request->id);

        return $eventsetup;
    }
}
