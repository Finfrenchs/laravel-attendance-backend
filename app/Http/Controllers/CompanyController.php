<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(10);
        return view('pages.company.index', compact('companies'));
    }

    public function show(Company $company)
    {

        $company = Company::firstOrFail();

        return view('pages.company.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('pages.company.edit', compact('company'));
    }

    public function update(Request $request, Company $id)
    {
        $company = Company::findOrFail($id);
        $company->update($request->all());
        return redirect()->route('companies.show')->with('success', 'Company updated successfully');
    }
}
