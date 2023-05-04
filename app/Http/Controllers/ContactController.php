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
        })->paginate(10);
        return view('contacts.index', compact('contacts', 'companies'));
//        return view('contacts.index');
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
//            'phone'=>'required|unique:contacts,phone|min:10|max:15'
            'phone' => ['required', 'unique:contacts,phone', 'regex:/^[^a-zA-Z]*\d+[^a-zA-Z]*$/', 'min:10', 'max:25'],
        ]);
        Contact::create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
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
            'email' => 'required|email',
            'address' => 'required|min:7 |max:255',
            'company_id' => 'required|exists:companies,id',
//            'phone'=>'required|unique:contacts,phone|min:10|max:15'
            'phone' => ['required', 'regex:/^[^a-zA-Z]*\d+[^a-zA-Z]*$/', 'min:10', 'max:25'],
        ]);
        $contact = Contact::findOrFail($id);

        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }

    public function destroy($id){
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('message', 'Contact has been deleted successfully');
    }
}
