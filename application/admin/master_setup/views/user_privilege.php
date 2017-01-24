
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
         <div class="">
            <div class="page-title">
              <div class="title_left">
                <h2>User Privilege ||  Group : <?php echo $group->description;?></h2>
              </div>
				 
			
              
            </div>
            <div class="clearfix"></div>
			<?php displayMessages();?>	
            <div class="clearfix"></div>
			 
			<form name="userpriv" method="post" action="<?php echo base_url('admin/master_setup/user_priv');?>">
			
			  <?php
			  if($modules)
			  {
				  foreach($modules as $module)
				  {
				?>
				<div class="row">
				  
              <div class="col-md-12 col-sm-12 col-xs-12">
			  
                <div class="x_panel">
                  <div class="x_title">
                    <h2><?php echo $module->name;?> <small><?php echo $module->description;?></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> </a>
                         
                      </li>
                       
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <table class="table">
                      <thead>
                        <tr>
						 <th align="center">Menu</th>
                         <!-- <th align="center">All</th>-->
						   
                          <th align="center">Can Add</th>
                          <th align="center">Can Edit</th>
                          <th align="center">Can Delete</th>
                          <th align="center">Can View</th>
                          
                        </tr>
                      </thead>
                      <tbody>
					  <?php
					   
						  if($module_menu[$module->id])
						  {
							  foreach($module_menu[$module->id] as $key => $menu)
							  {
								  $can_add = 0;
								  $can_edit = 0;
								  $can_remove = 0;
								  $can_view = 0;
								  $all_priv = 0;
								 
								  if(is_array($module_menu_privilege)){
									  
									      $can_add = $module_menu_privilege[$menu->id]['add'];
										  $can_edit = $module_menu_privilege[$menu->id]['edit'];
										  $can_remove = $module_menu_privilege[$menu->id]['remove'];
										  $can_view = $module_menu_privilege[$menu->id]['view'];
										  if($can_add == 1 && $can_edit == 1 && $can_remove == 1 && $can_view == 1){
											  $all_priv = 1;
										  }
								  }
								  
						?>
                        <tr>
                          <td ><?php echo $menu->name;?></td>
                         <!-- <td  ><input type="checkbox" name="all_<?php echo $menu->id;?>" id="all_<?php echo $menu->id;?>" class="flats" value="1" onchange="checkUncheck('<?php echo $module->id;?>','<?php echo $menu->id;?>')" <?php echo $all_priv==1?"checked":"";?>></td>-->
                          <td  ><input type="checkbox" name="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_add" id="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_add" class="flat" value="1" <?php echo $can_add==1?"checked":"";?>></td>
                          <td  ><input type="checkbox" name="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_edit" id="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_edit" class="flat" value="1" <?php echo $can_edit==1?"checked":"";?>></td>
                          <td  ><input type="checkbox" name="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_delete" id="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_delete" class="flat" value="1" <?php echo $can_remove==1?"checked":"";?>></td>
                          <td  ><input type="checkbox" name="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_view" id="menu_<?php echo $module->id;?>_<?php echo $menu->id;?>_view" class="flat" value="1" <?php echo $can_view==1?"checked":"";?>></td>
                        </tr>
							  <?php } } ?>
                         
                      </tbody>
                    </table>

                  </div>
                </div>
				
			 </div>
			</div>
				  <?php } } ?>
				
				    <div class="form-group">
					
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<input type="hidden" name="group_id" value="<?php echo $group->id;?>">
						<button type="submit" class="btn btn-success">Submit</button>
                          <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel</button>
                          
                        </div>
                      </div>

             </form>
			
			 
			<!--END of Listing Page-->

             
          </div>
        </div>
		
		<script type="text/javascript">
		function checkUncheck(moduleid,menuid){
                    alert('hi');
			 
			if($('#all_'+menuid).is(':checked'))
			{
				 
				$("[id^=menu_"+moduleid+"_"+menuid+"_]").prop('checked',true);
				 
			}else
			{
				$("[id^='menu_"+moduleid+"_"+menuid+"_']").prop('checked',false);
			}
		}
		</script>
        <!-- /page content -->