<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Contact;
class ContactController extends Controller
{
    public function index(){
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        $contacts = Contact::orderBy('first_name', 'asc')->where(function ($query) {
            if($companyId =  request('company_id')){
                $query->where('company_id', $companyId);
            }
        })->paginate(10);
        return view('contacts.index', compact('contacts', 'companies'));
//        return view('contacts.index');
    }


    public function create(){
        return view('contacts.create');
    }


    public function show($id){
        $contact =  Contact::find($id);
        return view('contacts.show', compact('contact'));
    }
}
