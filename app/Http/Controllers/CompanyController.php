<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{

    private $_company;
    private $_country;

    public function __construct(Company $company, Country $country)
    {
        $this->_company = $company;
        $this->_country = $country;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $countries = $this->_country->getCountries();
        return Inertia::render('Company/Index', [
            'countries' => $countries,
        ]);
    }
    public function getCompanies($type){

        $companies = $this->_company->getCompanies($type);
        // return $companies;
        return datatables()->of($companies)->toJson();
    }
    public function getDistributors($id){
        $companies = $this->_company->getDistributors($id);
        return $companies;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = $this->_company->createCompany($request);
        return $company;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $company = $this->_company->updateCompany($request->id,$request);
        return $company;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = $this->_company->deleteCompany($id);
        return $company;
    }
}
