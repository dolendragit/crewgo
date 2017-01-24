<!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>&nbsp;</h3>
                <ul class="nav side-menu">
				<?php
				$module_menu = array(
				 
				'module_id'=>''
				 
				);//to fetch only group modules
/*
			module.id AS module_id,
            menu.id AS menu_id, menu.name AS menu_name, menu.page_url,mp.can_add,mp.can_edit,mp.can_remove,mp.can_view,menu.is_secondary,
            module.icon_class'*/
					$get_group_modules = array(
										 (object) array('module_name'=>'Home',
												'module_id'=>0,
												'menu_id'=>31,
												'menu_name'=>'Home',
												'page_url'=>'customer/dashboard',
												'can_add'=>1,
												'can_edit'=>1,
												'can_remove'=>1,
												'can_view'=>1,
												'is_secondary'=>0,
												'icon_class'=>'fa-home'
											),
										 (object) array('module_name'=>'Create a new Job',
												'module_id'=>0,
												'menu_id'=>32,
												'menu_name'=>'Create a new Job',
												'page_url'=>'',
												'can_add'=>1,
												'can_edit'=>1,
												'can_remove'=>1,
												'can_view'=>1,
												'is_secondary'=>0,
												'icon_class'=>'fa-edit'
											),
										 (object) array('module_name'=>'Saved Jobs',
												'module_id'=>0,
												'menu_id'=>33,
												'menu_name'=>'Saved Jobs',
												'page_url'=>'',
												'can_add'=>1,
												'can_edit'=>1,
												'can_remove'=>1,
												'can_view'=>1,
												'is_secondary'=>0,
												'icon_class'=>'fa-list'
											),
										 (object) array('module_name'=>'Payment Setup',
												'module_id'=>0,
												'menu_id'=>34,
												'menu_name'=>'Payment Setup',
												'page_url'=>'',
												'can_add'=>1,
												'can_edit'=>1,
												'can_remove'=>1,
												'can_view'=>1,
												'is_secondary'=>0,
												'icon_class'=>'fa-opencart'
											),
										 (object) array('module_name'=>'Terms and Conditions',
												'module_id'=>0,
												'menu_id'=>35,
												'menu_name'=>'Terms and Conditions',
												'page_url'=>'',
												'can_add'=>1,
												'can_edit'=>1,
												'can_remove'=>1,
												'can_view'=>1,
												'is_secondary'=>0,
												'icon_class'=>'fa-eye'
											),
										 (object) array('module_name'=>'Privacy Policy',
												'module_id'=>0,
												'menu_id'=>36,
												'menu_name'=>'Privacy Policy',
												'page_url'=>'',
												'can_add'=>1,
												'can_edit'=>1,
												'can_remove'=>1,
												'can_view'=>1,
												'is_secondary'=>0,
												'icon_class'=>'fa-eye'
											)

										 );
					$get_group_modules = (object) $get_group_modules;
					// var_dump($get_group_modules);die;            
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
				
				$get_group_modules_menu = (object) array($group_modules);
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