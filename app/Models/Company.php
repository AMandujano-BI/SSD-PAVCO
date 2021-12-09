<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
