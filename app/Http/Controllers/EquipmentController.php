<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;
use Validator;
use App\UserLog, Auth;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.equipments')->with(['equipments' => $equipments]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addEquipName' => 'bail|required|unique:tblequipments,name',
            'addEquipDesc' => 'nullable',
            'addEquipWholeDay' => 'required|numeric',
            'addEquipHalfDay' => 'required|numeric',
            'addEquipHourlyExcess' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.equipments.index')
                        ->with([
                            'showAddModal' => true
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $equipment = new Equipment;
        $equipment->name = $request->input('addEquipName');
        $equipment->description = $request->input('addEquipDesc');
        $equipment->wholedayrate = $request->input('addEquipWholeDay');
        $equipment->halfdayrate = $request->input('addEquipHalfDay');
        $equipment->hourlyexcessrate = $request->input('addEquipHourlyExcess');
        $equipment->save();
        $equipment->code = sprintf('EQUIP-%03d', $equipment->id);
        $equipment->save();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Equipment - ' . $equipment->code . ' - ' . $equipment->name,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.equipments.index')->with(['success' => 'Equipment successfully added.']);
    }

    

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editequipName' => 'bail|required|unique:tblequipments,name,'.$id,
            'editequipDesc' => 'nullable',
            'editequipWholeDay' => 'required|numeric',
            'editequipHalfDay' => 'required|numeric',
            'editequipHourlyExcess' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.equipments.index')
                        ->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $equipment = Equipment::find($id);
        $equipment->name = $request->input('editequipName');
        $equipment->description = $request->input('editequipDesc');
        $equipment->wholedayrate = $request->input('editequipWholeDay');
        $equipment->halfdayrate = $request->input('editequipHalfDay');
        $equipment->hourlyexcessrate = $request->input('editequipHourlyExcess');

        if ($equipment->isDirty()) {
            $equipment->save();

            UserLog::create([
                'userid' => Auth::guard('web')->user()->id,
                'action' => 'Updated Equipment - ' . $equipment->code . ' - ' . $equipment->name,
                'date' => date('Y-m-d h:i:s'),
            ]);
            
            return redirect()->route('admin.equipments.index')->with(['success' => 'Equipment successfully updated.']);
        }

        return redirect()->route('admin.equipments.index')->with(['warning' => 'No changes have been made.']);
    }
    

    public function destroy($id)
    {
        $equipment = Equipment::find($id);
        $equipment->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Deactivated Equipment - ' . $equipment->code . ' - ' . $equipment->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
        return redirect()->route('admin.equipments.index')->with(['success' => 'Equipment successfully deleted.']);
    }

    
    public function restore($id)
    {
        $equipment = Equipment::withTrashed()->where('id', $id)->first();
        $equipment->restore();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Activated Equipment - ' . $equipment->code . ' - ' . $equipment->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
        
        return redirect()->route('admin.equipments.index')->with(['success' => 'Equipment successfully restored.']);
    }


    public function getEquipment(Request $request)
    {
        $equipment = Equipment::find($request->id);

        return $equipment;
    }
}
