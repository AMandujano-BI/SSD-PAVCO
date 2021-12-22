<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'fax',
        'customer',
        'distributor',
        'notes',
        'country_id',
        'company_id',
    ];


    public static function createCompany($request)
    {
        DB::beginTransaction();
        try {

            $name  = $request->name;
            $address = $request->address;
            $city  = $request->city;
            $state  = $request->state;
            $zip = $request->zip;
            $phone  = $request->phone;
            $fax  = $request->fax;
            $notes  = $request->notes;
            $country_id = $request->country_id;
            $company_id = $request->company_id;
            if ($company_id == 0) {
                $distributor = 1;
                $customer = 0;
            } else {
                $distributor = 0;
                $customer = 1;
            }
            if ($company_id == 0) $company_id = null;
            if ($customer == 0) $company_id = null;


            $companyFind = (new static)::where('name', $name)->first();
            if ($companyFind != null) {
                DB::rollBack();
                return [
                    'ok' => false,
                    'message' => 'Company already exist',
                    'value' => null,
                ];
            }

            $companyCreate  = (new static)::create([
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'phone' => $phone,
                'fax' => $fax,
                'customer' => $customer,
                'distributor' => $distributor,
                'notes' => $notes,
                'country_id' => $country_id,
                'company_id' => $company_id,

            ]);
            $company = (new static)::with([
                'country'
            ])->find($companyCreate->id);
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Company was added successfully',
                'value' => $company,
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'ok' => false,
                'message' => $e->getMessage(),
                'value' => 0
            ];
        }
    }
    public static function updateCompany($id, $request)
    {
        DB::beginTransaction();
        try {
            $companyCreate = (new static)::find($id);
            $companyCreate->name = $request->name;
            $companyCreate->address = $request->address;
            $companyCreate->city = $request->city;
            $companyCreate->state = $request->state;
            $companyCreate->zip = $request->zip;
            $companyCreate->phone = $request->phone;
            $companyCreate->fax = $request->fax;
            $companyCreate->customer = $request->customer;
            $companyCreate->distributor = $request->distributor;
            $companyCreate->notes = $request->notes;
            $companyCreate->country_id = $request->country_id;
            $companyCreate->company_id = $request->company_id;
            $companyCreate->save();
            $company = (new static)::with([
                'country'
            ])->find($companyCreate->id);
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Company was update successfully',
                'value' => $company,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public static function getDistributors()
    {
        $customers = (new static)::where('distributor', 1)->where('status', '!=', 0)->orderBy('name', 'asc')->get(['id AS value', 'name AS label']);
        return $customers;
    }
    public static function deleteCompany($id)
    {
        DB::beginTransaction();
        try {
            $company = (new static)::find($id);
            $company->status = 0;
            $company->save();
            DB::commit();
            return [
                'ok' => true,
                'message' => 'Company was deleted successfully',
                'value' => $company,
            ];
        } catch (\Exception $e) {

            DB::rollBack();
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public static function getCompanies($type)
    {
        if ($type == 1) {

            $companies = (new static)::with(['country'])->where('distributor', 1)->where('status', '!=', 0)->orderBy('name', 'asc')->get();
        } else if ($type == 0) {
            $companies = (new static)::with(['country'])->where('distributor', 0)->where('status', '!=', 0)->orderBy('name', 'asc')->get();
        } else if ($type == 3) {
            //GET ALL COMPANIES FROM DROPDOWN
            $companies = (new static)::with(['country'])->where('status', '!=', 0)->orderBy('name', 'asc')->get(['id AS value', 'name AS label']);
        } else {
            $companies = (new static)::with(['country'])->where('status', '!=', 0)->orderBy('name', 'asc')->get();
        }
        return $companies;
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
