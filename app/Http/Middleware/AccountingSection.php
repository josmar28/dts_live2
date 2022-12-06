<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Section;

class AccountingSection
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
        $section = Section::where('description','Accounting Section')->first();
        if(Session::get('auth')->section == $section->id) {
            return $next($request);
        }
        return redirect('document/accept');
    }
}
