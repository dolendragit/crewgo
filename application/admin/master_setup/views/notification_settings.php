
<!-- page content -->
<div class="right_col" role="main">
	<!-- top tiles -->
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h2>Notification ||  Settings : </h2>
			</div>	
		</div>
		<div class="clearfix"></div>
		<div class="clearfix"></div>

		<form name="userpriv" method="post" action="">			
			<div class="row">

				<div class="col-md-12 col-sm-12 col-xs-12">

					<div class="x_content">

						<table class="table notification-table-wrap">

						</thead>
						<tbody>
							<?php
							if($notification)
							{
								foreach($notification as $not)
								{
									?>
									<tr class="notification-wrapping">
										
										<td width="20%"><?php echo $not->name;?></td>
										<?php 
										$nt_groups = $this->model_master_setup->groups_notificaiton($not->id); 
										if($nt_groups){
											foreach ($nt_groups as $ntg) {
												$group_name = "group[".$not->id."][".$ntg->id."]";
												?>											
												<td  ><input type="checkbox" name="<?php echo $group_name; ?>" id="" class="flat" value="1" <?php echo $ntg->value==1?"checked":"";?>> <?php echo $ntg->name; ?></td>
												<?php
											}
										}
										?>
										<?php if($not->notification_type=='2'){ ?>
										<td class="options">After 
											<select name="notification_time[<?php echo $not->id ?>]">
												<option value='15'>15 </option>
												<option value='25'>25 </option>
												<option value='30'>30 </option>
												<option value='45'>45 </option>
												<option value='60'>60 </option>	
											</select> Minutes</td>
											<?php } ?>
											<?php if($not->notification_type=='3'){ ?>
											<td class="options">Before 
												<select name="notification_time[<?php echo $not->id ?>]">
													<option value='15'>15 </option>
													<option value='25'>25 </option>
													<option value='30'>30 </option>
													<option value='45'>45 </option>
													<option value='60'>60 </option>	
												</select> Minutes</td>
												<?php } ?>
												<?php if($not->notification_type=='4'){ ?>
												<td class="options">Leaves more than  
													<select name="allowed_distance[<?php echo $not->id ?>]">
														<option value='20'>20 </option>
														<option value='50'>50 </option>
														<option value='100'>100 </option>
														<option value='200'>200 </option>
														<option value='1000'>1000 </option>	
													</select> meters</td>
													<?php } ?>
													<td  ><?php $message = $not->message; ?> <textarea name="message[<?php echo $not->id; ?>]"><?php echo $message; ?></textarea> </td>
												</tr>
												<?php } } ?>
											</tbody>
										</table>
									</div>
								</div>

							</div>
							<div class="form-group">

								<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									<button type="submit" name="submit" value='1' class="btn btn-success">Submit</button>
								</div>
							</div>
						</div>	
					</form>


					<!--END of Listing Page-->


				</div>
			</div>
    <!-- /page content -->