<!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>&nbsp;</h3>
                <ul class="nav side-menu">
				<?php
				$module_menu = array(
				 
				'module_id'=>''
				 
				);//to fetch only group modules
					$get_group_modules = getUserModuleMenu($module_menu);
                                        
					if($get_group_modules)
					{
						foreach($get_group_modules as $group_modules)
						{
				?>
                  <li><a><i class="fa <?php echo $group_modules->icon_class;?>"></i> <?php echo $group_modules->module_name;?> <span class="fa fa-chevron-down"></span></a>
                 <?php
				 $module_menu = array(
						 
						'module_id'=>$group_modules->module_id
						 
						);//to fetch only group modules
						
				$get_group_modules_menu = getUserModuleMenu($module_menu);
				
				//echo $this->db->last_query();
				
				//print_r($get_group_modules_menu);
				if($get_group_modules_menu)
				{
				 echo '<ul class="nav child_menu">';
				  foreach($get_group_modules_menu as $module_menu)
				  {
					  if($module_menu->is_secondary==1)
						  continue;
				?>				 
				   
                      <li><a href="<?php echo base_url($module_menu->page_url);?>"><?php echo $module_menu->menu_name;?></a></li>
                      
                     
				<?php
				  }
				echo '</ul>';
				}
				?>
                  </li>
				<?php 
				
						}
				    }
				?>
				  
                 
                </ul>
              </div>
          

            </div>
            <!-- /sidebar menu -->