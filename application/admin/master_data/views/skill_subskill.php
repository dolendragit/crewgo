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
        <?php if ($page_action == "add" || $page_action == "edit") { ?>
            <div id="form_errors" class="alert alert-danger fade in" style="display:none;">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <div id="form_errors_msg"></div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">

                        <div class="x_content">
                            <br/>

                            <form id="frm_skill" name="frm_skill" data-parsley-validate
                                  class="form-horizontal form-label-left" onsubmit=" return validateForm()"
                                  method="post">

                                <!--  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="select_industry">Industry<span
                                                class="required">*</span>
                                    </label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">

                                        <select name="select_industry" id="select_industry"
                                                class="form-control col-md-4 col-xs-4"
                                                onchange="updateSkillList(this.value)">
                                            <option value="0">--Select Industry--</option>
                                            <?php
                                            if ($industry_select) {
                                                foreach ($industry_select as $industry_option) {
                                                    ?>
                                                    <option value="<?php echo $industry_option->id; ?>" <?php echo set_select('select_industry', $industry_option->id, $industry_option->id == $sel_industry_id ? true : false); ?>>
                                                        <?php echo $industry_option->name; ?>
                                                    </option>
                                                <?php }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>-->


                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Skill
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">

                                        <select name="skill_id" id="skill_id" class="form-control col-md-4 col-xs-4 select_skill"
                                                onchange="checkSelectedSubSkill(this.value)">
                                            <option value="0">--Select Skill--</option>
                                            <?php
                                            if ($skills) {
                                                foreach ($skills as $skill) {
                                                    ?>
                                                    <option value="<?php echo $skill->id; ?>" <?php echo set_select('skill_id', $skill->id, $skill->id == $sel_skill_id ? true : false); ?>>
                                                        <?php echo $skill->name; ?>
                                                    </option>
                                                <?php }
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-3" for="last-name">Sub-Skill<span
                                                class="required">*</span>
                                    </label>
                                    <div class="col-sm-9">

                                        <select name="level_id[]" id="level_id" class="form-control col-md-4 col-xs-4"
                                                multiple="">
                                             
                                            <?php
                                            if ($subskills) {
                                                foreach ($subskills as $subskill) {
                                                    ?>
                                                    <option value="<?php echo $subskill->id; ?>" <?php echo set_select('level_id', $subskill->id, $subskill->id == in_array($subskill->id, $skill_subskill_id_array[$sel_skill_id]) ? true : false); ?>>
                                                        <?php echo $subskill->name; ?>
                                                    </option>
                                                <?php }
                                            }
                                            ?>

                                        </select>

                                    </div>
                                </div>


                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">

                                        <input type="hidden" name="hidden_id" id="hidden_id"
                                               value="<?php echo $subskill->id; ?>">
                                        <input type="hidden" name="page_action" value="<?php echo $page_action; ?>">
                                        <button type="submit"
                                                class="btn btn-success"><?php echo $page_action == 'edit' ? 'Update' : 'Submit'; ?></button>
                                        <button type="button" class="btn btn-primary" onclick="history.go(-1)">Cancel
                                        </button>

                                    </div>
                                </div>

                            </form>
                        </div>
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

                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>S.N.</th>
                                   <!-- <th>Industry</th>-->
                                    <th>Skill</th>
                                    <th>Subskill</th>
                                    <th>Action</th>
                                </tr>
                                </thead>


                                <tbody>
                                <?php
                                if ($skill_data_array) {
                                    $s = 1;
                                    foreach ($skill_data_array as $skill_id => $skill_name) {
                                        ?>
                                        <tr>
                                            <td><?php echo $s; ?></td>
                                            <!--<td><?php echo $industry_skill_id_array[$skill_id];?></td>-->
                                            <td><?php echo $skill_name; ?></td>
                                            <td><?php echo implode(",", $skill_subskill_name_array[$skill_id]); ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/master_data/mapping/skillSubSkill/edit') . '/' . $skill_id; ?>"
                                                   title="Edit"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                                                <a href="<?php echo base_url('admin/master_data/mapping/skillSubSkill/remove') . '/' . $skill_id; ?>"
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
</div>
<!-- /page content -->


<script>
    $('.select_skill').select2();
    function validateForm() {

        var error_msg = "";
        //fetch required field value
        skill_id = $('#skill_id').val();
        subskill_id = $('#level_id').val();

        var invalid = [];
        if (skill_id == "") {
            error_msg += "-- Skill is required.</br>";

            invalid.push('skill_id');
        }
        if (!subskill_id) {
            error_msg += " -- Subskill is required.</br>";
            invalid.push('level_id');
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

    $('#level_id').select2({

        placeholder: 'Select a subskill'
    });


    $('#level_id').on('select2:unselecting', function (evt) {
        remove_level_id = evt.params.args.data.id;
        sel_skill_id = '<?php echo $sel_skill_id;?>';
        is_record_deleted = 0;
        delete_msg = "";
        if (confirm('Do you really want to remove the data?') == true) {
            $("#mydiv").show();

            $.ajax({
                async: false,
                url: '<?php echo base_url('admin/master_data/mapping/removeSkillSubSkill');?>',
                type: 'post',
                dataType: 'json',
                data: 'skill_id=' + sel_skill_id + '&level_id=' + remove_level_id,
                success: function (data) {
                    if (data.status === 1) {
                        is_record_deleted = 1;
                        delete_msg = data.msg;

                    } else {

                        is_record_deleted = 0;
                        delete_msg = data.msg;
                    }
                }
            });
            $("#mydiv").hide();

            if (is_record_deleted === 1) {
                alert(delete_msg);
                return true;

            } else {
                alert(delete_msg);
                return false;

            }

        } else {
            return false;
            //nothing
        }

    });

    function updateSkillList(industry_id) {
        //ajax call to get skills list basec on selected industry
        $.ajax({
            async: false,
            url: '<?php echo base_url('admin/master_data/mapping/changeSkill');?>',
            type: 'post',
            dataType: 'json',
            data: 'industry_id=' + industry_id,
            success: function (data) {
                //clear the select skill options on each select industry change
                $('#skill_id').empty();

                //set default select skill option
                default_opt = $('<option/>');
                default_opt.val('0');
                default_opt.text('---Select Skill---');

                $('#skill_id').append(default_opt);

                //for debugging
                //console.log(data);
                //skills_no = data.length-1;
                //console.log("objects = " +(skills_no+1));


                index = 0;

                //loop through the array objects
                $.each(data, function (key, value) {
                    //loop through each index of array to get key value pair of skill-id and skill-name
                    $.each(data[index], function (k, v) {
                        console.log("index: " + index);
                        console.log(k + "-" + v);
                        //add option element
                        var opt = $('<option/>');
                        opt.val(k);
                        opt.text(v);
                        $('#skill_id').append(opt);
                    });
                    index++;
                });
            }
        });

    }

    function checkSelectedSubSkill(skill_id) {
        data_exist = 0;

        $.ajax({
            async: false,
            url: '<?php echo base_url('admin/master_data/mapping/checkSkillSubSkill');?>',
            type: 'post',
            dataType: 'json',
            data: 'skill_id=' + skill_id,
            success: function (data) {
                if (data === 1) {
                    data_exist = 1;
                } else {
                    data_exist = 0;
                }
            }
        });

        industry_id = $('#select_industry').val();
        //alert('industry= '+industry_id);
        if (data_exist === 1) {
            goto_url = "<?php echo base_url('admin/master_data/mapping/skillSubSkill/edit');?>/" + skill_id;
            document.location = goto_url;
        } else {
            goto_url = "<?php echo base_url('admin/master_data/mapping/skillSubSkill/add');?>/" + skill_id;
            document.location = goto_url;
        }
    }
    //+"/"+industry_id
   
</script>