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
        <?php //if($page_action == "add" || $page_action == "edit") { ?>
        <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <div id="form_errors_msg"></div>
        </div>

        <div class="row">

            <div class="x_panel">

                <div class="x_content">
                    <br/>
                    <form id="frm_lhc_doc" name="frm_holiday_calendar" data-parsley-validate
                          class="form-horizontal form-label-left" onsubmit="return validateForm(this)" method="post">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">

                                <div class="col-md-5 col-sm-3 col-xs-3"
                                     id="master_data_status_<?php echo $edit_day->country; ?>"
                                     style="margin-top:5px;">
                                    <label for="country">Select a Country<span class="required">*</span>
                                    </label>
                                    <select name="country" id="country" required="required"
                                            onchange="loadStates(this.value)" class="form-control col-md-4 col-xs-4">
                                        <?php foreach ($select_country as $country) {
                                            $country_id = $country->id;
                                            $country_name = $country->name;

                                            ?>
                                            <option value="">Select Country</option>
                                            <option value="<?php echo $country_id; ?>"
                                                <?php echo set_select('country', $country_name,$country_id==$edit_day->country_id?true:false); ?>>
                                                <?php echo $country_name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-md-5 col-sm-3 col-xs-3"
                                     id="master_data_status_<?php echo $edit_day->id; ?>" style="margin-top:5px;">
                                    <label for="state">Select a State<span class="required">*</span>
                                    </label>
                                    <select name="region" id="region" required="required"
                                            class="form-control col-md-4 col-xs-4">
                                        <option value="">Select State</option>

                                        <?php foreach ($select_region as $region) {
                                            $region_id = $region->id;
                                            $region_name = $region->name;
                                            $region_short = $region->short_name;
                                            ?>

                                            <option value="<?php echo $region_id;?>"
                                                <?php echo set_select('region',$region_id,$region_id==$edit_day->country_region_id?true:false); ?>>
                                                <?php echo $region_name;?>
                                            </option>

                                        <?php } ?>
                                        <option value="0"
                                            <?php if($page_action=='edit'){echo set_select('region','0',$edit_day->country_region_id==0?true:false);}?>>
                                            All
                                        </option>

                                    </select>

                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">

                                <div class="col-md-9 col-sm-6 col-xs-6">
                                    <label for="holiday_date">Date : </label>

                                    <input type="text" id="holiday_date" name="holiday_date"
                                           class="form-control col-md-7 col-xs-12"
                                           value="<?php if($page_action=='edit'){echo $edit_day->date;} else echo date('Y-m-d');?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-9 col-sm-6 col-xs-6">
                                    <label for="description">Description : </label>
                                    <textarea class="form-control col-md-7 col-xs-12"
                                              name="description" id="description" placeholder="Description" rows="2"><?php echo set_value("description", $edit_day->description); ?></textarea>

                                </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                                <div class="col-md-9 col-sm-6 col-xs-6">
                                    <input type="hidden" name="hidden_id" id="hidden_id"
                                           value="<?php echo $edit_day->id; ?>"/>
                                    <input type="hidden" name="page_action" value="<?php echo $page_action; ?>">
                                    <button type="submit"
                                            class="btn btn-success"><?php echo $page_action == 'edit' ? 'Update' : 'Submit'; ?></button>
                                    <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel
                                    </button>

                                </div>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END of Add From-->
    <?php if ($page_action != 'edit') { ?>
        <!-- Listing Page--->

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Holiday</th>
                                <!--<th>Country</th>-->
                                <th>State</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>


                            <tbody>
                            <?php
                            if ($holiday_data) {
                                $s = 1;
                                foreach ($holiday_data as $record) {
                                    ?>
                                    <tr>
                                        <td><?php echo $s; ?></td>
                                        <td><?php echo $record->name; ?></td>
                                        <td><?php if($record->country_region_id==0){ echo "ALL";}else{echo $record->region_name; }?></td>
                                        <td><?php echo $record->description; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/master_data/holiday_calendar/edit') . '/' . $record->id; ?>"
                                               title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                                            <a href="<?php echo base_url('admin/master_data/holiday_calendar/remove') . '/' . $record->id; ?>"
                                               title="Delete"
                                               onclick="return confirm('Do you really want to delete this data?')"><span
                                                        class="glyphicon glyphicon-remove"></span></a>
                                        </td>
                                    </tr>
                                    <?php
                                    $s++;
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="4">No Records</td>
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

        <script type="text/javascript">

            $('#datatable').DataTable();

        </script>
    <?php } ?>
    <!--END of Listing Page-->

</div>
<!--        /page content-->

<script>
    function validateForm(thisForm) {

        var error_msg = "";
        //fetch required field value
        description = $('input[name=description]').val();

        if (description == "") {

            error_msg += " -- Description is required.</br>";
            invalid.push('description');
        }


        if (error_msg != "") {

            heading_error = "<div stlye='font-weight:bold;text-align:left;'>Please correct the following errors and try again:</div>";
            heading_error += error_msg;
            $('#form_errors_msg').html(heading_error);
            $('#form_errors').show();
            first_invalid = invalid[0];
            $(window).scrollTop(20);

            return false;
        }


        return true;
    }
</script>

<!--       Bootstrap core JavaScript
    ================================================== 
     Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
<!--     IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>-->


<script>
    $(function () {
        $("#holiday_date").datepicker({
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true
        });
    });
</script>