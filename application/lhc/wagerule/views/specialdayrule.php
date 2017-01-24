<?php if(isset($specialDayRule)): ?>
<form id="myform" method="post" action="<?php echo base_url('lhc/wagerule/savespecialdayrule');?>">
    	<div class="row special-table">
    		<div class="col-sm-12">
    			<h4 class="heading">Special Day Rule</h4>
    			<table class="table table-condensed table-bordered">
    				<tr>
    					<th>Day</th>
    					<th>From-To</th>
    					<th>Rule Name</th>
    					<th colspan="4">Pay Rate</th>
    				</tr>

    				<tr>
    					<td colspan="3">&nbsp;</td>
    					<td>Manual</td>
    					<td style="border: none;"><strong>OR</strong></td>
    					<td>Times</td>
    					<td>Shift</td>
    				</tr>


    				
    				<?php foreach($specialDayRule as $key => $value):?>
    				<input type="hidden" name="ruleid[]" value="<?php echo $value->id;?>">
    				<tr>
    					<td><?php echo date('d-M', strtotime($value->calendardate));?></td>
    					<td><?php echo $value->time_from;?> - <?php echo $value->time_to;?></td>
    					<td><?php echo $value->customer_rule_name;?></td>
    					<td style="width: 140px;">
    						<div class="row">
    							<div class="col-sm-8">
    								<input type="text" name="manual[]" id="manual-<?php echo $key;?>" value="<?php echo (!empty($alreadyExistingSpecialDayRule) && $alreadyExistingSpecialDayRule[$key]->newpay_rates != "0.00") ? $alreadyExistingSpecialDayRule[$key]->newpay_rates : ((!empty($value->pay_rate) && $value->pay_rate != "0.00") ? $value->pay_rate : '');?>" class="form-control testgroup">
    							</div>

    							<div class="col-sm-2">ph</div>

    							
    							<div class="clearfix"></div>
    						</div>
    					</td>
    					<td style="border: none;">&nbsp;</td>
    					<td style="width: 140px; border-right: none;">
    						<div class="row">
    							<div class="col-sm-8">
    								<input type="text" 
    									  name="times[]" 
    									  id="times-<?php echo $key;?>" 
    									  value="<?php echo (!empty($alreadyExistingSpecialDayRule)) ? $alreadyExistingSpecialDayRule[$key]->newpay_times != "0.00" ? $alreadyExistingSpecialDayRule[$key]->newpay_times: '' : ((!empty($value->pay_times) && $value->pay_times != "0.00") ? $value->pay_times : '');?>" 
    									  class="form-control testgroup">
    							</div>

    							<div class="col-sm-2">times</div>

    							<div class="clearfix"></div>
    						</div>
    					</td>



    					<td style="width: 130px; border-left: none;">
    						<select class="form-control" name="shifts[]">
    							<?php foreach($shiftTypes as $myval): ?>
    								<option value="<?php echo $myval->id;?>" <?php echo (!empty($alreadyExistingSpecialDayRule)
    																					&& $alreadyExistingSpecialDayRule[$key]->shift_type_id == $myval->id) 
    																					? 'selected' : (empty($alreadyExistingSpecialDayRule) && $value->shift_type_id == $myval->id) ? 'selected':''?>>
    																					<?php echo $myval->name;?>
    						
    								</option>
    							<?php endforeach;?>
    						</select>
    					</td>
    				</tr>


    				<?php endforeach;?>
    			</table>
    			<button class="btn btn-primary btn-sm" type="submit">Update Rule</button>
    		</div>
    	</div>
    </form>
<?php endif; ?>