<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="">            
            <div class="page-title">
              <div class="title_left">
               <?php echo getPageActionTitle($page_url,$page_title,$page_action);?>
              </div>	 
            </div>
            
                    <div class="clearfix"></div>
			<?php displayMessages();?>	
                    <div class="clearfix"></div>
			
     			
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top:5px;">
                                <div class="x_panel">
                                    <div class="x_content">
			 
                                        <!--Select industry options-->
                                        <div class="col-md-3 col-xs-9">
                                            <form method="post" accept-charset="utf-8" action="<?php echo site_url("admin/master_data/industry_access"); ?>">
                                                <label for="industry">Select Industry</label>
                                                <select name="industry" id="industry" onchange="this.form.submit()" class="form-control">
                                                    <option value="0">Select Industry</option>
                                                    <?php foreach($select_industry as $industry_opt)
                                                    {
                                                        $industry_id=$industry_opt->id;
                                                        $industry_name=$industry_opt->name;
                                                    ?>
                                
                                
                                                    <option value="<?php echo $industry_id;?>"
                                                        <?php echo set_select('industry',$industry_id,$industry_id==$selected_industry_id?true:false); ?>>
                                                        <?php echo $industry_name;?>
                                                    </option>
                                                    <?php }?> 
                                                </select>                                                                             
                                            </form>                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <!-- Listing Part--->
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_panel">  
                                            <div class="x_content">
                                                <table id="datatable" class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>S.N.</th>
                                                        <th>Customer</th>
                                                        <th>Access Grant To Industry</th>
                                                        <th>Description</th>                                                              
                        <!--                            <td  style="vertical-align: middle">Action</td>-->
                                                    </tr>                         
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    if($access)
                                                    {
                                                        $s=1;
                                                        foreach($access as $record)
                                                        {
                                                            $customer_user_id = $record->user_id;
                            
                                                        ?>
                                                    
                                                    <tr>
                                                        <td><?php echo $s;?></td>           
                                                        <td><?php echo $record->customer;?></td>                     
                                                        <td>
                                                            <?php if($record->access==1){ ?>                                                            
                                                                <input type="checkbox" name="has_access_<?php echo $customer_user_id?>" id="has_access_<?php echo $customer_user_id?>" value="1" checked="checked" onclick="accessChange(this,<?php echo $selected_industry_id;?>,<?php echo $customer_user_id;?>)"> Yes
                                                            <?php } 
                                                            elseif($record->access!=1)
                                                            { ?>                        
                                                                <input type="checkbox" name="has_access_<?php echo $customer_user_id?>" id="has_access_<?php echo $customer_user_id?>" value="1" onchange="accessChange(this,<?php echo $selected_industry_id;?>,<?php echo $customer_user_id;?>)">
                                                            <?php }?>
                                                        </td>
                                                        <td>
                                                            <input type="text" onblur="accessChange(has_access_<?php echo $customer_user_id;?>,<?php echo $selected_industry_id;?>,<?php echo $customer_user_id;?>)" class="form-control"name="description_<?php echo $customer_user_id?>" id="description_<?php echo $customer_user_id?>" value="<?php echo $record->description;?>">                            
                                                        </td>                                           
<!--                    <td>
                        <a href="<?php //echo base_url('admin/master_data/industry_access/edit').'/'.$customer_user_id;?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php //echo base_url('admin/master_data/industry_access/remove').'/'.$customer_user_id;?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
                    </td>-->
                                                    </tr>
                                                    <?php 
                                                        $s++;
                                                        }  
                                                    }else{?>
                                                    
                                                    <tr><td colspan="4">No Records</td></tr>  
                                                    <?php } ?>
                                                    </tbody>
                                                </table>  
                                            </div>
                                        </div>
                                    </div>
                                </div>	
    </div>	
<!--			    TODO Pagination not working-->
                        <script type="text/javascript">
                            $('#datatable').DataTable();                              
			</script>
		<!--END of Listing Page-->
                                         
</div>
        <!-- /page content -->
        
        
        <script>
 function validateForm() {
     
       var error_msg = ""; 
       //fetch required field value
      days = $('input[name=days]').val();
      
      var invalid = [];
      
        if(days==""){
           error_msg += " -- Days is required.</br>";
            invalid.push('days');
       }
     
       if(error_msg!=""){
       
         heading_error ="<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>"; 
         heading_error += error_msg;
         $('#form_errors_msg').html(heading_error);
         $('#form_errors').show();
         //alert(heading_error);
         first_invalid = invalid[0];
        // $('input[name='+first_invalid+']').focus();
        $(window).scrollTop(20);
         
         return false;
       }
       
      
        return true;
    }
    
    
//update database on has_access checkbox change and description change
 function accessChange(hasAccessBox,industry_id,user_id){
     //console.log(typeof hasAccessBox);

     access_value= hasAccessBox.checked;
     field_name = 'description_'+user_id;
     description = $('input[name='+field_name+']').val();
     data = {industry_id:industry_id, industry_access:access_value, customer_user_id: user_id , description:description};
     //alert('industry id = '+industry_id + '\nuser_id : ' + user_id + '\nhas access : ' + access_value + '\nDescription : ' + description + '\nfield_name : ' + field_name);
     if(access_value){
          $.post('<?php echo base_url('admin/master_data/changeIndustryAccess');?>',data);
     }
    
    else{
        //clear the description field
         $('input[name='+field_name+']').val('');
          $.post('<?php echo base_url('admin/master_data/changeIndustryAccess');?>',data);
    }
 }
 
</script>

    
