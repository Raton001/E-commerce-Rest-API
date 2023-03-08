@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
         <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-12 mt-1 mb-2">
                            <h4>Statistics</h4>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    
                                    <h2 class="text-muted mb-0 line-ellipsis"><a href="/ebay/dashboard">eBay</a></h2>
                                    <input type="checkbox" name="default" value="1" class="marketplace">
                                    <span>Make default</span>
                                </div>
                            </div>
                        </div>
                       <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    
                                    <h2 class="text-muted mb-0 line-ellipsis"><a href="/amazon/dashboard">Amazon</a></h2>
                                    <input type="checkbox" name="default" value="4" class="marketplace">
                                    <span>Make default</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    
                                    <h2 class="text-muted mb-0 line-ellipsis"><a href="/shopee/dashboard">Shopee</a></h2>
                                    <input type="checkbox" name="default" value="2" class="marketplace">
                                    <span>Make default</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    
                                    <h2 class="text-muted mb-0 line-ellipsis"><a href="/lazada/dashboard">Lazada</a></h2>
                                    <input type="checkbox" name="default" value="3" class="marketplace">
                                    <span>Make default</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Widgets Statistics End -->

    </div>
</div>


@endsection

