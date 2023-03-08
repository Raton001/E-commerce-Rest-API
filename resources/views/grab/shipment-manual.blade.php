@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{session('success')}}</div>
@endif
<form method="post" action="/grab/manual-shipment">
    @csrf
    <div class="row">
    	<div class="col-12">
    		<label>UID</label><br/>
    		<input type="text" name="uid" value="449">(Lynnity)
    	</div>
    	<div class="col-12">
    		<label>Marketplace</label><br/>
    		<select name="marketplace">
    			<option>Grab</option>
    			<option>Astro</option>
    			<option>Offline</option>
    			<option>eBay</option>
    			<option>Amazon</option>
    			<option>Shopee</option>
    			<option>Lazada</option>

    		</select>
    	</div>

    	<div class="col-12">
    		<label>Shipping Mode</label><br/>
    		<select name="shipping_mode">
    			<option value="0">Select a Shipping Mode</option>
    			<option>DHL Express</option>
    			<option>UPS</option>
    			<option>Skynet</option>
    			<option selected="selected">Pick Up</option>
    			<option>DHL Global Mail</option>
    			<option>Citylinkt</option>
    			<option>Pos Laju</option>
    			<option>Aramex</option>
    			<option>Fedex</option>
    			<option>KTM</option>
    			<option>Shopee Logistic</option>
    			<option>Lazada Logistic</option>
    			<option>KTMD</option>
    			<option>FDW-AU</option>
    			<option>Webstore Logistic</option>

    		</select>
    		<label>If nothing above, key in new shipping mode</label><br/>
    		<input type="text" name="shipping_mode_new">
    	</div>

    	<div class="col-12">
    		<label>Final Selling Price</label><br/>
    		<input type="text" name="final_selling_price">
    	</div>
    	<div class="col-12">
    		<label>Order ID</label><br/>
    		<input type="text" name="order_id">
    	</div>
    	<div class="col-12">
    		<label>Shop ID</label><br/>
    		<input type="text" name="shop_id" value="1875">(Grab Shop)
    	</div>
    	<div class="col-12">
    		<label>Customer Name</label><br/>
    		<input type="text" name="customer_name">
    	</div>
    	<div class="col-6">
    		<label>Address 1</label><br/>
    		<input type="text" name="address_1">
    	</div>
    	<div class="col-6">
    		<label>Address 2</label><br/>
    		<input type="text" name="address_2">
    	</div>
    	<div class="col-3">
    		<label>City</label><br/>
    		<input type="text" name="city">
    	</div>
    	<div class="col-3">
    		<label>State</label><br/>
    		<input type="text" name="state">
    	</div>
    	<div class="col-3">
    		<label>Postcode</label><br/>
    		<input type="text" name="postcode">
    	</div>
    	<div class="col-3">
    		<label>Country</label><br/>
    		<input type="text" name="country">
    	</div>
    	<div class="col-6">
    		<label>Contact</label><br/>
    		<input type="text" name="contact">
    	</div>
    	<div class="col-6">
    		<label>Email</label><br/>
    		<input type="text" name="email">
    	</div>
    	<div class="col-12">
    		<label>Airway Bill</label><br/>
    		<input type="text" name="airway_bill">
    	</div>
    	<!-- <div class="col-6">
    		<label>Package</label><br/>
    		<input type="text" name="package">
    	</div> -->
    	<div class="col-6">
    		<label>Product</label><br/>
    		<input type="text" id="product">
    		<input type="button" id="getProducts" value="Search">
    		<div id="product_list">
    			
    		</div>
    	</div>
    	<div class="col-12">
    		
    		<input type="submit" name="Submit" value="Submit">
    	</div>



    </div>

</form>
@endsection