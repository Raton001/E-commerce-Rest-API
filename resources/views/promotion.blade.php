@extends('layouts.app')

@section('content')
<!-- <div class="app-content content">
  <div class="content-overlay"></div>
  <div class="content-wrapper">
      <div class="content-header row">
      </div>
      <div class="content-body"> -->
          <section id="">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <p style="color: red;">Promotion</p>
                    <h1 style="font-size: 1.3rem;"><?php echo $promotion->name;?></h1>
                  </div>
                  <div class="card-body">
                    <table>
                      <tr>
                        <th>startDate</th>
                        <td>:</td>
                        <td><?php echo $promotion->startDate;?></td>
                      </tr>
                      <tr>
                        <th>endDate</th>
                        <td>:</td>
                        <td><?php echo $promotion->endDate;?></td>
                      </tr>
                      <tr>
                        <th>endDate</th>
                        <td>:</td>
                        <td><?php echo $promotion->endDate;?></td>
                      </tr>
                      <tr>
                        <th>Status</th>
                        <td>:</td>
                        <td><?php echo $promotion->promotionStatus;?></td>
                      </tr>

                      <tr>
                        <th>Priority</th>
                        <td>:</td>
                        <td><?php echo $promotion->priority;?></td>
                      </tr>

                      <tr>
                        <th>promotionType</th>
                        <td>:</td>
                        <td><?php echo $promotion->promotionType;?></td>
                      </tr>

                      <tr>
                        <th>promotionId</th>
                        <td>:</td>
                        <td><?php echo $promotion->promotionId;?></td>
                      </tr>

                      
                       <tr>
                        <th>inventoryCriterion</th>
                        <td>:</td>
                        <td>
                          <table>
                            <tr>
                              <th>inventoryItems</th>
                              <th>listingIds</th>
                              <th>ruleCriteria</th>
                            </tr>
                            <tr>
                              <td>
                                <?php echo $promotion->inventoryCriterion->inventoryItems?>
                                
                              </td>
                               <td>
                                <?php 
                                echo implode(',', $promotion->inventoryCriterion->listingIds);
                                ?>
                                
                              </td>
                               <td>
                                <?php echo $promotion->inventoryCriterion->ruleCriteria?>
                                
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>


                      <tr>
                        <th>discountRules</th>
                        <td>:</td>
                        <td>
                          <table>
                            <tr>
                              <th>inventoryItems</th>
                              <th>minQuantity</th>
                              <th>percentageOffOrder</th>
                            </tr>
                            <?php
                          
                            foreach ($promotion->discountRules as $key => $rules) {
                             
                              ?>
                            <tr>
                              <td>
                                <?php echo (isset($rules->discountSpecification->minQuantity)? $rules->discountSpecification->minQuantity: '');?>
                                
                              </td>
                               <td>
                                <?php 
                                if (isset($rules->discountBenefit->percentageOffOrder) && is_array($rules->discountBenefit->percentageOffOrder)) {
                                echo implode(',', $rules->discountBenefit->percentageOffOrder);

                                }
                                ?>
                                
                              </td>
                               <td>
                                <?php echo $rules->ruleOrder;?>
                                
                              </td>
                            </tr>
                              <?php
                            }
                            ?>
                           
                          </table>
                        </td>
                      </tr>

                      
                      
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </section>
<!--       </div>
  </div>
</div> -->


@endsection
