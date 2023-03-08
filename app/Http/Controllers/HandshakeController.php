<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HandshakeController extends Controller
{
    use \App\Http\Controllers\AuthMarketplaceController;

	public function getConsent()
	{

		return $this->getEbayConsent();	
	}

	public function getToken()
	{
		return $this->getEbayToken();	
	}
	
	public function getAccept()
	{
		return $this->getEbayAccept();	
	}
	

	
}
