<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Section;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $budget = Section::where('description','Budget Section')->first();
        $accounting = Section::where('description','Accounting Section')->first();
        if(Session::get('auth')->section == $accounting->id) {
            return redirect('accounting/accept');
        }
        return $next($request);

    }
}
