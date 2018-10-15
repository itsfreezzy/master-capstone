<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\FunctionHall, App\FunctionHallCombo, App\UserLog, Auth;

class FunctionHallController extends Controller
{
    public function index()
    {
        $functionhalls = FunctionHall::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.function-halls')->with(['functionhalls' => $functionhalls]);
    }

    
    public function store(Request $request)
    {
        $messages = [
            'fhname.required' => 'Function Hall name is required!',
            'fhname.unique' => 'Entered Function Hall name already exists or it has been deleted. Please enter new Function Hall name.',
            'addfloorarea.required' => 'Floor Area is required!',
            'addfloorarea.numeric' => 'Floor Area must be numeric or decimal!',
            'addmaxcap.gte' => 'Maximum capacity must be greater than or equal to minimum capacity!',
            'addwholeday.gt' => 'Whole day rate must be greater than half day rate!',
        ];

        $validator = Validator::make($request->all(), [
            'fhname' => 'bail|required|min:10|max:75|unique:tblfunctionhalls,name',
            'addfloorarea' => 'required|numeric',
            'addmincap' => 'required|numeric',
            'addmaxcap' => 'required|numeric|gte:addmincap',
            'addwholeday' => 'required|numeric|gt:addhalfday',
            'addhalfday' => 'required|numeric',
            'addinegrate' => 'required|numeric',
            'addhourlyexcess' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.function-halls.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $functionhall = new FunctionHall;
        $functionhall->name = $request->input('fhname');
        $functionhall->floorarea = $request->input('addfloorarea');
        $functionhall->mincapacity = $request->input('addmincap');
        $functionhall->maxcapacity = $request->input('addmaxcap');
        $functionhall->wholedayrate = $request->input('addwholeday');
        $functionhall->halfdayrate = $request->input('addhalfday');
        $functionhall->ineghourlyrate = $request->input('addinegrate');
        $functionhall->hourlyexcessrate = $request->input('addhourlyexcess');
        $functionhall->save();
        
        $fh = FunctionHall::find($functionhall->id);
        $fh->code = sprintf('FH-%03d', $functionhall->id);
        $fh->save();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Function Hall - ' . $functionhall->code . ' - ' . $functionhall->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
        
        return redirect()->route('admin.function-halls.index')->with(['success' => 'Function hall is successfully added to the database.']);
    }

    
    public function update(Request $request, $id)
    {
        $messages = [
            'editfhname.required' => 'Function Hall name is required!',
            'editfhname.unique' => 'Entered Function Hall name already exists or it has been deleted. Please enter new Function Hall name.',
            'editfloorarea.required' => 'Floor Area is required!',
            'editfloorarea.numeric' => 'Floor Area must be numeric or decimal!',
            'editmaxcap.gte' => 'Maximum capacity must be greater than or equal to minimum capacity!',
            'editwholeday.gt' => 'Whole day rate must be greater than half day rate!',
        ];

        $validator = Validator::make($request->all(), [
            'editfhname' => 'bail|required|min:10|max:75|unique:tblfunctionhalls,name,'.$id,
            'editfloorarea' => 'required|numeric',
            'editmincap' => 'required|numeric',
            'editmaxcap' => 'required|numeric|gte:editmincap',
            'editwholeday' => 'required|numeric|gt:edithalfday',
            'edithalfday' => 'required|numeric',
            'editinegrate' => 'required|numeric',
            'edithourlyexcess' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.function-halls.index')
                        ->withErrors($validator)
                        ->withInput()
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ]);
        }
        
        $functionhall = FunctionHall::find($id);
        $functionhall->name = $request->input('editfhname');
        $functionhall->floorarea = $request->input('editfloorarea');
        $functionhall->mincapacity = $request->input('editmincap');
        $functionhall->maxcapacity = $request->input('editmaxcap');
        $functionhall->wholedayrate = $request->input('editwholeday');
        $functionhall->halfdayrate = $request->input('edithalfday');
        $functionhall->ineghourlyrate = $request->input('editinegrate');
        $functionhall->hourlyexcessrate = $request->input('edithourlyexcess');

        if ($functionhall->isDirty()) {
            $functionhall->save();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Updated Function Hall - ' . $functionhall->code . ' - ' . $functionhall->name,
                'date' => date('Y-m-d h:i:s'),
            ]);

            return redirect()->route('admin.function-halls.index')->with(['success' => 'Function hall is successfully updated.']);
        }

        return redirect()->route('admin.function-halls.index')->with(['warning' => 'No changes have been made.']);
    }
    

    public function destroy($id)
    {
        $functionhall = FunctionHall::find($id);
        $functionhall->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Deactivated Function Hall - ' . $functionhall->code . ' - ' . $functionhall->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
        return redirect()->route('admin.function-halls.index')->with(['success' => 'Function hall is successfully deleted.']);
    }


    public function restore($id)
    {
        try {
            $functionhall = FunctionHall::withTrashed()->where('id', $id)->first();
            $functionhall->restore();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Activated Function Hall - ' . $functionhall->code . ' - ' . $functionhall->name,
                'date' => date('Y-m-d h:i:s'),
            ]);
        } catch (Exception $e) {
            return redirect()->route('admin.function-halls.index')->with(['error' => 'Error restoring function hall.']);
        }
        return redirect()->route('admin.function-halls.index')->with(['success' => 'Function hall is successfully restored.']);
    }


    public function getFunctionHall(Request $request)
    {
        $functionhall = FunctionHall::withTrashed()->where('id', $request->id)->first();

        return $functionhall;
    }

    public function combo() {
        $functionhalls = FunctionHall::withTrashed()->get();
        $functionhallcombos = FunctionHallCombo::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.fhalls-combo')->with([
            'functionhalls' => $functionhalls,
            'functionhallcombos' => $functionhallcombos,
        ]);
    }

    public function comboStore(Request $request) {
        $messages = [
            'comboname.required' => 'Function Hall Combo name is required!',
            'comboname.required' => 'Entered Function Hall combo name already exists or it has been deleted. Please enter new Function Hall combo name.',
            'fhname.min' => 'The amount of rooms in a combo must be at least :min rooms.',
            'fhvalue.unique' => 'Entered Function Hall combo already exists or it has been deleted. Please enter new Function Hall combo.',
            'addfloorarea.required' => 'Floor Area is required!',
            'addfloorarea.numeric' => 'Floor Area must be numeric or decimal!',
            'addmaxcap.gte' => 'Maximum capacity must be greater than or equal to minimum capacity!',
            'addwholeday.gt' => 'Whole day rate must be greater than half day rate!',
        ];

        $validator = Validator::make($request->all(), [
            'comboname' => 'required|unique:tblfunchallsdiscount,name',
            'fhname' => 'required|min:2',
            'fhvalue' => 'bail|required|unique:tblfunchallsdiscount,code',
            'addfloorarea' => 'required|numeric',
            'addmincap' => 'required|numeric',
            'addmaxcap' => 'required|numeric|gte:addmincap',
            'addwholeday' => 'required|numeric|gt:addhalfday',
            'addhalfday' => 'required|numeric',
            'addinegrate' => 'required|numeric',
            'addhourlyexcess' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.fhalls-combo.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $functionhall = new FunctionHallCombo;
        $functionhall->name = $request->input('comboname');
        $functionhall->code = $request->input('fhvalue');
        $functionhall->floorarea = $request->input('addfloorarea');
        $functionhall->mincapacity = $request->input('addmincap');
        $functionhall->maxcapacity = $request->input('addmaxcap');
        $functionhall->wholedayrate = $request->input('addwholeday');
        $functionhall->halfdayrate = $request->input('addhalfday');
        $functionhall->ineghourlyrate = $request->input('addinegrate');
        $functionhall->hourlyexcessrate = $request->input('addhourlyexcess');
        $functionhall->save();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Function Hall Combo - ' . $functionhall->code . ' - ' . $functionhall->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
        
        return redirect()->route('admin.fhalls-combo.index')->with(['success' => 'Function hall combo is successfully added to the database.']);
    }

    public function comboUpdate(Request $request, $id) {
        $messages = [
            'editcomboname.required' => 'Function Hall Combo name is required!',
            'editcomboname.required' => 'Entered Function Hall combo name already exists or it has been deleted. Please enter new Function Hall combo name.',
            'editfhname.min' => 'The amount of rooms in a combo must be at least :min rooms.',
            'editfhvalue.unique' => 'Entered Function Hall combo already exists or it has been deleted. Please enter new Function Hall combo.',
            'editfloorarea.required' => 'Floor Area is required!',
            'editfloorarea.numeric' => 'Floor Area must be numeric or decimal!',
            'editmaxcap.gte' => 'Maximum capacity must be greater than or equal to minimum capacity!',
            'editwholeday.gt' => 'Whole day rate must be greater than half day rate!',
        ];

        $validator = Validator::make($request->all(), [
            'editcomboname' => 'required|unique:tblfunchallsdiscount,name,'.$id,
            'editfhname' => 'required|min:2',
            'editfhvalue' => 'bail|required|unique:tblfunchallsdiscount,code,'.$id,
            'editfloorarea' => 'required|numeric',
            'editmincap' => 'required|numeric',
            'editmaxcap' => 'required|numeric|gte:editmincap',
            'editwholeday' => 'required|numeric|gt:edithalfday',
            'edithalfday' => 'required|numeric',
            'editinegrate' => 'required|numeric',
            'edithourlyexcess' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.fhalls-combo.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $functionhall = FunctionHallCombo::find($id);
        $functionhall->name = $request->input('editcomboname');
        $functionhall->code = $request->input('editfhvalue');
        $functionhall->floorarea = $request->input('editfloorarea');
        $functionhall->mincapacity = $request->input('editmincap');
        $functionhall->maxcapacity = $request->input('editmaxcap');
        $functionhall->wholedayrate = $request->input('editwholeday');
        $functionhall->halfdayrate = $request->input('edithalfday');
        $functionhall->ineghourlyrate = $request->input('editinegrate');
        $functionhall->hourlyexcessrate = $request->input('edithourlyexcess');

        if ($functionhall->isDirty()) {
            $functionhall->save();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Updated Function Hall Combo - ' . $functionhall->code . ' - ' . $functionhall->name,
                'date' => date('Y-m-d h:i:s'),
            ]);
            return redirect()->route('admin.fhalls-combo.index')->with(['success' => 'Function hall is successfully updated.']);
        }

        return redirect()->route('admin.fhalls-combo.index')->with(['warning' => 'No changes have been made.']);
    }

    public function comboDestroy($id) {
        try {
            $functionhall = FunctionHallCombo::find($id);
            $functionhall->delete();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Deactivated Function Hall Combo - ' . $functionhall->code . ' - ' . $functionhall->name,
                'date' => date('Y-m-d h:i:s'),
            ]);
        } catch (Exception $e) {
            return redirect()->route('admin.fhalls-combo.index')->with(['error' => 'Error deleting function hall.']);
        }
        return redirect()->route('admin.fhalls-combo.index')->with(['success' => 'Function hall is successfully deleted.']);
    }

    public function comboRestore($id) {
        try {
            $functionhall = FunctionHallCombo::withTrashed()->where('id', $id)->first();
            $functionhall->restore();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Activated Function Hall Combo - ' . $functionhall->code . ' - ' . $functionhall->name,
                'date' => date('Y-m-d h:i:s'),
            ]);
        } catch (Exception $e) {
            return redirect()->route('admin.fhalls-combo.index')->with(['error' => 'Error restoring function hall.']);
        }
        return redirect()->route('admin.fhalls-combo.index')->with(['success' => 'Function hall is successfully restored.']);
    }

    public function getFunctionHallCombo(Request $request)
    {
        $functionhall = FunctionHallCombo::withTrashed()->where('id', $request->id)->first();

        return $functionhall;
    }
}
