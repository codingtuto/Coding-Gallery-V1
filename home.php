<?php 
include 'db_connect.php'; 
?>
<style>
.pImg-holder{
    width: calc(100%);
    height: 72vh;
}
.pImg-holder .carousel-item,.pImg-holder .carousel-item img,.pImg-holder .carousel,.pImg-holder .carousel-inner{
    width: calc(100%);
    height:  calc(100%);
}
.posts .fa-heart{
   color: gray;
    font-size: 2rem;
}
.posts .fa-heart.active{
   color: red;
}
.comment .usr{
    font-size: 18px
}
.comment p{
   margin: unset;
}
.comment .dt{
    font-style: italic;
    margin-left : 1rem
}
.comment{
    width: calc(100%);
}
</style>
        
<div class="container-sm mt-4">
    <div class="col-lg-8 offset-lg-2">
        <?php 
        $query = $conn->query("SELECT p.*,concat(u.firstname,' ',u.middlename,' ',u.lastname) as name FROM posts p inner join users u on u.id = p.user_id order by unix_timestamp(p.date_created) desc ");
        while($row=$query->fetch_assoc()):
        ?>
            <div class="card mt-4 mb-4 posts">
                <div class="card-body" style="padding-left: unset;padding-right:unset" data-id="<?php echo $row['id'] ?>">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-8">
                                <p><b><?php echo ucwords($row['name']) ?></b>
                                <small><i> <?php echo date("M d,Y h:i A",strtotime($row['date_created'])) ?></i></small>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <p><?php echo $row['content'] ?></p>
                            </div>
                        </div>
                        <div class="row">
                            
                            <?php
                            $_f = array();
                            $files = $conn->query("SELECT * from file_uploads where id in (".$row['file_ids'].") ");
                            while($frow = $files->fetch_assoc()):
                                $_f[] = $frow;
                            endwhile;
                            $active = 0;
                            ?>
                            <div class="pImg-holder">
                                <div id="carousel_<?php echo $row['id'] ?>" class="carousel slide" >
                                     <ol class="carousel-indicators">
                                        <?php 
                                        $active = 0;
                                        foreach ($_f as $f):
                                            $a = 'active';
                                            if($active > 0)
                                                $a = ''
                                    ?>
                                        <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $a ?>" class="<?php echo $a ?>"></li>
                                       
                                    <?php $active++; ?>
                                    <?php endforeach; ?>
                                      </ol>
                                    <div class="carousel-inner">
                                    <?php 
                                        $active = 0;
                                        foreach ($_f as $f):
                                            $a = 'active';
                                            if($active > 0)
                                                $a = ''
                                    ?>
                                    <div class="carousel-item <?php echo $a ?>">
                                    <img src="assets/<?php echo $f['file_path'] ?>" class="d-block w-100" alt="">
                                    </div>
                                    <?php $active++; ?>
                                    <?php endforeach; ?>
                                    </div>
                                    <a class="carousel-control-prev" href="#carousel_<?php echo $row['id'] ?>" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Précédent</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel_<?php echo $row['id'] ?>" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Suivant</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="row comment-field">
                            <?php
                            $data = $conn->query("SELECT c.*,concat(u.firstname,' ',u.middlename,' ',u.lastname) as uname FROM comments c inner join users u on u.id = c.user_id where c.post_id = ".$row['id']);
                            while($c = $data->fetch_assoc()):
                            ?>
                             <div class="comment">
                                <div class="col-md-12">
                                    <p><b class="usr"><i><?php echo ucwords($c['uname']) ?></i></b> <small class="dt"><?php echo date("M d,Y",strtotime($c['date_created'])) ?></small><br><small class="cntnt"><?php echo $c['comment'] ?></small></p>
                                </div>
                            </div>
                                  

                        <?php endwhile; ?>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-11">
                                <textarea name="comment[<?php echo $row['id'] ?>]" cols="30" rows="1" class="form-control cmt-field" placeholder="Ecrire une commentaire"></textarea>
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn btn-primary cmt_btn" data-id="<?php echo $row['id'] ?>"><i class="fa fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<div class="comment_clone" style="display: none">
    <div class="comment">
        <div class="col-md-12">
            <p><b class="usr"><i>Sample User</i></b> <small class="dt">Jun 14,2014</small><br><small class="cntnt">woowwww.....</small></p>
            <hr>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.carousel').carousel({
            ride:false
        });
    $('.cmt-field').keypress(function(e){
        if(e.which == 13)
            $(this).closest('.cmt_btn').trigger('click')
    })
    $('.cmt_btn').click(function(){
        var id = $(this).attr('data-id')
        var comment = $('[name="comment['+id+']"]').val()
        if(comment == '')
            return false;
       $('[name="comment['+id+']"]').attr('disabled',true)
        var _this = $(this)
        _this.attr('disabled',true)
        $.ajax({
            url:"ajax.php?action=save_comment",
            method:"POST",
            data:{post_id: id,comment:comment},
            success:function(resp){
                resp = JSON.parse(resp)
                if(resp.status == 1){
                    var data  =resp.data
                    var f = $('.comment_clone .comment').clone()
                    f.find(".cntnt").html(data.comment)
                    f.find(".usr").html(data.user)
                    f.find(".dt").html(data.date)
                        $('[name="comment['+id+']"]').attr('disabled',false)
                        $('[name="comment['+id+']"]').val('')
                        _this.attr('disabled',false)
                    _this.closest('.card').find('.comment-field').append(f)

                }
            }
        })
    })
    })
</script>