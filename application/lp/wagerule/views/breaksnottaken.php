<?php if(isset($breakRule)): ?>
<form id="myformTwo" method="post" action="<?php echo base_url('lp/wagerule/saveBreakrule');?>">
    	<div class="row special-table">
    		<div class="col-sm-12">
    			<h4 class="heading">Breaks Not Taken</h4>
    			<table class="table table-condensed table-bordered">
    				<tr>
    					<th>Day</th>
                        <th>Rule Name for customer</th>
    					<th>Rule Name for LHC</th>
                        <th>From-To</th>
    					<th>Pay Rate</th>
    				</tr>

    				

                    <?php foreach($breakRule as $mykeys => $myvals): ?>
                    <input type="hidden" name="ruleidtwo[]" value="<?php echo $myvals->id;?>">
                    <tr>
                        <td><?php echo $myvals->mydayname;?></td>    
                        <td><?php echo $myvals->customer_rule_name;?></td>    
                        <td><?php echo $myvals->lhc_rule_name;?></td>    
                        <td><?php echo $myvals->time_from;?> - <?php echo $myvals->time_to;?></td>  

                         <td style="width: 140px;">
                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" name="manualtwo[]" id="manualtwo-<?php echo $mykeys;?>" value="<?php echo (isset($alreadyExistingBreakRule[$mykeys]->newpay_rates) ? $alreadyExistingBreakRule[$mykeys]->newpay_rates : $myvals->pay_rate);?>" class="form-control testgroup">
                                </div>

                                <div class="col-sm-2">ph</div>

                                
                                <div class="clearfix"></div>
                            </div>
                        </td>  
                    </tr>
                    <?php endforeach; ?>

                   
    				
    				
    			</table>
    			<button class="btn btn-primary btn-sm" type="submit">Update Rule</button>
    		</div>
    	</div>
    </form>
<?php endif;?>