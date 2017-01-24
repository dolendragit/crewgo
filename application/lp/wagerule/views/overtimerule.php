<?php if(isset($overtimeRule)): ?>
<form id="myformOne" method="post" action="<?php echo base_url('lp/wagerule/saveovertimerule');?>">
    	<div class="row special-table">
    		<div class="col-sm-12">
    			<h4 class="heading">Overtime (penalty rates)</h4>
    			<table class="table table-condensed table-bordered">
    				<tr>
                        <th>Interval</th>
    					<th>Day</th>
                        <th>Threshold Hours</th>
    					<th>Rule Name</th>
                        <th>Apply to all hours</th>
    					<th colspan="5">Pay Rate</th>
    				</tr>

    				<tr>
    					<td colspan="5">&nbsp;</td>
    					<td>Manual</td>
    					<td style="border: none;"><strong>OR</strong></td>
    					<td>Times</td>
    					<td>Shift</td>
    				</tr>




                    <?php foreach($overtimeRule as $mykey => $vals):?>
                       <input type="hidden" name="ruleidone[]" value="<?php echo $vals->id;?>">
                        <tr>
                            <td><?php echo $vals->intervalname;?></td>
                            <td><?php echo $vals->dayname;?></td>
                            <td><?php echo $vals->threshold_hour;?></td>
                            <td><?php echo $vals->customer_rule_name;?></td>
                            <td><?php echo ($vals->applicable_for_all == "1") ? 'Y' : 'N';?></td>


                            <td style="width: 140px;">
                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" name="manualone[]" id="manualone-<?php echo $mykey;?>" value="<?php echo (!empty($alreadyExistingOverTimeRule)) ? $alreadyExistingOverTimeRule[$mykey]->newpay_rates != "0.00" ? $alreadyExistingOverTimeRule[$mykey]->newpay_rates: '' : ((!empty($vals->pay_rates) && $vals->pay_rates != "0.00") ? $vals->pay_rates : '');?>" class="form-control testgroup">
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
                                          name="timesone[]" 
                                          id="timesone-<?php echo $mykey;?>" 
                                          value="<?php echo (!empty($alreadyExistingOverTimeRule)) ? $alreadyExistingOverTimeRule[$mykey]->newpay_times != "0.00" ? $alreadyExistingOverTimeRule[$mykey]->newpay_times: '' : ((!empty($vals->pay_times) && $vals->pay_times != "0.00") ? $vals->pay_times : '');?>" 
                                          class="form-control testgroup">
                                </div>

                                <div class="col-sm-2">times</div>

                                <div class="clearfix"></div>
                            </div>
                        </td>



                        <td style="width: 130px; border-left: none;">
                            <select class="form-control" name="shiftsone[]">
                                <?php foreach($shiftTypes as $myval): ?>
                                    <option value="<?php echo $myval->id;?>" <?php echo (!empty($alreadyExistingOverTimeRule)
                                                                                        && $alreadyExistingOverTimeRule[$mykey]->shift_type_id == $myval->id) 
                                                                                        ? 'selected' : (empty($alreadyExistingOverTimeRule) && $vals->shift_type_id == $myval->id) ? 'selected':''?>><?php echo $myval->name;?></option>
                                <?php endforeach;?>
                            </select>
                        </td>

                        </tr>

                    <?php endforeach; ?>


    				
    				
    			</table>
    			<button class="btn btn-primary btn-sm" type="submit">Update Rule</button>
    		</div>
    	</div>
    </form>
<?php endif;?>