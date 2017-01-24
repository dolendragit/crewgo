<?php if(isset($workHour)): ?>

<form id="myformThree" method="post" action="<?php echo base_url('lp/wagerule/saveWorkHour');?>">
    	<div class="row special-table">
    		<div class="col-sm-12">
    			<h4 class="heading">Work Hours</h4>
    			<table class="table table-condensed table-bordered">
    				<tr>
    					<th>Day</th>
                        <th>Rule Name for customer</th>
    					<th>Shifts</th>
                        <th>From-To</th>
    					<th>Pay Rate</th>
    				</tr>

    				
                    <?php foreach($workHour as $allkey => $allVal): ?>
                   
                    <input type="hidden" name="ruleidthree[]" value="<?php echo $allVal->id;?>">
                    <tr>
                        <td><?php echo $allVal->myday;?></td>    
                        <td><?php echo $allVal->customer_rule_name;?></td>    
                        <td><?php echo $allVal->myshiftname;?></td>    
                        <td><?php echo $allVal->time_from;?> - <?php echo $allVal->time_to;?></td>  

                         <td style="width: 140px;">
                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" name="manualthree[]" id="manualthree-<?php echo $allkey;?>" value="<?php echo (isset($alreadyExistingWorkHrRule[$allkey]->newpay_rates) ? $alreadyExistingWorkHrRule[$allkey]->newpay_rates : $allVal->pay_rate);?>" class="form-control testgroup">
                                </div>

                                <div class="col-sm-2">ph</div>

                                
                                <div class="clearfix"></div>
                            </div>
                        </td>  
                    </tr>
                <?php endforeach;?>

                   
    				
    				
    			</table>
    			<button class="btn btn-primary btn-sm" type="submit">Update Rule</button>
    		</div>
    	</div>
    </form>

<?php endif;?>
