<h3><?php echo $title ?></h3>
<form method="POST" action="<?php echo $url; ?>" id="register_form"  role="form"  class="form-horizontal" enctype="multipart/form-data" >	
    <div class="form-group">
        <div class="col-sm-12">
            <pre>
                <?php echo $raw_data; ?>
            </pre>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label"> * Json:</label>
        <div class="col-sm-9"><?php echo $raw_data; ?></div>
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