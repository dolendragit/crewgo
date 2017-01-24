<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo base_url('webservice').'/'.uri_string(); ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >	
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Dates:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="dates" value="" placeholder="yyyy-mm-dd,yyyy-mm-dd,yyyy-mm-dd,yyyy-mm-dd," /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Location:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="location" value="" /></div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Meeting Place:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="meeting_place" value="" /></div>
    </div>
        <div class="form-group">
        <label class="col-sm-3 control-label"> * Skills:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="skill" value="" placeholder="1,2,3,4" /></div>
    </div>
        <div class="form-group">
        <label class="col-sm-3 control-label"> * Level:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="level" value="" placeholder="1,2,3,4" /></div>
    </div>
        <div class="form-group">
        <label class="col-sm-3 control-label"> * Quantity:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="quantity" value="" placeholder="1,2,3,4" /></div>
    </div>
  <!--   <div class="form-group">
        <span id="skill-content">
        <label class="col-sm-3 control-label"> * Skills:</label>
        
        <div class="col-sm-3"><label>Skill</label>
            <select class="form-control" name="skill[]">
                <option></option>
            </select>
        </div>
        <div class="col-sm-3"><label>Level</label>
            <select class="form-control" name="level[]">
                <option></option>
            </select>
        </div>
        <div class="col-sm-2"><label>Quantity</label>
            <select class="form-control" name="quantity[]">
                <option></option>
            </select>
        </div>
        </span>
            <div class="col-sm-1"><a href="javascript:;" class="btn btn-danger btn-sm add_skills">ADD</a></option>
            </select>
        </div>
    </div> -->
  <div class="form-group">
        <label class="col-sm-3 control-label"> * Notes:</label>
        <div class="col-sm-9"><input class="form-control" type="text" name="notes" value="" /></div>
    </div>
    <div class="form-group"><div class="col-sm-offset-3 col-sm-9"><input type="submit" class="btn btn-default" value="Submit" /></div></div>
</form>

<script type="text/javascript">
$(function(){
    $(document).on("click",".add_skills",function() {
        var html = $('#skill-content').html();
        $('#skill-content').append(html);

    });
});
</script>