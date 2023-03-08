<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Trait HelperController
{
  public function showCategoryTree(int $n)
    {
    	$menus = \App\Menu::where(['status'=> 1, 'position'=>'left'])->get();
        $menus = collect($menus->toArray())->all();

        $categories = []; 

        foreach($menus as $row) { 
            $categories[$row['parent']][] = $row;
        }

        if(isset($categories[$n])) { 
            ?>
            <!-- <ul> -->
            <?php
              foreach($categories[$n] as $category) {
                
                    ?>
                    <li>
                     <a href='<?php echo $category['url'];?>'><?php echo $category['name'];?></a>
                      <ul class="menu-content">
                      <?php
                      $this->showCategoryTree($category['id']);
                      ?>
                      </ul>
                    </li>
                    <?php
                
              ?>
              
              <?php 
                            
              }
              ?>
            <!-- </ul> -->
          <?php
        }
        
        return;
    }
    public function showGroupTree(int $n)
    {
      $menus = \App\Group::where([])->get();
        $menus = collect($menus->toArray())->all();

        $categories = []; 

        foreach($menus as $row) { 
            $categories[$row['parent']][] = $row;
        }

        if(isset($categories[$n])) { 
            ?>
            <!-- <ul> -->
            <?php
              foreach($categories[$n] as $category) {
                
                    ?>
                    <li>
                     <a href='/group/<?php echo $category['id'];?>'><?php echo $category['name'];?></a>
                      <ul class="menu-content">
                      <?php
                      $this->showGroupTree($category['id']);
                      ?>
                      </ul>
                    </li>
                    <?php
                
              ?>
              
              <?php 
                            
              }
              ?>
            <!-- </ul> -->
          <?php
        }
        
        return;
    }

    public function getInternalApiUrl()
    {
      // return 'http://localhost/eas-git/web/index.php?r=coded/';
      // return 'http://localhost/cms/cms/web/index.php?r=coded/';
      // return 'http://dev.axisdigitalleap.asia/web/index.php?r=coded/';
      return 'http://ebx.axisdigitalleap.com/web/index.php?r=coded/';
    }

    public function makeCurl($api, $param = false, $url = false)
    {
      if (!$url) {
      $url = $this->getInternalApiUrl();

      }
   
      $cSession = curl_init(); 
      curl_setopt($cSession,CURLOPT_URL,"{$url}{$api}{$param}");
      curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($cSession,CURLOPT_HEADER, false); 
      $result=curl_exec($cSession);
      curl_close($cSession);

      if (!$url) {

      $data = json_decode($result);
      } else {
        $data = $result;
      }
      return $data;
    }

    public function makePostCurl($url, $hash, $body, $skipHeader = false)
    {

$body = json_encode($body);

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, ['data'=>urlencode($body)]);

      // execute!
      $response = curl_exec($ch);
// echo "<pre>";
// var_dump($response);
// exit;
      // close the connection, release resources used
      curl_close($ch);

     return $response;
      // $headers = array(
      //   'Authorization: ' . $hash,
      //   'Content-Type: application/json; charset=utf-8'
      // );

      // $ch = curl_init($url);
      // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
      // curl_setopt($ch, CURLOPT_POST, TRUE);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
      // $result = curl_exec($ch);
      // $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      // curl_close($ch);

      // $data = ['data'=>$result, 'lastFetched'=>date('Y-m-d H:i:s')];
      // return $data;
    }

    public static function makeStaticCurl($api, $param = false)
    {
      $url = 'http://ebx.axisdigitalleap.com/web/index.php?r=coded/';
      $cSession = curl_init(); 
      curl_setopt($cSession,CURLOPT_URL,"{$url}{$api}{$param}");
      curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($cSession,CURLOPT_HEADER, false); 
      $result=curl_exec($cSession);
      curl_close($cSession);
      $data = json_decode($result);

      return $data;
    }

    private function VerifyAddItemBody()
    {
      $body = new \stdClass();
      $body->Item = new \stdClass();
    
      $body->Item->ItemSpecifics = '';
      $body->Item->ApplicationData = 'TheCodedProject';
      $body->Item->AutoPay = 'true';
      $body->Item->ShipToLocations = 'US';
      $body->Item->Site = 'US';
      $body->Item->SiteId = '0';
      $body->Item->StartPrice = array(
        'currencyID'=>'USD',
        'StartPrice'=>'00.0'
      );
      $body->Item->Title = '';
      
      $body->Item->BuyerResponsibleForShipping = 'false';
      $body->Item->CategoryMappingAllowed = 'true';
      $body->Item->ConditionID = '1000';
      $body->Item->Country = 'MY';
      $body->Item->CrossBorderTrade = 'US';
      $body->Item->Currency = 'USD';
      $body->Item->Description = '';
      $body->Item->DisableBuyerRequirements = 'true';
      $body->Item->eBayPlus = 'false';
      $body->Item->ExtendedSellerContactDetails = new \stdClass();
      $body->Item->ExtendedSellerContactDetails->ClassifiedAdContactByEmailEnabled = 'true';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails = new \stdClass();
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours1AnyTime = 'false';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours1Days = 'None';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours1From = '10:30:00';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours1To = '17:00:00';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours2AnyTime = 'false';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours2Days = 'None';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours2From = '10:30:00';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->Hours2To = '17:00:00';
      $body->Item->ExtendedSellerContactDetails->ContactHoursDetails->TimeZoneID = 'Asia/Kuala_Lumpur';


      $body->Item->HitCounter = 'NoHitCounter';
      $body->Item->IncludeRecommendations = 'false';
      
      $body->Item->ListingDuration = 'GTC';
      $body->Item->ListingType = 'FixedPriceItem';
      $body->Item->Location = 'Selangor';
      $body->Item->PaymentMethods = 'PayPal';
      // $body->Item->PayPalEmailAddress = '';
      $body->Item->PictureDetails = new \stdClass();
      $body->Item->PictureDetails->GalleryType = 'Gallery';
      $body->Item->PictureDetails->PictureURL = 'https://i.postimg.cc/BtWkHKXT/11-Lalisse-Lavender-Hand-Cream-70m-L-2pcs-Lalisse-Intense-Rose-Hand-Cream-70m-L-1pc-Clean-Pure-Manuk.jpg,https://i.postimg.cc/BtWkHKXT/11-Lalisse-Lavender-Hand-Cream-70m-L-2pcs-Lalisse-Intense-Rose-Hand-Cream-70m-L-1pc-Clean-Pure-Manuk.jpg,https://i.postimg.cc/BtWkHKXT/11-Lalisse-Lavender-Hand-Cream-70m-L-2pcs-Lalisse-Intense-Rose-Hand-Cream-70m-L-1pc-Clean-Pure-Manuk.jpg';

      $body->Item->PostalCode = '';
      $body->Item->PrimaryCategory =  new \stdClass();
      $body->Item->PrimaryCategory->CategoryID = '';
      $body->Item->PrivateListing = 'false';
      $body->Item->Quantity = '1';

      $body->Item->ReturnPolicy = new \stdClass();
      $body->Item->ReturnPolicy->Description = 'ReturnsAccepted';
      $body->Item->ReturnPolicy->InternationalRefundOption = 'MoneyBack';
      $body->Item->ReturnPolicy->InternationalReturnsAcceptedOption = 'ReturnsAccepted';
      $body->Item->ReturnPolicy->InternationalReturnsWithinOption = 'Days_30';
      $body->Item->ReturnPolicy->InternationalShippingCostPaidByOption = 'Seller';
      $body->Item->ReturnPolicy->RefundOption = 'MoneyBack';
      $body->Item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
      $body->Item->ReturnPolicy->ReturnsWithinOption = 'Days_30';
      $body->Item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';

      // $body->Item->ShippingDetails = new \stdClass();
      // $body->Item->ShippingDetails->GlobalShipping = 'true';


      $body->Item->SellerProfiles = new \stdClass();
      $body->Item->SellerProfiles->SellerPaymentProfile = new \stdClass();
      $body->Item->SellerProfiles->SellerPaymentProfile->PaymentProfileID = '184096176010';
      $body->Item->SellerProfiles->SellerPaymentProfile->PaymentProfileName = 'PayPal Immediate Pay';

      $body->Item->SellerProfiles->SellerReturnProfile = new \stdClass();
      $body->Item->SellerProfiles->SellerReturnProfile->ReturnProfileID = '184096401010';
      $body->Item->SellerProfiles->SellerReturnProfile->ReturnProfileName = 'Return Policy';

      $body->Item->SellerProfiles->SellerShippingProfile = new \stdClass();
      $body->Item->SellerProfiles->SellerShippingProfile->ShippingProfileID = '184097426010';
      $body->Item->SellerProfiles->SellerShippingProfile->ShippingProfileName = 'Expedited Shipping';


      return $body;

    }
}
