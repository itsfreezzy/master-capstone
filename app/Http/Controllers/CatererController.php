<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Caterer;
use App\CatEmail;
use App\CatContact;
use App\CatContactPerson;
use Validator;
use Auth, App\UserLog;

class CatererController extends Controller
{
    public function index()
    {
        $caterers = Caterer::withTrashed()->orderBy('created_at', 'DESC')->get();
        $catemails = CatEmail::all();
        $catcontacts = CatContact::all();
        $catcontactpersons = CatContactPerson::all();
        
        return view('admin.caterers')->with([
            'caterers' => $caterers,
            'catemails' => $catemails,
            'catcontacts' => $catcontacts,
            'catcontactpersons' => $catcontactpersons,
        ]);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addcaterer' => 'required',
            'addaddress' => 'required',
            'caterercontact' => 'required',
            'caterercontact.*' => 'min:7|max:15',
            'catereremail' => 'required',
            'catereremail.*' => 'email',
            'caterercontactperson' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.caterers.index')
                        ->with([
                            'showAddModal' => true,
                        ])
                        ->withErrors($validator)
                        ->withInput();
        }

        $caterer = new Caterer;
        $caterer->name = $request->addcaterer;
        $caterer->address = $request->addaddress;
        $caterer->save();

        foreach ($request->caterercontact as $contact) {
            $catcontact = new CatContact;
            $catcontact->catererid = $caterer->id;
            $catcontact->contactno = $contact;
            $catcontact->save();
        }

        foreach ($request->catereremail as $email) {
            $catemail = new CatEmail;
            $catemail->catererid = $caterer->id;
            $catemail->email = $email;
            $catemail->save();
        }

        foreach ($request->caterercontactperson as $contactperson) {
            $catcontactperson = new CatContactPerson;
            $catcontactperson->catererid = $caterer->id;
            $catcontactperson->person = $contactperson;
            $catcontactperson->save();
        }

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Caterer - ' . $caterer->id . ' - ' . $caterer->name,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.caterers.index')->with(['success' => 'Caterer has been added to the database.']);
    }

    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'editcaterer' => 'required',
            'editaddress' => 'required',
            'editcaterercontact' => 'required',
            'editcaterercontact.*' => 'min:7|max:15',
            'editcatereremail' => 'required',
            'editcatereremail.*' => 'email',
            'editcaterercontactperson' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('admin.caterers.index')
                        ->withErrors($validator)
                        ->withInput()->with([
                            'showEditModal' => true,
                            'id' => $id,
                        ]);
        }

        $caterer = Caterer::find($id);
        $caterer->name = $request->editcaterer;
        $caterer->address = $request->editaddress;
        $caterer->save();

        CatEmail::where('catererid', $caterer->id)->delete();
        foreach ($request->editcatereremail as $email) {
            $catemail = new CatEmail;
            $catemail->catererid = $id;
            $catemail->email = $email;
            $catemail->save();
        }

        CatContact::where('catererid', $caterer->id)->delete();
        foreach ($request->editcaterercontact as $contact) {
            $catcontact = new CatContact;
            $catcontact->catererid = $id;
            $catcontact->contactno = $contact;
            $catcontact->save();
        }

        CatContactPerson::where('catererid', $caterer->id)->delete();
        foreach ($request->editcaterercontactperson as $contactperson) {
            $catcontactperson = new CatContactPerson;
            $catcontactperson->catererid = $id;
            $catcontactperson->person = $contactperson;
            $catcontactperson->save();
        }

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Updated Caterer - ' . $caterer->id . ' - ' . $caterer->name,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.caterers.index')->with(['success' => 'Caterer information successfully updated.']);

    }

    
    public function destroy($id)
    {
        $caterer = Caterer::find($id);
        $caterer->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Caterer - ' . $caterer->id . ' - ' . $caterer->name,
            'date' => date('Y-m-d h:i:s'),
        ]);
        
        return redirect()->route('admin.caterers.index')->with(['success' => 'Caterer successfully deleted.']);
    }


    public function restore($id)
    {
        $caterer = Caterer::withTrashed()->where('id', $id)->first();
        $caterer->restore();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Added Caterer - ' . $caterer->id . ' - ' . $caterer->name,
            'date' => date('Y-m-d h:i:s'),
        ]);

        return redirect()->route('admin.caterers.index')->with(['success' => 'Caterer is successfully restored.']);
    }
}