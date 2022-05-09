<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\URL;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForRole
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if($request->path() != 'user'){
                $link = "/".$request->path();
                $sub_module = \App\SubModule::where('link',$link)->first();
                if(is_null($sub_module)){
                   return redirect('/user');
               }else{
                $permission = \App\RoleModuleAssociation::where('role_id',Auth::user()->role_id)->where('sub_module_id',$sub_module->id)->first();
                if(!is_null($permission)){
                    return $next($request);
                }else{
                    return redirect('/user');
                }
            }
        }
    }

    return $next($request);
}
}
