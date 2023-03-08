<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Jobs\SendOrderEmail;
use App\Jobs\DashboardOrder;
use App\Jobs\DashboardListing;
use \App\Http\Controllers\Controller;
use \App\Http\Controllers\ListingController as Listing;




use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Mylaunchpack;
use App\myOldlisting;
use App\Order;
use Log;

use Illuminate\Http\Request;

class EbxapiController extends Controller
{
    use \App\Http\Controllers\HelperController;
	use \App\Http\Controllers\AuthMarketplaceController;
	use \App\Http\Controllers\CalculatorController;

	
    public function internalToken()
    {
       return 'v^1.1#i^1#f^0#r^1#I^3#p^3#t^Ul4xMF8yOkYwNTgwMkY4MTJBMkQ5N0Y2OTYwRjNFRUM2RjFFMzUzXzFfMSNFXjI2MA==';
        
    }
    
	public function getRecomendedItemSpecs(Request $request)
	{
	    $input = $request->all();
	  
	    $categoryID = $input['ebay_id'];//79631;
	    $recommendedArr = [];
	    
		  $recommended = $this->fireXmlApi('GetCategorySpecifics', 
        ['CategoryID'=>$categoryID], 1131, true, $this->internalToken());
        
        $recommended = array_column($recommended, 'NameRecommendation');

             foreach ($recommended as $key => $value) {
              foreach ($value as $k => $v) {

                  $recommendedArr[$v['Name']] = array(
                    'Recommendation'=>(isset($v['ValueRecommendation']) ? array_column($v['ValueRecommendation'], 'Value') : ''),
                    'Rule'=>$v['ValidationRules']['UsageConstraint']
                    );

             }
           }
        
        return $recommendedArr;
	}
	
	public function getCalculator(Request $request)
	{
	    return $this->getConfig()[0];
	}
	
	public function getFixedListingFee(Request $request)
	{
	    $input = $request->all();
	
	    $type = $input['store_type'];
	    
	    $fees = array(
	        'Starter'=>'30',
	        'Basic'=>'25',
	        'Premium'=>'10',
	        'Anchor'=>'5',
	        'Enterprise'=>'5'
	        );
	        
	   if (in_array($type, array_keys($fees))) {
	       return $fees[$type];
	   }
	}
	
	public function getTotalCost(Request $request)
    {
      $weight = $request->input('weight');
      $productCost = $request->input('productCost');

      $shipping = $this->makeCurl("shippingrate", "&weight=$weight");
     
      $postageCharge = array_column($shipping, 'total_shipping_cost')[0];

      $config = $this->getConfig()[0];


      $postageWithFuelSurcharge = ($config['fuel_surcharge']*$postageCharge) /100;

      $shippingRate = $postageWithFuelSurcharge + $postageCharge;

      $totalProductCost = ($shippingRate + $productCost);

      return [
        'rm'=>round($totalProductCost, 2, PHP_ROUND_HALF_UP), 
        'usd'=>round($totalProductCost / $config['conversion_rate'], 2, PHP_ROUND_HALF_UP)];
    
    }
    
    public function getNetProfit(Request $request)
    {
      $sellingPrice = $request->input('sellingPrice');//must be in usd
      $totalCost = $request->input('totalCost');//in myr


      //at least 1 active calculator present
      if (sizeof($this->getConfig())> 0) {

      
      $config = $this->getConfig()[0];
      $totalFees = $this->getTotalFees($sellingPrice);

      $paypalFtFeesAgainstCurrency = $config['conversion_rate'] - (($config['conversion_rate'] * $config['foreign_currency_rate'])/100);

      $balance = $sellingPrice - $totalFees;

      $convertionTo = round($balance * $paypalFtFeesAgainstCurrency, 2, PHP_ROUND_HALF_UP);//rm

      $totalSelling = $sellingPrice*$config['conversion_rate'];//rm

      $netProfit = $convertionTo - $totalCost;//rm

      return round($netProfit, 2, PHP_ROUND_HALF_UP);
      }
      return 'No active Calculator Found';

    }
    
    public function getLaunchpacks(Request $request)
    {
         $query="SELECT id, launch_name, launch_date FROM launchpacks WHERE status = 1";
         $launchpacks = DB::select($query);
         return $launchpacks;
    }

    public function createListing()
    {
    	$listingpool = array();

     	$template = $this->VerifyAddItemBody();

     	echo "<pre>";
     	var_dump($template);exit;

    //    $applicationID = $this->makeCurl("getpackageid", "&sku=".trim($spec['ApplicationData']))[0]->id;
 
    //    $template->Item->ItemSpecifics = $spec['ItemSpecifics'];
    //    $template->Item->ApplicationData = $applicationID;
    //    // $template->Item->MessageID = $applicationID;
    //    $template->Item->Title = $spec['Title'];
    //    $template->Item->StartPrice['StartPrice'] = $spec['StartPrice'];
    //    $template->Item->PrimaryCategory->CategoryID = $spec['ebayCatID'];
    //    $template->Item->Description = $spec['Description'];
    //    $template->Item->PictureDetails->PictureURL = $spec['PictureURL'];
       
    //    $listingpool[] = $template;
    //  }

    //   $ebay = new \App\Launchpack;
    //   $ebay->launch_name = $launchpackTitle;
    //   $ebay->sme_id = $smeID;
    //   $ebay->updated_by = Auth::id();

    //   // $ebay->launch_date = '2021-01-29 19:26:00';

    //   $ebay->save();
    //   $launcpackID = $ebay->id;

    // foreach ($listingpool as $key => $pool) {

    //       $ebay = new \App\LaunchpackListing;
    //       $ebay->launchpack_id = $launcpackID;
    //       $ebay->package_id = $pool->Item->ApplicationData;
    //       $ebay->template = json_encode($pool->Item);

    //       $ebay->save();
    // }
    }

    public function products(Request $request)
    {
       $marketplace = $request->route('marketplace');

        $role = $this->userRole();
        $store  = [];
        $propkg = [];
        $brands = [];

        $brands = $this->makeCurl("brands", "&sme_id=48");
        $sellings = [];

        $sme = $this->makeCurl('getsme');

       $products = $this->makeCurl("getproductlisting", "&limit=10");
       $sme = $this->makeCurl('getsme');
       return view('shopee.home', [
        'data'=>$products, 
        'stores'=>$this->stores(),
        'sme'=>$sme,
        'role'=>'admin',
        'title'=>'Products',
        'shopname'=>'AXIS',
        'marketplace'=>'shopee',
        'account'=>'123',
        'keys'=>$brands,
        'form'=>'editBulkListing',
        'page'=>'shopee.item-listings']);
    }

     public function product(Request $request)
     {
      $id = $request->route('id');
      $edit = $request->route('edit');
// var_dump($edit);exit;
      $product = $this->makeCurl("getproductlisting", "&limit=10&pid=".$id);

       $query = "SELECT * FROM attributes WHERE product_id = $id";
   
      $attributes = DB::select($query);
      

      return view('product', [
        'data'=>$product[0],
        'role'=>1,
        'title'=>'Products',
        'shopname'=>'AXIS',
        'marketplace'=>'shopee',
        'account'=>'123',
        'stores'=>$this->stores(),
        'skipHeader'=>1
      ]);

     }

     public function editBulkListing(Request $request)
     {

      $input = $request->all();

      $output = [];

      // echo "<pre>";
      // var_dump($input);
      // exit;

      foreach($input['listing'] as $key=>$value)
      {
        if (isset($value['sku'])) {
          $output[] = $value;

        }
      }

      $response = $this->makePostCurl(
        'https://ebx.axisdigitalleap.com/web/index.php?r=coded/updateproduct',
        '',
        $output,
        0
      );


      foreach ($output as $key => $value) {
         //keep the record in coded
        $ebay = new \App\listingLog;
        $ebay->sku = $value['sku'];
        $ebay->name = $value['name'];
        $ebay->price = $value['price'];

        $ebay->updated_by = Auth::id();
        $ebay->save();

      }
      
          return redirect()->back()->with('success', 'Product Updated');

     }

     public function editProduct(Request $request)
     {
      $id = $request->route('id');
      $product = $this->makeCurl("getproductlisting", "&limit=10&pid=".$id);

       //attributes
      $query = "SELECT category_id FROM attributes WHERE product_id = $id";
      $categoryID = DB::select($query);

        //attributes
      $query = "SELECT description FROM attributes WHERE product_id = $id";
      $description = DB::select($query);


      if (isset($categoryID[0]->category_id)) {
$cat = $categoryID[0]->category_id;
      } else {
        $cat = 0;
      }

      //101213
      //$product[0]->shopee_cat_id
      $template = array(
        'shopee'=>$this->getAttributes($cat),
        'lazada'=>[],
        'ebay'=>[],
        'amazon'=>[]);

      //attributes
      $query = "SELECT * FROM attributes WHERE product_id = $id AND type='template'";
   
      $attributes = DB::select($query);

      //custom
      $query = "SELECT * FROM attributes WHERE product_id = $id AND type='custom'";
   
      $custom = DB::select($query);


      $outputCustom = [];

      if (isset($custom[0]->attribute)) {
   
        foreach ($custom as $key => $value) {
          // if (isset($value->attribute)) {
         $outputCustom[$value->marketplace] = json_decode($value->attribute);

          // }


        }
    
      }
      


      //images
      $query = "SELECT image FROM  image_galleries WHERE product_id = $id";
   
      $images = DB::select($query);

      $output = [];

      foreach ($attributes as $key => $attribute) {

       $output[$attribute->marketplace] = json_decode($attribute->attribute);
      }

      return view('edit-product', [
        'data'=>$product[0],
        'role'=>1,
        'title'=>'Products',
        'shopname'=>'AXIS',
        'marketplace'=>'shopee',
        'account'=>'123',
        'stores'=>$this->stores(),
        'skipHeader'=>1,
        'template'=>$template,
        'attributes'=>$output,
        'customs'=>$outputCustom,
        'shopee_cat'=>$cat,
        'images'=>(isset($images[0])? explode(',', $images[0]->image): ''),
        'description'=>(isset($description[0]->description) ? $description[0]->description : '')
      ]);

     }

     public function saveProduct(Request $request)
     {

       $id = $request->route('id');
       $input = $request->all();
       $brandID = $input['brand'];
       $category_id = $input['category_id'];
       $custom = [];

       if (isset($input['custom'])) {
        $custom = array_merge($input['custom'], $input['contact']);
       } else {
                   //custom attribute

       
         foreach ($input['contact'] as $key => $value) {
          $custom[] = $value;

         }
       }



       //logging
       $log_attributes = 0;
       $log_image = 0;
       $log_title = 0;
       $log_price = 0;
       $log_description = 0;
       $log_action = 'update';

       $filePath = '/assets/listing_gallery'.DIRECTORY_SEPARATOR.$brandID;

       $templateAttributes = [];
       //template attributes
       if (isset($input['attribute'])) {

       
       $attributes = $input['attribute'];
       foreach ($attributes as $key => $attribute) {
        if ($attribute['value']) {
          $templateAttributes[] = $attribute;
        }
       }
     }


       //listing
       
       
     

      if (!\App\Attribute::where(['product_id'=> $id, 'type'=>'template', 'marketplace'=>'shopee'])->exists()) {
  
                $attribute = new \App\Attribute;

                $attribute->product_id = $id;
                $attribute->attribute = (sizeof($templateAttributes) > 0 ? json_encode($templateAttributes) : '');
                $attribute->type = 'template';
                $attribute->marketplace = 'shopee';
                $attribute->category_id = $category_id;
                $attribute->description = $input['content'];
                



                $attribute->save();

                $log_action = 'insert';

           } else {

              \App\Attribute::where(['product_id'=> $id, 'type'=>'template', 'marketplace'=>'shopee'])
              ->update([
                'category_id'=>$category_id,
                'attribute' => (sizeof($templateAttributes) > 0 ? json_encode($templateAttributes) : ''),
                'description'=>$input['content'],

                ]);
              $log_action = 'update';
              $log_attributes = 1;
           }

      // }

        if(isset($input['repeater-list']))
         {
       
          //upload gallery picture
          // $this->validate($request, [
          //         'gallery' => 'required',
          //         'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
          // ]);

              $i = 0;
              foreach($input['repeater-list'] as $image)
              {
                  $file = $image['images'];
                  $i++;
                  $galleryName = $file->getClientOriginalName();
           
                  $name = time().'_'.$i.'_'.$galleryName;
                  //move to pulic folder in lcoalhost
                  $file->move(public_path($filePath.DIRECTORY_SEPARATOR.$id), $name);
       
                  $data[] = $filePath.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.$name;  
              }

             $images = implode(',', $data);

            if (!\App\ImageGallery::where(['product_id'=> $id])->exists()) {
              
                //save the images
                $gallery = new \App\ImageGallery;
                $gallery->product_id = $id;
                $gallery->image = $images;
                $gallery->save();
           } else {

           
              \App\ImageGallery::where(['product_id'=> $id])
              ->update([
                'image' => $images
                ]);
           }
                

           }



       if (!\App\Attribute::where(['product_id'=> $id, 'type'=>'custom', 'marketplace'=>'shopee'])->exists()) {
                
                $attribute = new \App\Attribute;

                $attribute->product_id = $id;
                $attribute->attribute = json_encode($custom);
                $attribute->type = 'custom';
                $attribute->marketplace = 'shopee';
                $attribute->category_id = $category_id;



                $attribute->save();
                $log_action = 'update';
                $log_attributes = 1;
           } else {

              \App\Attribute::where(['product_id'=> $id, 'type'=>'custom', 'marketplace'=>'shopee'])
              ->update([
                'category_id'=>$category_id,
                'attribute' => json_encode($custom)
                ]);
              $log_attributes = 1;
           }
 

          return redirect()->back()->with('success', 'Product Updated');
        

     }

  private function getAttributes($categoryID, $productID = false)
  {
    if (!$productID) {

      $tpm_base_url4 = 'https://partner.shopeemobile.com/api/v1/item/attributes/get'; // ending with /
        $private_key4  = '545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e';

        // Body (json encoded array)
        $request_body4 = json_encode(array(
           'category_id' =>(int)$categoryID,
            'partner_id' => (int)2000902,
            'shopid' => (int)275920176,
            'timestamp' => time())); 

        // Calculate the HMAC ($hash)
        $unhashed4 = $tpm_base_url4 . '|' . $request_body4;
        $hash4     = hash_hmac('sha256', $unhashed4, $private_key4);

        // Request headers
        $headers4 = array(
            'Authorization: ' . $hash4,
            'Content-Type: application/json; charset=utf-8'
        );

        // Request
        $ch4 = curl_init($tpm_base_url4);
        curl_setopt($ch4, CURLOPT_HTTPHEADER, $headers4);
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($ch4, CURLOPT_HEADER, TRUE); // Includes the header in the output
        curl_setopt($ch4, CURLOPT_POST, TRUE);
        curl_setopt($ch4, CURLOPT_POSTFIELDS, $request_body4);
        $result4 = curl_exec($ch4);
        // $status4 = curl_getinfo($ch4, CURLINFO_HTTP_CODE);
        curl_close($ch4);

        return json_decode($result4);
    } else {
      //fetch from database
       echo "from db";
    }
  }
}
