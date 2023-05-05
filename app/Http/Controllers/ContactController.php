<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Contact;
class ContactController extends Controller
{
    public function index(){
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        $contacts = Contact::orderBy('id', 'desc')->where(function ($query) {
            if($companyId =  request('company_id')){
                $query->where('company_id', $companyId);
            }
            if ($search = \request('search')){
                $query->where('first_name', 'LIKE', "%{$search}%");
            }
            else{
                return redirect()->route('contacts.index')->with([
                    'message' => 'Search No Result',
                    'action' => 'delete'
                ]);
            }
        })->paginate(10);
        return view('contacts.index', compact('contacts', 'companies'));
    }


    public function create(){
        $contact = new Contact();
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function store(Request $request){
        $request->validate([
            'first_name' => ['required', 'regex:/^[a-zA-Z]+$/', 'min:3', 'max:20'],
            'last_name' => ['required', 'regex:/^[a-zA-Z]+$/', 'min:3', 'max:20'],
            'email' => 'required|email|unique:contacts,email',
            'address' => 'required|min:7 |max:255',
            'company_id' => 'required|exists:companies,id',
            'phone' => ['required', 'unique:contacts,phone', 'regex:/^[^a-zA-Z]*\d+[^a-zA-Z]*$/', 'min:10', 'max:25'],
        ]);
        Contact::create($request->all());
//        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
        return redirect()->route('contacts.index')->with([
            'message' => 'Contact created successfully',
            'action' => 'create'
        ]);
    }

    public function show($id){
        $contact =  Contact::findOrFail($id);
        return view('contacts.show', compact('contact'));
    }

    public function edit($id){

        $contact = Contact::findOrFail($id);
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'regex:/^[a-zA-Z]+$/', 'min:3', 'max:20'],
            'last_name' => ['required', 'regex:/^[a-zA-Z]+$/', 'min:3', 'max:20'],
            'email' => 'required|email|unique:contacts,email,'.$id,
            'address' => 'required|min:7 |max:255',
            'company_id' => 'required|exists:companies,id',
            'phone' => ['required', 'unique:contacts,phone,'.$id, 'regex:/^[^a-zA-Z]*\d+[^a-zA-Z]*$/', 'min:10', 'max:25'],
        ]);
        $contact = Contact::findOrFail($id);

        $contact->update($request->all());

//        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
        return redirect()->route('contacts.index')->with([
            'message' => 'Contact edited successfully',
            'action' => 'edit'
        ]);
    }

    public function destroy($id){
        $contact = Contact::findOrFail($id);
        $contact->delete();

//        return back()->with('message', 'Contact has been deleted successfully');
        return back()->with([
            'message' => 'Contact deleted successfully',
            'action' => 'delete'
        ]);
    }
}
