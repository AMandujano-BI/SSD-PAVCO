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
    ];


    public static function createCompany($request)
    {
        $name  = $request->name;
        $address = $request->address;
        $city  = $request->city;
        $state  = $request->state;
        $zip = $request->zip;
        $phone  = $request->phone;
        $fax  = $request->fax;
        $customer  = $request->customer;
        $distributor = $request->distributor;
        $notes  = $request->notes;
        $country_id = $request->country_id;
        $company  = (new static)::create([
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

        ]);
        return [
            'ok' => true,
            'message' => 'Company was added successfully',
            'value' => $company,
        ];
    }
    public static function updateCompany($id, $request)
    {
        DB::beginTransaction();
        try {
            $company = (new static)::find($id);
            $company->name = $request->name;
            $company->address = $request->address;
            $company->city = $request->city;
            $company->state= $request->state;
            $company->zip = $request->zip;
            $company->phone = $request->phone;
            $company->fax= $request->fax;
            $company->customer= $request->customer;
            $company->distributor= $request->distributor;
            $company->notes= $request->notes;
            $company->country_id= $request->country_id;
            $company->save();
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
        $customers = (new static)::where('distributor', 1)->orderBy('name', 'asc')->get(['id AS value', 'name AS label']);
        return $customers;
    }
    public static function deleteCompany($id)
    {
        DB::beginTransaction();
        try {
            $company = (new static)::find($id);
            //$company->status = 2;
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

            $companies = (new static)::with(['country'])->where('distributor', 1)->orderBy('name', 'asc')->get();
        } else if ($type == 0) {
            $companies = (new static)::with(['country'])->where('distributor', 0)->orderBy('name', 'asc')->get();
        } else {
            $companies = (new static)::with(['country'])->orderBy('name', 'asc')->get();
        }
        return $companies;
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
