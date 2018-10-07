<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\MeetingRoom;
use App\Timeblock;

class MeetingRoomController extends Controller
{
    public function index()
    {
        $meetingrooms = MeetingRoom::withTrashed()->where('name', 'not like', '%old%')->orderBy('created_at', 'DESC')->get();
        $timeblocks = TimeBlock::all();

        return view('admin.meeting-rooms')->with([
            'meetingrooms' => $meetingrooms,
            'timeblocks' => $timeblocks,
        ]);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mrname' => 'bail|required|unique:tblmeetingrooms,name',
            'addfloorarea' => 'required|numeric',
            'addmincap' => 'required|numeric',
            'addmaxcap' => 'required|numeric',
            'addrateperblock' => 'required|numeric',
            'addinegrate' => 'required|numeric',
            'addtimeblock' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.meeting-rooms.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $meetingroom = new MeetingRoom;
        $meetingroom->name = $request->input('mrname');
        $meetingroom->floorarea = $request->input('addfloorarea');
        $meetingroom->mincapacity = $request->input('addmincap');
        $meetingroom->maxcapacity = $request->input('addmaxcap');
        $meetingroom->rateperblock = $request->input('addrateperblock');
        $meetingroom->ineghourlyrate = $request->input('addinegrate');
        $meetingroom->timeblockcode = $request->input('addtimeblock');
        $meetingroom->save();

        $mr = MeetingRoom::find($meetingroom->id);
        $mr->code = sprintf('MR-%03d', $meetingroom->id);
        $mr->save();

        return redirect()->route('admin.meeting-rooms.index')->with(['success' => 'Meeting room is successfully added to the database.']);
    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editmrname' => 'bail|required|unique:tblmeetingrooms,name,'.$id,
            'editfloorarea' => 'required|numeric',
            'editmincap' => 'required|numeric',
            'editmaxcap' => 'required|numeric',
            'editrateperblock' => 'required|numeric',
            'editinegrate' => 'required|numeric',
            'edittimeblock' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.meeting-rooms.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $meetingroom = MeetingRoom::find($id);
        $meetingroom->name = $request->input('editmrname');
        $meetingroom->floorarea = $request->input('editfloorarea');
        $meetingroom->mincapacity = $request->input('editmincap');
        $meetingroom->maxcapacity = $request->input('editmaxcap');
        $meetingroom->rateperblock = $request->input('editrateperblock');
        $meetingroom->ineghourlyrate = $request->input('editinegrate');
        $meetingroom->timeblockcode = $request->input('edittimeblock');

        if ($meetingroom->isDirty()){
            $meetingroom->save();
            return redirect()->route('admin.meeting-rooms.index')->with(['success' => 'Meeting room is successfully updated!']);
        }

        return redirect()->route('admin.meeting-rooms.index')->with(['warning' => 'No changes have been made.']);
    }

    
    public function destroy($id)
    {
        MeetingRoom::find($id)->delete();
        return redirect()->route('admin.meeting-rooms.index')->with(['success' => 'Meeting room is successfully deleted.']);
    }

 
    public function restore($id)
    {
        MeetingRoom::withTrashed()->where('id', $id)->restore();
        return redirect()->route('admin.meeting-rooms.index')->with(['success' => 'Meeting room is successfully restored.']);
    }

    
    public function getMeetingRoom(Request $request)
    {
        $meetingroom = MeetingRoom::withTrashed()->where('id', $request->id)->first();

        return $meetingroom;
    }
}
