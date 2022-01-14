<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    private $_company;
    private $_user;
    private $_rol;
    public function __construct(Company $company, User $user,Rol $rol)
    {
        $this->_company = $company;
        $this->_user = $user;
        $this->_rol= $rol;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companies = $this->_company->getCompanies(3);
        $rols= $this->_rol->getRols();
        // dd($rols);
        return Inertia::render('User/Index', [
            'rols' => $rols,
            'companies' => $companies,
        ]);
    }
    public function getUsers($type)
    {

        $users = $this->_user->getUsers($type);
        // return $users;
        return datatables()->of($users)->toJson();
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

        $user = $this->_user->createUser($request);
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->_user->updateUser($request);
        return $user;
    }

    public function resetPassword(Request $request){
        $user = $this->_user->resetPassword($request);
        return $user;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $user = $this->_user->deleteUser($id);
        return $user;
    }
}
