function isNumber(evt) {
    
   var charCode = (evt.which) ? evt.which : evt.keyCode;
   
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true;
     
}

function changeMasterDataStatus(request_url,master_data,id){
     $.ajax({
        url:request_url,
        type:'post',
        dataType: 'json',
        data:'master_data='+master_data+'&tbl_id='+id,
            success:function(data){
               if(data.status == 1){
                   $("#master_data_status_"+id).html(data.msg);
               }else{
                   alert(data.msg);
               }
        }
    });
   
}

//change table order
function changeMasterDataOrder(request_url,master_data,data){
     $.ajax({
        url:request_url,
        type:'post',
        dataType: 'json',
        data:'master_data='+master_data+'&tbl_id='+data,
            success:function(data){
              //yes
        }
    });
   
}

//change table order
function changeWRDataOrder(request_url,master_data,data){
     $.ajax({
        url:request_url,
        type:'post',
        dataType: 'json',
        data:'master_data='+master_data+'&tbl_id='+data,
            success:function(data){
              //yes
        }
    });
   
}
 
    $(document).ajaxStart(function () {
       //ajax request went so show the loading image
       $("#mydiv").show();
    });
  $(document).ajaxStop(function () {
      //got response so hide the loading image
        $("#mydiv").hide();
   });


  


/*
** LHC Industry restriction javascript
** Add Restriction Industry
*/
  $(document).on('click', '#btnAddIndustry', function(){
    $('#saveIndusrty').html('Save');
    $('#modalTitle').html('Add Industry');

    
    $('#saveIndusrty').removeClass('btn-danger');
    $('#saveIndusrty').addClass('btn-success');
    $("#modalPasw").modal('show');
    $('#modalPasw').on('click', '#saveIndusrty', function(){
      $.ajax({
        type: "POST",
        url: checkPaswUrl, 
        datatype: 'applicaion/json',
        data: $('form#formPasw').serialize(),
        success: function(data) { 
          $('#errorMsgArea').hide();
          $('#errorMsg').html('');
          $('#saveIndusrty').removeAttr('disabled', 'disabled');
          var data_obj = JSON.parse(data);
          if(data_obj.status=='error'){
            $('#errorMsgArea').show();
            $('#errorMsg').html(data_obj.message);  
          }else if(data_obj.status=='success'){

            $.ajax({
              type: "POST",
              url: addIndustryUrl, 
              datatype: 'applicaion/json',
              data: $('form#formAddIndustry').serialize(),
              success: function(res) { 
                console.log(res);
                $('#errorMsgArea').hide();
                $('#errorMsg').html('');     
                var res_obj = JSON.parse(res);
                if(res_obj.status=='success'){
                  $('#successMsgArea').show();
                  $('#successMsg').html(res_obj.message); 
                  $('#saveIndusrty').attr('disabled', 'disabled');
                  setTimeout( function(){
                    location.reload();
                  }, 300);

                } else if(res_obj.status=='error'){
                  $('#errorMsgArea').show();
                  $('#errorMsg').html(res_obj.message);
                  $('#saveIndusrty').attr('disabled', 'disabled');
                  setTimeout( function(){
                    location.reload();
                  }, 300);
                }
              }
            });
          }
        }
      });
    });
  })

/*
** LHC Industry restriction javascript
** Remove Restriction Industry
*/
  $(document).on('click', '.remove_industry', function(){
    var inId = $(this).parent().data('id');
    $('#saveIndusrty').html('Remove');
    $('#saveIndusrty').removeClass('btn-success');
    $('#saveIndusrty').addClass('btn-danger');


    $("#modalPasw").modal('show');
    $('#modalPasw').on('click', '#saveIndusrty', function(){
      $.ajax({
        type: "POST",
        url: checkPaswUrl, 
        datatype: 'applicaion/json',
        data: $('form#formPasw').serialize(),
        success: function(data) { 
          $('#errorMsgArea').hide();
          $('#errorMsg').html('');
          $('#saveIndusrty').removeAttr('disabled', 'disabled');
          var data_obj = JSON.parse(data);
          if(data_obj.status=='error'){
            $('#errorMsgArea').show();
            $('#errorMsg').html(data_obj.message);  
          }else if(data_obj.status=='success'){
            $.ajax({
              type: "POST",
              url: removeIndustryUrl, 
              datatype: 'applicaion/json',
              data: {id: inId},
              success: function(res) { 
                console.log(res);
                $('#errorMsgArea').hide();
                $('#errorMsg').html('');     
                var res_obj = JSON.parse(res);
                if(res_obj.status=='success'){
                  $('#successMsgArea').show();
                  $('#successMsg').html(res_obj.message);
                  $('#saveIndusrty').attr('disabled', 'disabled');
                  setTimeout( function(){
                    location.reload();
                  }, 300);

                } else if(res_obj.status=='error'){
                  $('#errorMsgArea').show();
                  $('#errorMsg').html(res_obj.message);
                  $('#saveIndusrty').attr('disabled', 'disabled');
                  setTimeout( function(){
                    location.reload();
                  }, 300);
                }
              }
            });
          }
        }
      });
    });
  })



