<style>
	#drop {
   	min-height: 15vh;
    max-height: 30vh;
    overflow: auto;
    width: calc(100%);
    border: 5px solid #929292;
    margin: 10px;
    border-style: dashed;
    padding: 10px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}
	#uploads {
		min-height: 15vh;
	width: calc(100%);
	margin: 10px;
	padding: 10px;
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	}
	#uploads .img-holder{
	    position: relative;
	    margin: 1em;
	    cursor: pointer;
	}
	#uploads .img-holder:hover{
	    background: #0095ff1f;
	}
	#uploads .img-holder .form-check{
	    display: none;
	}
	#uploads .img-holder.checked .form-check{
	    display: block;
	}
	#uploads .img-holder.checked{
	    background: #0095ff1f;
	}
	#uploads .img-holder img {
		height: 39vh;
    width: 22vw;
    margin: .5em;
		}
	#uploads .img-holder span{
	    position: absolute;
	    top: -.5em;
	    left: -.5em;
	}
	#dname{
		margin: auto 
	}
img.imgDropped {
    height: 16vh;
    width: 7vw;
    margin: 1em;
}
.imgF {
    border: 1px solid #0000ffa1;
    border-style: dashed;
    position: relative;
    margin: 1em;
}
span.rem.badge.badge-primary {
    position: absolute;
    top: -.5em;
    left: -.5em;
    cursor: pointer;
}
label[for="chooseFile"]{
	color: #0000ff94;
	cursor: pointer;
}
label[for="chooseFile"]:hover{
	color: #0000ffba;
}
.opts {
    position: absolute;
    top: 0;
    right: 0;
    background: #00000094;
    width: calc(100%);
    height: calc(100%);
    justify-items: center;
    display: flex;
    opacity: 0;
    transition: all .5s ease;
}
.img-holder:hover .opts{
    opacity: 1;

}
button.btn.btn-sm.btn-rounded.btn-sm.btn-dark {
    margin: auto;
}
</style>
<?php include 'db_connect.php' ?>
<header>
	
</header>
<section class="mt-4 pt-4 pb-4">
	<form id="frm-upload">
	<div class="container">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-8 offset-md-2">
				<div class="form-group">
						<select name="type" id="" class="badge badge-secondary">
							<option value="1">Partager</option>
							<option value="0">Ajouter seulement</option>
						</select>
				</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8 offset-md-2">
				<div class="form-group">
						<textarea name="content" id="" cols="30" rows="4" placeholder="Write a content for your post here" class="form-control"></textarea>
				</div>
				</div>
			</div>
			<input type="file" id="chooseFile" multiple="multiple" onchange="displayIMG(this)" accept="image/x-png,image/gif,image/jpeg" style="display: none">
			  <div id="drop">
			  	<span id="dname" class="text-center">Déposer vos fichiers <br> or <br> <label for="chooseFile"><strong>Choisir une fichier</strong></label></span>
			  </div>
			  <div id="list"></div>
			  <div class="col-md-12">
					<button class="btn btn-sm btn-primary btn-block col-md-2  offset-md-10"><i class="fa fa-upload"></i> Téléverser</button>
				</div>
			
		</div>
	</div>
	</form>	
</section>
<div class="container-fluid mt-4">
	<div class="col-lg-10 offset-lg-1">
	<div class="card card-body">
		<div id="uploads">
			<?php
				$uploads = $conn->query("SELECT * from file_uploads order by unix_timestamp(date_uploaded) desc ");
				while($row=$uploads->fetch_assoc()):
			?>
			<div class="img-holder">
				<span>
					<div class="form-check">
					  <input class="form-check-input imgs" type="checkbox" value="<?php echo $row['id'] ?>" >
					</div>
				</span>
				<img src="assets/<?php echo $row['file_path'] ?>" alt="">
			<div class="opts">
				<button class="btn btn-sm btn-rounded btn-sm btn-dark btn-share" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-share"></i></button>
			</div>
			</div>
		<?php endwhile; ?>
		</div>
		</div>
	</div>
	
</div>
	<div class="imgF" style="display: none " id="img-clone">
			<span class="rem badge badge-primary" onclick="rem_func($(this))"><i class="fa fa-times"></i></span>
	</div>
<script>
	$('[name="type"]').change(function(){
		if($(this).val() == 0){
			$('[name="content"]').closest('.row').hide('slideUp')
		}else{
			$('[name="content"]').closest('.row').show('slideDown')

		}
	})

	$('#frm-upload').submit(function(e){
		e.preventDefault()
			$.ajax({
			url:'ajax.php?action=save_upload',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("File successfully uploaded",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
		
	})
	if (window.FileReader) {
  var drop;
  addEventHandler(window, 'load', function() {
    var status = document.getElementById('status');
    drop = document.getElementById('drop');
    var dname = document.getElementById('dname');
    var list = document.getElementById('list');

    function cancel(e) {
      if (e.preventDefault) {
        e.preventDefault();
      }
      return false;
    }

    // Tells the browser that we *can* drop on this target
    addEventHandler(drop, 'dragover', cancel);
    addEventHandler(drop, 'dragenter', cancel);

    addEventHandler(drop, 'drop', function(e) {
      e = e || window.event; // get window.event if e argument missing (in IE)   
      if (e.preventDefault) {
        e.preventDefault();
      } // stops the browser from redirecting off to the image.
      $('#dname').remove();
      var dt = e.dataTransfer;
      var files = dt.files;
      for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();

        //attach event handlers here...

        reader.readAsDataURL(file);
        addEventHandler(reader, 'loadend', function(e, file) {
          var bin = this.result;
          var imgF = document.getElementById('img-clone');
          	imgF = imgF.cloneNode(true);
          imgF.removeAttribute('id')
          imgF.removeAttribute('style')

          var img = document.createElement("img");
          var fileinput = document.createElement("input");
          var fileinputName = document.createElement("input");
          fileinput.setAttribute('type','hidden')
          fileinputName.setAttribute('type','hidden')
          fileinput.setAttribute('name','img[]')
          fileinputName.setAttribute('name','imgName[]')
          fileinput.value = bin
          fileinputName.value = file.name
          img.classList.add("imgDropped")
          img.file = file;
          img.src = bin;
          imgF.appendChild(fileinput);
          imgF.appendChild(fileinputName);
          imgF.appendChild(img);
          drop.appendChild(imgF)
        }.bindToEventHandler(file));
      }
      return false;

    });

    Function.prototype.bindToEventHandler = function bindToEventHandler() {
      var handler = this;
      var boundParameters = Array.prototype.slice.call(arguments);
      return function(e) {
        e = e || window.event; // get window.event if e argument missing (in IE)   
        boundParameters.unshift(e);
        handler.apply(this, boundParameters);
      }
    };
  });
} else {
  document.getElementById('status').innerHTML = 'Your browser does not support the HTML5 FileReader.';
}

function addEventHandler(obj, evt, handler) {
  if (obj.addEventListener) {
    // W3C method
    obj.addEventListener(evt, handler, false);
  } else if (obj.attachEvent) {
    // IE method.
    obj.attachEvent('on' + evt, handler);
  } else {
    // Old school method.
    obj['on' + evt] = handler;
  }
}
function displayIMG(input){

    	if (input.files) {
	if($('#dname').length > 0)
		$('#dname').remove();

    			Object.keys(input.files).map(function(k){
    				var reader = new FileReader();
				        reader.onload = function (e) {
				        	// $('#cimg').attr('src', e.target.result);
          				var bin = e.target.result;
          				var fname = input.files[k].name;
          				var imgF = document.getElementById('img-clone');
						  	imgF = imgF.cloneNode(true);
						  imgF.removeAttribute('id')
						  imgF.removeAttribute('style')
				        	var img = document.createElement("img");
					          var fileinput = document.createElement("input");
					          var fileinputName = document.createElement("input");
					          fileinput.setAttribute('type','hidden')
					          fileinputName.setAttribute('type','hidden')
					          fileinput.setAttribute('name','img[]')
					          fileinputName.setAttribute('name','imgName[]')
					          fileinput.value = bin
					          fileinputName.value = fname
					          img.classList.add("imgDropped")
					          img.src = bin;
					          imgF.appendChild(fileinput);
					          imgF.appendChild(fileinputName);
					          imgF.appendChild(img);
					          drop.appendChild(imgF)
				        }
		        reader.readAsDataURL(input.files[k]);
    			})
    			
rem_func()

    }
    }
function rem_func(_this){
		_this.closest('.imgF').remove()
		if($('#drop .imgF').length <= 0){
			$('#drop').append('<span id="dname" class="text-center">Déposer vos fichiers <br> or <br> <label for="chooseFile"><strong>Choisir une fichier</strong></label></span>')
		}
}
$(document).ready(function(){
	$('.btn-share').click(function(){
		uni_modal("Ecriver quelques chhoses ici",'share.php?id='+$(this).attr('data-id'))
	})
})
</script>