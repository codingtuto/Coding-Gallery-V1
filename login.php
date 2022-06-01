<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    include('db_connect.php');
    include('header.php');

	
    ?>

    <style>
    body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main-login{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:50%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:50%;
		height: calc(100%);
		background:#59b6ec61;
		display: flex;
		align-items: center;
		background: url(assets/img/recruitment-cover.jpg);
	    background-repeat: no-repeat;
	    background-size: cover;
	}
	#login-right .card{
		margin: auto;
		z-index: 1
	}
	.logo {
    margin: auto;
    font-size: 8rem;
    background: white;
    padding: .5em 0.7em;
    border-radius: 50% 50%;
    color: #000000b3;
    z-index: 10;
    height: calc(75%);
    max-width: calc(80%);
}
.logo img{
 	height: calc(100%);
    max-width: calc(100%);
}
div#login-right::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: calc(100%);
    height: calc(100%);
    /*background: #000000e0;*/
}
    </style>
    <body id="page-top">
        <!-- Navigation-->
        <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
      </div>
      
<main id="main-login">
	<div id="login-left">
  			<div class="logo">
  				<img src="assets/img/downloaded/gallery-logo.png" alt="">
  			</div>
  		</div>

  		<div id="login-right">
  			<div class="col-lg-8 offset-lg-2">
  			<div class="card">
  				<div class="card-body">
			  				<h4 class="text-center">Connexion</h4>
  					<form id="login-form" >
  						<div class="form-group">
  							<label for="email" class="control-label">Email</label>
  							<input type="text" id="email" name="email" class="form-control">
  						</div>
  						<div class="form-group">
  							<label for="password" class="control-label">Mot de passe</label>
  							<input type="password" id="password" name="password" class="form-control">
  						</div>
  						<center><button class="btn-sm btn-block btn-wave col-md-4 btn-primary">Se connecter</button> or <br>
  							<a href="javascript:void(0)" id="signup_btn">Créer un compte</a></center>
  					</form>
  				</div>
  			</div>
  			</div>
  		</div>
</main>
<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continuer</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-righ t"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div id="preloader"></div>
     
        
       <?php include('footer.php') ?>
    </body>

    <script>
    	
    	$('#signup_btn').click(function(){
    		uni_modal("Create Account","signup.php")
    	})
    	$('#login-form').submit(function(e){
			e.preventDefault()
			$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
			if($(this).find('.alert-danger').length > 0 )
				$(this).find('.alert-danger').remove();
			$.ajax({
				url:'ajax.php?action=login',
				method:'POST',
				data:$(this).serialize(),
				error:err=>{
					console.log(err)
			$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

				},
				success:function(resp){
					if(resp == 1){
						location.href ='index.php?page=home';
					}else{
						$('#login-form').prepend('<div class="alert alert-danger">Identifiant incorrect.</div>')
						$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
					}
				}
			})
		})
    </script>
    <?php $conn->close() ?>

</html>
