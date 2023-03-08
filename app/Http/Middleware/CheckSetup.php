<?php

namespace App\Http\Middleware;

use Closure;

class CheckSetup
{
    use \App\Http\Controllers\AuthMarketplaceController;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $ignore = [
            'ebayauthenticate',
            'shipmentShop',
            'shipmentShopSave'];

            if (in_array(\Route::currentRouteName(), $ignore)) {
                return $next($request);
            }
   
      //   if ($request->user() != null) {

      //    $userID = $request->user()->id;

      //    if (!\App\Setup::where('user_id', $userID)->exists()) {
            
      //       if (!\App\User::whereNotIn('admin', [1])->exists()) {
      //             if ($this->setup($userID) != null) {
      //               return $this->setup($userID);
      //             }

      //         }
      //   }
          
     
      // }

      
      
       return $next($request);
    }
}
