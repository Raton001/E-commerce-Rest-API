@extends('layouts.app')

@section('content')

		  <!-- Form wizard with step validation section start -->
                <section id="validation">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h4 class="card-title">Sync Ebay Account</h4>
                                </div>
                                <div class="card-body">
                                    <form action="#" class="wizard-validation">
                                        <!-- Step 1 -->
                                        <h6>
                                            <i class="step-icon"></i>
                                            <span>Step 1</span>
                                        </h6>
                                        <!-- Step 1 -->
                                        <!-- body content of step 1 -->
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-12">

                                                    @if (gettype($consent) != 'string')
		                                            <button class="btn btn-outline-info"><a data-authnauth  href="#">Auth N Auth</a></button>
		                                        @else
		                                            <button class="btn btn-outline-info"><a data-authnauth  href="<?php echo $consent;?>">Auth N Auth <i class="fas fa-check"></i></a></button>
		                                        @endif
                                                </div>
                                                
                                            </div>
                                            
                                        </fieldset>
                                        <!-- body content of step 1 end -->
                                        <!-- Step 2 -->
                                        <h6>
                                            <i class="step-icon"></i>
                                            <span>Step 2</span>
                                        </h6>
                                        <!-- step 2 -->
                                        <!-- body content of step 2 end -->
                                        <fieldset>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button class="btn btn-outline-info"><a href="https://auth.ebay.com/oauth2/authorize?client_id=karennai-middlema-PRD-ec8ec878c-badd14db&response_type=code&redirect_uri=karen_nair-karennai-middle-chacvvjxx&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly" target="_blank">OAuth</a></button>
                                                </div>
                                                
                                            </div>
                                        </fieldset>
                                        <!-- body content of step 2 end -->
                                       
                                       
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Form wizard with step validation section end -->


@endsection

