<!-- page content -->
<!-- Select2 CSS -->
<link href="<?php echo base_url('assets/admin/vendors/select2/dist/css/select2.min.css');?>" rel="stylesheet">
<div class="right_col" role="main">
	<!-- top tiles -->
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h2>Countries Available Settings : </h2>
			</div>	
		</div>
		<div class="clearfix"></div>
		<?php displayMessages();?>	
        <div class="clearfix"></div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<br />
						<form method="post" action="" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Countries available For LHC <span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<select name="available_countries[]" class="form-control col-md-7 col-xs-12 select2" multiple="multiple">
										<?php if($all_countries){
											foreach ($all_countries as $key => $country){ ?>
											<option value="<?php echo $country->id; ?>" <?php if($country->lhc_available =='yes'){echo 'selected="selected"';} ?> ><?php echo $country->name; ?></option>							
											<?php } 
										} ?>
									</select>
									<?php echo form_error('available_countries[]') ?>
								</div>
							</div>
							<div class="ln_solid"></div>
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									<button type="submit" class="btn btn-success">Submit</button>
									<button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel</button>

								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
			</div>
		<!--END of Listing Page-->
	</div>
</div>
<!-- Select2 js -->
<script src="<?php echo base_url('assets/admin/vendors/select2/dist/js/select2.min.js');?>"></script>
<script type="text/javascript">
	$(".select2").select2();
</script>
