<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
      rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link href="<?php echo base_url('assets/admin/select2/css/select2.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/admin/select2/js/select2.js'); ?>"></script>
<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <?php echo getPageActionTitle($page_url, $page_title, $page_action); ?>
            </div>


        </div>
        <div class="clearfix"></div>
        <?php displayMessages(); ?>
        <div class="clearfix"></div>

        <!--Add Form-->
        <?php if ($page_action == "add" || $page_action == "edit") {


            ?>
            <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div id="form_errors_msg"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="x_panel">

                        <div class="x_content">
                            <br/>

                            <form id="frm_ppe" name="frm_ppe" data-parsley-validate
                                  class="form-horizontal form-label-left" onsubmit=" return validateForm()"
                                  method="post">
                                <div class="form-group">
                                    <label class="control-label col-sm-1" for="Country">Country <span
                                                class="required">*</span>
                                    </label>
                                    <div class="col-sm-3">

                                        <select name="country_id" id="country_id" class="form-control">
                                            <?php if ($countries) {
                                                foreach ($countries as $country) {

                                                    ?>
                                                    <option value="<?php echo $country->id; ?>" <?php echo set_select('country_id', $country->id, @$sociable_hour->country_id == $country->id ? true : false); ?>><?php echo $country->name; ?></option>
                                                <?php }
                                            } ?>

                                        </select>


                                    </div>

                                    <label class="control-label  col-sm-2 " for="Sociable time from">Region:<span
                                                class="required">*</span>
                                    </label>
                                    <div class="col-sm-2   ">
                                        <select name="region_id" id="region_id" class="form-control"
                                                onchange="getSocaibleHoursView()">
                                            <option value="">--Select Region --</option>
                                            <?php if ($regions) {
                                                foreach ($regions as $region) {

                                                    ?>
                                                    <option value="<?php echo $region->id; ?>" <?php echo set_select('region_id', $region->id, @$sociable_hour->country_region_id == $region->id ? true : false); ?>><?php echo $region->name; ?></option>
                                                <?php }
                                            } ?>

                                        </select>
                                    </div>

                                    <label class="control-label col-sm-1  " for="Status"> Status</label>
                                    <div class="col-sm-2">
                                        <select name="status" id="status" class="form-control ">

                                            <option value="1" <?php echo set_select('status', 1, @$sociable_hour->status == '1' ? true : true); ?>>
                                                Show
                                            </option>
                                            <option value="0" <?php echo set_select('status', 0, @$sociable_hour->status == '0' ? true : false); ?>>
                                                Hide
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-1" for="first-name">Days <span
                                                class="required">*</span>
                                    </label>
                                    <div class="col-sm-3">
                                        <select name="days" id="days" class="form-control">
                                            <?php if (is_array($days)) {
                                                foreach ($days as $daykey => $day) {

                                                    ?>
                                                    <option value="<?php echo $daykey; ?>" <?php echo set_select('day', $daykey, @$sociable_hour->days == $daykey ? true : false); ?>><?php echo $day; ?></option>
                                                <?php }
                                            } ?>

                                        </select>

                                    </div>

                                    <label class="control-label  col-sm-2 " for="Sociable time from">Sociable time: from
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-sm-2   ">
                                        <input type="text" id="sociable_from_hour" name="sociable_from_hour"
                                               value="<?php echo set_value('social_from_hour', @$sociable_hour->sociable_from_hour); ?>"
                                               class="form-control" maxlength="10">
                                        <!--  <span class="input-group-btn">
                                           <button class="btn btn-default" type="button" style="background-color:#CCC;" ><i class="fa fa-clock-o"></i></button>
                                          </span>-->
                                    </div>
                                    <label class="control-label col-sm-1  " for="To"><span
                                                class="input-group-addon">To</span></label>
                                    <div class="col-sm-2   ">
                                        <input type="text" id="sociable_to_hour" name="sociable_to_hour"
                                               value="<?php echo set_value('social_to_hour', @$sociable_hour->sociable_to_hour); ?>"
                                               class="form-control" maxlength="10" onchange="getSociableHourNSpeak()">


                                    </div>


                                </div>


                                <div class="form-group">
                                    <label class="control-label col-sm-1" for="first-name">Sociable factor <span
                                                class="required">*</span>
                                    </label>
                                    <div class="col-sm-3">
                                        <input type="text" onkeypress="return isNumber(event)" id="sociable_factor"
                                               name="sociable_factor"
                                               value="<?php echo set_value('sociable_factor', @$sociable_hour->sociable_factor); ?>"
                                               class="form-control" maxlength="4">
                                    </div>

                                    <label class="control-label  col-sm-2 " for="non_sociable_hour">Non-Sociable hour
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-sm-2 ">
                                        <input type="text" onkeypress="return isNumber(event)" id="non_sociable_hour"
                                               name="non_sociable_hour"
                                               value="<?php echo set_value('non_sociable_hour', @$sociable_hour->non_sociable_hour); ?>"
                                               class="form-control" maxlength="4">

                                    </div>
                                    <label class="control-label col-sm-1  " for="ns_peak">NS peak <span
                                                class="required">*</span></label>
                                    <div class="col-sm-2   ">
                                        <input type="text" id="ns_peak" name="ns_peak"
                                               value="<?php echo set_value('ns_peak', @$sociable_hour->ns_peak); ?>"
                                               class="form-control">
                                        <!-- <span class="input-group-btn">
                                              <button class="btn btn-default" type="button" style="background-color:#CCC;" ><i class="fa fa-clock-o"></i></button>
                                        </span>-->

                                    </div>


                                </div>


                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class=" col-sm-6  col-md-offset-3">
                                        <input type="hidden" name="hidden_id" id="hidden_id"
                                               value="<?php echo @$sociable_hour->id; ?>">
                                        <input type="hidden" name="page_action" value="<?php echo $page_action; ?>">
                                        <button type="submit"
                                                class="btn btn-success"><?php echo $page_action == 'edit' ? 'Update' : 'Submit'; ?></button>
                                        <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel
                                        </button>

                                    </div>
                                </div>

                            </form>
                        </div>
                        <!-- List related data -->

                    <!--    <div class="row">
                            <div class="col-lg-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Sociable Hours
                                            <small>(For selected country and state )</small>
                                        </h2>
                                        <ul class="nav navbar-right panel_toolbox">
                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            </li>

                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content" id="sociable_hour_container">

                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>S.N.</th>
                                                <th>Country</th>
                                                <th>Region</th>
                                                <th>Days</th>
                                                <th>Time</th>
                                                <th>Sociable factor</th>
                                                <th>Non-sociable Hours</th>
                                                <th>NS peak</th>

                                                <th style="vertical-align: middle">Action</th>
                                            </tr>

                                            </thead>


                                            <tbody>
                                            <?php
                                            if ($sociable_hours) {
                                                $s = 1;
                                                foreach ($sociable_hours as $sociable_hour) {


                                                    ?>
                                                    <tr>
                                                        <td><?php echo $s; ?></td>
                                                        <td><?php echo $sociable_hour->country_name; ?></td>
                                                        <td><?php echo $sociable_hour->name; ?></td>
                                                        <td><?php echo $days[$sociable_hour->days]; ?></td>
                                                        <td><?php echo $this->model_master_data->formatTime($sociable_hour->sociable_from_hour) . '-', $this->model_master_data->formatTime($sociable_hour->sociable_to_hour); ?></td>
                                                        <td><?php echo $sociable_hour->sociable_factor; ?></td>
                                                        <td><?php echo $sociable_hour->non_sociable_hour; ?></td>
                                                        <td><?php echo $this->model_master_data->formatTime($sociable_hour->ns_peak); ?></td>

                                                        <td>
                                                            <a href="<?php echo base_url('admin/master_data/sociable_hours/edit') . '/' . $sociable_hour->id; ?>"
                                                               title="Edit"><span
                                                                        class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                                                            <a href="<?php echo base_url('admin/master_data/sociable_hours/remove') . '/' . $sociable_hour->id; ?>"
                                                               onclick="return confirm('Do you really want to delete this data?')"
                                                               title="Delete"><span
                                                                        class="glyphicon glyphicon-remove"></span></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $s++;
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="10">No Records</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>


                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        -->

                    </div>
                </div>
            </div>


            <!-- END of Add From-->
        <?php }else{ ?>
            <!-- Listing Page--->

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                            <?php $this->load->view('sociable_hours_list'); ?>

                            <!--       <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                          <tr>
                            <th >S.N.</th>
                             <th>Country</th>
                            <th>Region</th>
                            <th>Days</th>  
                            <th>Time</th>
                            <th>Sociable factor </th>
                            <th>Non-sociable Hours</th>
                            <th>NS peak</th>
                             
                            <th  style="vertical-align: middle">Action</th>
                        </tr>
                        
                      </thead>


                      <tbody>
                <?php
                            if ($sociable_hours) {
                                $s = 1;
                                foreach ($sociable_hours as $sociable_hour) {


                                    ?>
                    <tr>
                      <td ><?php echo $s; ?></td>
                      <td><?php echo $days[$sociable_hour->days]; ?></td>
                      <td><?php echo $this->model_master_data->formatTime($sociable_hour->sociable_from_hour) . '-', $this->model_master_data->formatTime($sociable_hour->sociable_to_hour); ?></td>
                      <td><?php echo $sociable_hour->sociable_factor; ?></td>
                      <td><?php echo $sociable_hour->non_sociable_hour; ?></td>
                      <td><?php echo $this->model_master_data->formatTime($sociable_hour->ns_peak); ?></td>
                       
                      <td>
                        <a href="<?php echo base_url('admin/master_data/sociable_hours/edit') . '/' . $sociable_hour->id; ?>" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="<?php echo base_url('admin/master_data/sociable_hours/remove') . '/' . $sociable_hour->id; ?>" onclick="return confirm('Do you really want to delete this data?')" title="Delete"><span class="glyphicon glyphicon-remove"></span></a>
                      </td>
                    </tr>
                <?php
                                    $s++;
                                }
                            } else {
                                ?>
                    <tr><td colspan="10">No Records</td></tr>
                <?php
                            }
                            ?>
                        
                         
                      </tbody>
                    </table>  -->
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">

                var table = $('#datatable').DataTable({
                    rowReorder: false
                });

                /* table.on( 'row-reorder', function ( e, diff, edit ) {
                 var result = 'Reorder started on row: '+edit.triggerRow.data()[1]+'<br>';

                 for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                 var rowData = table.row( diff[i].node ).data();

                 result += rowData[1]+' updated to be in position '+
                 diff[i].newData+' (was '+diff[i].oldData+')<br>';
                 }

                 $('#result').html( 'Event result:<br>'+result );
                 } );*/


            </script>
        <?php } ?>
        <!--END of Listing Page-->


    </div>
</div>
<!-- /page content -->


<script>
    function validateForm() {

        var error_msg = "";
        //fetch required field value
        days = $('input[name=days]').val();
        country_region_id = $('#region_id').val();

        sociable_from_hour = $('input[name=sociable_from_hour]').val();
        sociable_to_hour = $('input[name=sociable_to_hour]').val();
        sociable_factor = $('input[name=sociable_factor]').val();
        non_sociable_hour = $('input[name=non_sociable_hour]').val();
        ns_peak = $('input[name=ns_peak]').val();

        var invalid = [];
        if (country_region_id == "") {
            error_msg += " -- Region is required.</br>";
            invalid.push('country_region_id');
        }
        if (days == "") {
            error_msg += " -- Days is required.</br>";
            invalid.push('days');
        }
        if (sociable_from_hour == "") {
            error_msg += " -- Sociable time 'From' is required.</br>";
            invalid.push('sociable_from_hour');
        }
        if (sociable_to_hour == "") {
            error_msg += " -- Sociable time 'To' is required.</br>";
            invalid.push('sociable_to_hour');
        }
        if (sociable_factor == "") {
            error_msg += " -- Sociable factor is required.</br>";
            invalid.push('sociable_factor');
        }
        if (non_sociable_hour == "") {
            error_msg += " -- Non-sociable factor is required.</br>";
            invalid.push('non_sociable_hour');
        }
        if (ns_peak == "") {
            error_msg += " -- NS peak is required.</br>";
            invalid.push('ns_peak');
        }

        //check unque data
        if (country_region_id != "" && days != "") {

            $.ajax({
                async: false,
                url: '<?php echo base_url('admin/master_data/checkUniqeSociableData');?>',
                type: 'post',
                data: 'region_id=' + $('#region_id').val() + '&sociable_id=<?php echo $sociable_hour->id;?>&days=' + $('#days').val(),
                success: function (response) {
                    data = $.parseJSON(response);

                    if (data.success) {

                        error_msg += " -- Data already exist for selected country,state and days.</br>";

                    }
                }
            });

        }

        if (error_msg != "") {

            heading_error = "<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>";
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
    $('#sociable_to_hour').datetimepicker({

        format: 'HH:mm A'


    }).on('dp.change', function (e) {
        getSociableHourNSpeak();
    });

    $('#sociable_from_hour').datetimepicker({
        format: 'HH:mm A'

    }).on('dp.change', function (e) {
        getSociableHourNSpeak();
    });

    $('#ns_peak').datetimepicker({
        format: 'HH:mm A'

    });


    // $('#sociable_from_hour').datetimepicker({ timeFormat: 'HH:mm:ss p' });
    //$('#ns_peak').timepicker({ timeFormat: 'HH:mm:ss p' });

    function getSociableHourNSpeak() {
        sociable_from_hour = $('input[name=sociable_from_hour]').val();
        sociable_to_hour = $('input[name=sociable_to_hour]').val();

        //alert($("#sociable_from_hour").find("input").val());

        if (sociable_from_hour != "" && sociable_to_hour != "") {
            $.post('<?php echo base_url('admin/master_data/getNonsociableHourNSpeak');?>', 'sociable_from_hour=' + sociable_from_hour + '&sociable_to_hour=' + sociable_to_hour, function (response) {
                data = $.parseJSON(response);

                if (data.success == 1) {

                    $('#non_sociable_hour').val(data.non_sociable_hour);
                    $('#ns_peak').val(data.ns_peak);
                }else if(data.success == 2){
                    alert(data.message);
                    $('#sociable_to_hour').val('');
                    $('#non_sociable_hour').val('');
                    $('#ns_peak').val('');
                }
            });

        }
    }

    function getSocaibleHoursView() {
        region_id = $('#region_id').val();
        ;
        sociable_id = "<?php echo $sociable_hour->id;?>";

        $.post('<?php echo base_url('admin/master_data/getSociableNSpeakView');?>', 'region_id=' + region_id + '&sociable_id=' + sociable_id, function (response) {
            $('#sociable_hour_container').html(response);

        });
    }

    getSocaibleHoursView();


</script>
<script type="text/javascript">
    // $('#region_id').select2();
</script>
    
