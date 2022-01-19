<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
class RolsMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {

            $id = auth()->user()->id;
            $user  = User::where('id', $id)->with(['rols'])->first();
            $rols = $user->rols;
            $currentRouteLarge = Route::currentRouteName();
            $currentRoute = explode('.',$currentRouteLarge);
            // dd($currentRoute[0]);
            // dd($this->userAccessRole()['Distributor'],$currentRoute);
            // dd(in_array($currentRoute,$this->userAccessRole()['Distributor']));
            if (count($rols) > 0) {
                foreach ($rols as $rol) {
                    if (in_array($currentRoute[0], $this->userAccessRole()[$rol->name])) {
                        return $next($request);
                    } 
                }
                abort(403, 'You are not allowed to access this page');
            } else {
                exit;
            }
        } catch (\Throwable $th) {
            // dd($th);
            abort(403, 'You are not allowed to access this page ');
        }
    }

    private function userAccessRole()
    {
        return [
            'Master Administrator' => [
                'photo',
                'company',
                'run',
                'part',
                'chemical',
                'report',
                'user',
              

            ],
            'Administrator' => [
                'photo',
                'run',
                'part',
                'report',
           
            ],
            'Distributor' => [
                'run',
                'photo',
                'part',

            ],
            'Customer' => [
                'run',
                'photo',
                'part',
            ]
        ];
    }
}
