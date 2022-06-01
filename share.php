<div class="cantainer-fluid">
	<form id="manage-post">
		<div class="form-group">
			<input type="hidden" name="file_id" value="<?php echo $_GET['id'] ?>">
			<textarea name="content" id="" cols="30" rows="10" class="form-control"></textarea>
		</div>
	</form>
</div>
<script>
	$('#manage-post').submit(function(e){
		e.preventDefault()
		start_load();
		$.ajax({
			url:"ajax.php?action=save_posts",
			method:"POST",
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Success",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>