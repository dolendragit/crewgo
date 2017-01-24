<style type="text/css">
	 .form-control{
	height: 23px;
    padding: 2px 12px;
    font-size: 11px;
}
</style>

<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <div class="page-title">
        <div class="title_left">
            <h3>
                Wage Rates
            </h3>
        </div>
    </div>
    <div class="clearfix">
    </div>


    <!--main content-->
    <div class="row">
    	<div class="panel panel-default">
    		<div class="panel-content">
    			<div class="row content-wrapper">
    				<?php $this->load->view('home/alert');?>
	    			<div class="col-sm-3">
	    				<form class="form-horizontal" method="get" action="<?php echo base_url('lhc/wagerule');?>">
						  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">Country</label>
						    <div class="col-sm-6">
						      <select class="form-control country" name="country" required>
						      	<option value="">Select Country</option>
						      	<?php foreach ($country as $val): ?>
						      		<option value="<?php echo $val->id; ?>" <?php echo ($this->input->get('country') == $val->id) ? 'selected' : '';?>><?php echo $val->name; ?></option>
						        <?php endforeach;?>
						      </select>
						    </div>
						  </div>

						  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">State</label>
						    <div class="col-sm-6">
						      <select class="form-control state" name="state" required>
						      	<option value="">Select State</option>
						      </select>
						    </div>
						  </div>


						   <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">Skill</label>
						    <div class="col-sm-6">
						      <select class="form-control skill" name="skill">
						      	<option value="0">Select skill</option>
						      	<?php foreach ($skillsForLhcUser as $skills): ?>
						      		<option value="<?php echo $skills->id; ?>" <?php echo ($this->input->get('skill') == $skills->id) ? 'selected' : '';?>><?php echo $skills->name; ?></option>
						      	<?php endforeach;?>
						      </select>
						    </div>
						  </div>


						   <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label">Subskill</label>
						    <div class="col-sm-6">
						      <select class="form-control subskill" name="subskill">
						      	<option value="0">Select subskill</option>
						      </select>
						    </div>
						  </div>
	    			</div>

	    			<div class="col-sm-3">
	    				<h4 class="heading">Shift Types</h4>
	    				<ul class="shift-type">
	    				<?php foreach ($shiftTypes as $value): ?>
	    					<li><?php echo "<strong>" . $value->name . "</strong>"; ?> <?php echo $value->time_from; ?> - <?php echo $value->time_to; ?></li>
	    				<?php endforeach;?>
	    				</ul>
	    			</div>

	    			<div class="col-sm-3">
	    				  <div class="form-group">
						    <label for="inputEmail3" class="col-sm-2 control-label" style="margin-top: 8px;">Calendar</label>
						    <div class="col-sm-4">
						      <select class="form-control calendar">
						      	<option value="<?php echo date('Y') ?>"><?php echo date('Y') ?></option>
						      	<option value="<?php echo date('Y') - 1 ?>"><?php echo date('Y') - 1 ?></option>
						      </select>
						    </div>
						  </div>

						  <div class="clearfix"></div>
						  <div class="date-holder">
						  	<span class="calendars"><?php foreach ($calendar as $value): ?><?php echo date('d-M', strtotime($value->date)) . ','; ?><?php endforeach;?></span>
						  </div>
	    			</div>

	    		</div>
    		</div>

    		<div class="panel-footer">
    			<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i>&nbsp;Filter</button>
    		</div>
    	</form>

    	</div>


   		
   	<!--special day rule-->
    <?php $this->load->view('specialdayrule');?>	
    <?php $this->load->view('overtimerule');?>	
    <?php $this->load->view('breaksnottaken');?>	
    <?php $this->load->view('workhours');?>	
    <!--special day rule-->
 	



    </div>
    <!--end of main content-->
</div>


<script type="text/javascript">


$(function(){
	$('.country').trigger('change');
	$('.skill').trigger('change');
});

    $('#myform').on('submit', function(){
  		var formLength = "<?php echo count($specialDayRule);?>";	
  		for(var i=0; i < formLength; i++){	
  			if(($('#times-'+i).val() === "" && $('#manual-'+i).val() === "") || ($('#times-'+i).val() == "0.00" && $('#manual-'+i).val() == "0.00")){
  				$('#times-'+i).css('border', '1px solid red');
  				$('#manual-'+i).css('border', '1px solid red');
  				alert("Either ph or times value is required");
  				return false;
  			} else if($('#times-'+i).val() != "" && $('#manual-'+i).val() != ""){
  				$('#times-'+i).css('border', '1px solid red');
  				$('#manual-'+i).css('border', '1px solid red');
  				alert("Both ph and times can't contain value");
  				return false;
  			} 
  			
  		}

    });


    $("#myformOne").on('submit', function(){
    	var formLengthOne = "<?php echo count($overtimeRule);?>";	
  		for(var i=0; i < formLengthOne; i++){
  			if(($('#timesone-'+i).val() === "" && $('#manual-'+i).val() === "") || ($('#timesone-'+i).val() == "0.00" && $('#manualone-'+i).val() == "0.00")){
  				$('#timesone-'+i).css('border', '1px solid red');
  				$('#manualone-'+i).css('border', '1px solid red');
  				alert("Either ph or times value is required");
  				return false;
  			} else if($('#timesone-'+i).val() != "" && $('#manualone-'+i).val() != ""){
  				$('#timesone-'+i).css('border', '1px solid red');
  				$('#manualone-'+i).css('border', '1px solid red');
  				alert("Both ph and times can't contain value");
  				return false;
  			} 
  			
  		}
    });

    $("#myformTwo").on('submit', function(){
    	var formLengthTwo = "<?php echo count($breakRule);?>";	
  		for(var i=0; i < formLengthTwo; i++){
  			 if($('#manualtwo-'+i).val() === ""){
  				$('#manualtwo-'+i).css('border', '1px solid red');
  				alert("Please add some value");
  				return false;
  			} 
  			
  		}
    });

     $("#myformThree").on('submit', function(){
    	var formLengthThree = "<?php echo count($workHour);?>";	
  		for(var i=0; i < formLengthThree; i++){
  			 if($('#manualthree-'+i).val() === ""){  
  			   $('#manualthree-'+i).css('border', '1px solid red');			 	
  				alert("Please add some value");
  				return false;
  			} 
  			
  		}
    });


	$('.country').on('change', function(){
		var selectedCountry = $('.country option:selected').val();
		$.ajax({
			type: "post",
			url: "<?php echo base_url(); ?>" + 'lhc/wagerule/getStates',
			data: {country: selectedCountry},
			dataType: 'json',
			success: function(result){
				$('.state').html('');

				  var fromurl;
				  var parsed = parseURLParams("<?php echo $_SERVER["REQUEST_URI"] ;?>");
				  if (typeof parsed === "undefined"){
				  		fromurl = "";
				  } else{
				  		fromurl = parsed.state[0];
				  }
		          $.each(result, function(key, val){

		            var textselect;
		            if (fromurl == val.id){
		              textselect = 'selected';
		            } else {
		              textselect = '';
		            }
		           state = `<option value="`+val.id+`"`+textselect+`>`+val.name+`</option>`;
		           $('.state').append(state);
		          });
			},
		});
	});


	$('.skill').on('change', function(){
    var skill = $('.skill option:selected').val();
     $.ajax({
        type: "POST",
        url:  "<?php echo base_url(); ?>" + "lhc/staff/industrySkill",
        data: {myvalue: skill},
        dataType: 'json',
        cache:false,
        success: function(result){
         $('.subskill').html('');

         	var fromurl;
       		var parsed = parseURLParams("<?php echo $_SERVER["REQUEST_URI"] ;?>");
       		 if (typeof parsed === "undefined"){
				  	fromurl = "";
				} else {
					fromurl = parsed.subskill[0];
				}

          $.each(result, function(key, val){

            var textselect;
            if (fromurl == val.id){
              textselect = 'selected';
            } else {
              textselect = '';
            }
           subskill = `<option value="`+val.id+`"`+textselect+`>`+val.name+`</option>`;
           $('.subskill').append(subskill);
          });
        },
      });
  });



	$('.calendar').on('change', function(){
		var selectedYear = $('.calendar option:selected').val();

		$.ajax({
			type: 'POST',
			url: "<?php echo base_url(); ?>" + 'lhc/wagerule/getCalendar',
			data: {selectedYear: selectedYear},
			dataType: 'json',
			beforeSend: function(){

			},
			success: function(result){
				$('.calendars').html('');


				$.each(result, function(obj, val){

					var month = new Array();
					month[0] = "Jan";
					month[1] = "Feb";
					month[2] = "Mar";
					month[3] = "Apr";
					month[4] = "May";
					month[5] = "Jun";
					month[6] = "Jul";
					month[7] = "Aug";
					month[8] = "Sept";
					month[9] = "Oct";
					month[10] = "Nov";
					month[11] = "Dec";


					var myDate = new Date(val.date);
					//myDate.getDate() + '-' + month[myDate.getMonth()] + ',';

					$('.calendars').append(myDate.getDate() + '-' + month[myDate.getMonth()] + ', ');
				});

			}

		});
	});


	function parseURLParams(url) {
	    var queryStart = url.indexOf("?") + 1,
	        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
	        query = url.slice(queryStart, queryEnd - 1),
	        pairs = query.replace(/\+/g, " ").split("&"),
	        parms = {}, i, n, v, nv;

	    if (query === url || query === "") return;

	    for (i = 0; i < pairs.length; i++) {
	        nv = pairs[i].split("=", 2);
	        n = decodeURIComponent(nv[0]);
	        v = decodeURIComponent(nv[1]);

	        if (!parms.hasOwnProperty(n)) parms[n] = [];
	        parms[n].push(nv.length === 2 ? v : null);
	    }
	    return parms;
}


</script>



	

   


