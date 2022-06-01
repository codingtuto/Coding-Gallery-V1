<?php session_start() ?>
<div class="container-fluid">
  <form action="" id="signup-frm">
    <div id="msg"></div>
    <div class="form-group">
      <label for="" class="control-label">Prenom</label>
      <input type="text" name="firstname" required="" class="form-control">
    </div>
     <div class="form-group">
      <label for="" class="control-label">Surnom</label>
      <input type="text" name="middlename" class="form-control">
    </div>
    <div class="form-group">
      <label for="" class="control-label">Nom</label>
      <input type="text" name="lastname" required="" class="form-control">
    </div>
    <div class="form-group">
      <label for="" class="control-label">Numero</label>
      <input type="text" name="contact" required="" class="form-control">
    </div>
    <div class="form-group">
      <label for="" class="control-label">Addresse</label>
      <textarea cols="30" rows="3" name="address" required="" class="form-control"></textarea>
    </div>
    <div class="form-group">
      <label for="" class="control-label">Email</label>
      <input type="email" name="email" required="" class="form-control">
    </div>
    <div class="form-group">
      <label for="" class="control-label">Mot de passe</label>
      <input type="password" name="password" required="" class="form-control">
    </div>
    <button class="button btn btn-info btn-sm">Créer</button>
  </form>
</div>

<style>
  #uni_modal .modal-footer{
    display:none;
  }
</style>
<script>
  $('#signup-frm').submit(function(e){
    e.preventDefault()
    start_load()
    if($(this).find('.alert-danger').length > 0 )
      $(this).find('.alert-danger').remove();
    $.ajax({
      url:'ajax.php?action=signup',
      method:'POST',
      data:$(this).serialize(),
      error:err=>{
        console.log(err)
    end_load()

      },
      success:function(resp){
        if(resp == 1){
          location.href ='<?php echo isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php?page=home' ?>';
        }else{
          $('#signup-frm').prepend('<div class="alert alert-danger">Email already exist.</div>')
           end_load()
        }
      }
    })
  })
</script>