<!-- page content -->
<!-- Select2 Css -->
<link href="<?php echo base_url('assets/admin/vendors/select2/dist/css/select2.min.css');?>" rel="stylesheet">
<div class="right_col" role="main">
	<!-- top tiles -->
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h2>Restrict Industry to LHCs : </h2>
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
						<form method="post" action="" id="formAddIndustry" class="form-horizontal form-label-left">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Restrict Industry <span class="required">*</span>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<select name="restrict_industry" class="form-control select2">
										<?php if($all_industries){
											foreach ($all_industries as $key => $industry){ ?>
											<option value="<?php echo $industry->id; ?>" <?php if($industry->lhc_restriction =='yes'){echo 'disabled="disabled"';} ?> ><?php echo $industry->name; ?></option>								
											<?php } 
										} ?>
									</select>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12">
								<button type="button" class="btn btn-primary" id="btnAddIndustry">Add</button>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
								</label>
								<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="industry_box">
								<?php if($selected_industries){
									foreach ($selected_industries as $key => $industry){ ?>
									<div class="label label-default" data-id="<?php echo $industry->id; ?>"><?php echo $industry->name; ?> <span class="remove_industry"><i class="fa fa-times"></i></span></div>			
									<?php } 
								} ?>
								</div>
								</div>								
							</div>
							<div class="ln_solid"></div>

						</form>
					</div>
				</div>
			</div>
		</div>		
		
		<!--END of Listing Page-->
	</div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="modalPasw" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
      <form method="post" action="" class="form-horizontal" id="formPasw">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="modalTitle">Add Industry</h4>
        </div>
        <div class="modal-body">
         <div id="errorMsgArea" class="alert alert-block alert-danger fade in" style="display: none;">
              <p id="errorMsg" class="text-center"></p>
          </div>
          <div id="successMsgArea" class="alert alert-block alert-success fade in" style="display: none;">
              <p id="successMsg" class="text-center"></p>
          </div>
         <div class="form-group">
         <?php if($this->session->userdata( 'email' ) && ($this->session->userdata( 'email' )=='md@crewgo.com')){ ?>
         <label for="password" class="col-sm-4 control-label">Password <span class="required">*</label>
         	<div class="col-sm-8">
         		<input type="password" name="password" class="form-control" id="password" placeholder="Your Password">
         	</div>
         	<?php } else { ?>
         	<label for="" class="col-sm-8 control-label">You are not authorized to restrict industries </label>
         	<?php } ?>
         </div>
        </div>
        <div class="modal-footer">
        <?php if($this->session->userdata( 'email' ) && ($this->session->userdata( 'email' )=='md@crewgo.com')){ ?>
          <button type="button" class="btn btn-success" id="saveIndusrty"> Save </button>
          <?php } ?>
          <button type="button" class="btn btn-primary" data-dismiss="modal"> Close </button>
        </div>
        </form>
      </div>
      
    </div>
  </div>
  
<!-- Select2 js -->
<script src="<?php echo base_url('assets/admin/vendors/select2/dist/js/select2.min.js');?>"></script>
<script type="text/javascript">
	$(".select2").select2();
	var checkPaswUrl = '<?php echo base_url('admin/master_setup/check_password') ?>';
	var addIndustryUrl = '<?php echo base_url('admin/master_setup/add_restrict_industry') ?>';
	var removeIndustryUrl = '<?php echo base_url('admin/master_setup/remove_restrict_industry') ?>';

	
</script>