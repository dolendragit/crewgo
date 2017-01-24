   <?php if ($this->session->flashdata('error') != ""):?>
       <div class="alert alert-error alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Error!</strong> <?php echo $this->session->flashdata('error');?>
      </div>
    <?php endif; ?>


     <?php if ($this->session->flashdata('success') != ""):?>
       <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong> <?php echo $this->session->flashdata('success');?>
      </div>
    <?php endif; ?>


     <?php if ($this->session->flashdata('warning') != ""):?>
       <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>warning!</strong> <?php echo $this->session->flashdata('warning');?>
      </div>
    <?php endif; ?>



     <?php if ($this->session->flashdata('info') != ""):?>
       <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>info!</strong> <?php echo $this->session->flashdata('info');?>
      </div>
    <?php endif; ?>