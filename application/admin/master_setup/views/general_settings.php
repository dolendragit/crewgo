<!-- page content -->
<div class="right_col" role="main">
	<!-- top tiles -->
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h2>Settings : </h2>
			</div>	
		</div>
		<div class="clearfix"></div>
		<div class="clearfix"></div>
		<form method="post" action="">	
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_content">
						<table class="table">
							<th align="center">Setting element</th>
							<th align="center">Value</th>
						</thead>
						<tbody>							
							<tr>
								<td>Quote expiry time</td>
								<td><label><input type="text" class="form-control" name="quote_expiry_time" value="<?php echo set_value('quote_expiry_time', $settings->quote_expiry_time) ?>"> </label> Minutes <?php echo form_error('quote_expiry_time') ?> </td>					
							</tr>
							<tr>
								<td>Quote cleanup time</td>
								<td><label><input type="text" class="form-control" name="quote_cleanup_time" value="<?php echo set_value('quote_cleanup_time', $settings->quote_cleanup_time) ?>"> </label> Days <?php echo form_error('quote_cleanup_time') ?> </td>				
							</tr>
							<tr>
								<td>Short Notice Period</td>
								<td><label><input type="text" class="form-control" name="short_notice_period" value="<?php echo set_value('short_notice_period', $settings->quote_expiry_time) ?>"> </label> Hours <?php echo form_error('quote_expiry_time') ?> </td>				
							</tr>
							<tr>
								<td>Maximum length of booking for a job</td>
								<td ><div class="col-md-4 col-sm-4 col-xs-4">
								<input type="text" class="form-control" name="max_booking_time" value="<?php echo set_value('max_booking_time', $settings->max_booking_time) ?>"> 
								<?php echo form_error('max_booking_time') ?> 
								</div>
								<div class="col-md-4 col-sm-4 col-xs-4">
								<?php $booking_type = ($this->input->post('max_booking_time_type')) ? $this->input->post('max_booking_time_type'): $settings->max_booking_time_type; ?>
									<select class="form-control" name="max_booking_time_type">
										<option value="days" class="flat" <?php if($booking_type=='days'){echo 'selected="selected"';} ?>>Days</option>
										<option value="weeks" class="flat" <?php if($booking_type=='weeks'){echo 'selected="selected"';} ?>>Weeks</option>
									</select>
									<?php echo form_error('max_booking_time_type') ?> 
									</div>
									<div class="clearfix"></div>
								</td>					
							</tr>
							<tr>
								<td>Publish Job in Market before</td>
								<td><?php $job_published_time = ($this->input->post('publish_job_before'))?$this->input->post('publish_job_before'):$settings->publish_job_before; ?> 
									<label>
										<select class="form-control" name="publish_job_before">
											<option value="45" <?php if ($job_published_time==45){echo 'selected="selected"'; } ?>> 45 </option>
											<option value="60" <?php if ($job_published_time==60){echo 'selected="selected"'; } ?> > 60 </option>
											<option value="120" <?php if ($job_published_time==120){echo 'selected="selected"'; } ?> > 120 </option>
										</select>
									</label> Minutes 
									<?php echo form_error('publish_job_before') ?> </td>					
								</tr>
							</tbody>
						</table>
						<table class="table">
							<th align="center">Invoice customer</th>
							<th align="center"></th>
						</thead>
						<tbody>							
							<tr>
								<td></td>
								<td>After <label>
									<?php $timesheet_completion = ($this->input->post('ic_timesheet_completion'))?$this->input->post('ic_timesheet_completion'):$settings->ic_timesheet_completion; ?> 
									<select class="form-control" name="ic_timesheet_completion">
										<option value="15" <?php if($timesheet_completion==15){echo 'selected="selected"';} ?> > 15 </option>
										<option value="25" <?php if($timesheet_completion==25){echo 'selected="selected"';} ?> > 25 </option>
										<option value="30" <?php if($timesheet_completion==30){echo 'selected="selected"';} ?> > 30 </option>
										<option value="45" <?php if($timesheet_completion==45){echo 'selected="selected"';} ?> > 45 </option>
										<option value="60" <?php if($timesheet_completion==60){echo 'selected="selected"';} ?> > 60 </option>
									</select></label> minutes of timesheet completion 
									<?php echo form_error('ic_timesheet_completion') ?> 
								</td>					
							</tr>
							<tr>
								<td></td>
								<td>After <label>
									<?php $task_completion = ($this->input->post('ic_task_completion'))?$this->input->post('ic_task_completion'):$settings->ic_task_completion; ?> 
									<select class="form-control" name="ic_task_completion">
										<option value="15" <?php if($task_completion==15){echo 'selected="selected"';} ?> >15</option>
										<option value="25" <?php if($task_completion==25){echo 'selected="selected"';} ?> >25</option>
										<option value="30" <?php if($task_completion==30){echo 'selected="selected"';} ?> >30</option>
										<option value="45" <?php if($task_completion==45){echo 'selected="selected"';} ?> >45</option>
										<option value="60" <?php if($task_completion==60){echo 'selected="selected"';} ?> >60</option>
									</select></label> minutes of task completion 
									<?php echo form_error('ic_task_completion') ?> 
								</td>					
							</tr>
						</tbody>
					</table>
				</table>
				<table class="table">
					<th align="center">LHC Settings </th>
					<th align="center"></th>
				</thead>
				<tbody>							
					<tr>
						<td>Allow the LHC to replace a Staff</td>
						<td> <label>
							<?php $replace_staff = ($this->input->post('allow_lhc_to_replace_staff'))?$this->input->post('allow_lhc_to_replace_staff'):$settings->allow_lhc_to_replace_staff; ?> 
							<input type="radio" name="allow_lhc_to_replace_staff"  value="yes" class="flat" <?php if($replace_staff=='yes'){echo 'checked="checked"';} ?>></label>Yes<label>
							<input type="radio" name="allow_lhc_to_replace_staff" value="no" class="flat" <?php if($replace_staff=='no'){echo 'checked="checked"';} ?>></label> No 
							<?php echo form_error('allow_lhc_to_replace_staff') ?> 
						</td>
					</tr>
					<tr>
						<td>Pay period</td>
						<td> 
							<label>
								<?php $pay_period = ($this->input->post('pay_period'))?$this->input->post('pay_period'):$settings->pay_period_type; ?>
								<select class="form-control" name="pay_period_type">
									<option value="1" <?php if($pay_period==1){echo 'selected="selected"';} ?>>Weekly</option>
									<option value="2" <?php if($pay_period==2){echo 'selected="selected"';} ?>>Fortnightly</option>
									<option value="3" <?php if($pay_period==3){echo 'selected="selected"';} ?>>Monthly</option>
									<option value="4" <?php if($pay_period==4){echo 'selected="selected"';} ?>>Fist Day of Month</option>
									<option value="5" <?php if($pay_period==5){echo 'selected="selected"';} ?>>Last Day of Month</option>
								</select> <?php echo form_error('pay_period_type') ?> 
							</label>
							<label>
							<?php $pay_week_day = ($this->input->post('pay_week_day'))?$this->input->post('	pay_week_day'):$settings->pay_week_day; ?>

								<input type="radio" name="pay_week_day" class="flat" value="1" <?php if($pay_week_day==1){echo 'checked="checked"';} ?>></label> Mon
								<label> <input type="radio" name="pay_week_day" class="flat" value="2" <?php if($pay_week_day==2){echo 'checked="checked"';} ?>></label> Tue
								<label> <input type="radio" name="pay_week_day" class="flat" value="3" <?php if($pay_week_day==3){echo 'checked="checked"';} ?>></label> Wed
								<label> <input type="radio" name="pay_week_day" class="flat" value="4" <?php if($pay_week_day==4){echo 'checked="checked"';} ?>></label> Thu
								<label> <input type="radio" name="pay_week_day" class="flat" value="5" <?php if($pay_week_day==5){echo 'checked="checked"';} ?>></label> Fri
								<?php echo form_error('pay_week_day') ?> 
							</td>						
						</tr>
						<tr>
							<td colspan="2"><label>Remove the staff from potential staff list to avoid Penalty rates</label></td>													
						</tr>
						<tr>
							<td colspan="2">Exceeds that day <label><input type="text" name="remove_potential_staff_day" class="form-control" value="<?php echo set_value('remove_potential_staff_day', $settings->remove_potential_staff_day) ?>"></label> 
								<?php echo form_error('remove_potential_staff_day') ?> 
							</td>
						</tr>
						<tr>
							<td colspan="2">Exceeds that week <label><input type="text" name="remove_potential_staff_week" class="form-control" value="<?php echo set_value('remove_potential_staff_week', $settings->remove_potential_staff_week) ?>"></label> 
								<?php echo form_error('remove_potential_staff_week') ?> 
							</td>
						</tr>
						<tr>
							<td colspan="2">Exceeds that fortnight <label><input type="text" name="remove_potential_staff_fortnight" class="form-control" value="<?php echo set_value('remove_potential_staff_fortnight', $settings->remove_potential_staff_fortnight) ?>"></label> 
								<?php echo form_error('remove_potential_staff_fortnight') ?> 

							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
			<button type="submit" name="submit" value='1' class="btn btn-success"> Update </button>
		</div>
	</div>
</div>	
</form>
<!--END of Listing Page-->
</div>
</div>
