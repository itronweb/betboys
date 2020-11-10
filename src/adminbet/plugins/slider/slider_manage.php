<?php 
	GLOBAL $content;
	$content['title']       = "تغییر اسلایدر";
	require('../../layouts/sidebar.php');
	
	$UploadROUTE	=	'../../../upload/slider/';
	$url		=	$UploadROUTE.SZRNameFILE(@$_POST['url']);
	$url_link	=	'http://'.$_SERVER[HTTP_HOST].'/'.$url;
	
	$fields = array( 'title' ); 
	$append = array( 'status' => 1, 'type'=> 1, 'url'=>$url_link );
	$_POST = array_merge($_POST, $append );
	$fields = array_merge( $fields, array_keys($append) );
	mysqli_query($cn,"SET NAMES 'utf8'");
	if ( (@$_POST['go'] == "edit") ) {

		$data = []; foreach ($fields as $item) if (!isset($_POST[$item])) { unset($item); } else { $data[$item] = $_POST[$item]; }
		$edit = dbWrite( 'slideshow', [ 'id' => $_POST['id'] ], $data );
		file_put_contents($url, B64IMG($_REQUEST['url_src']));

		$status = ($edit ? '<div class="panel panel-warning"><div class="panel-heading">اسلایدر با موفقیت ویرایش شد.</div></div>' : '<div class="panel panel-danger"><div class="panel-heading">مشکلي در ويرايش اسلایدر به وجود آمده است.</div></div>');
	}
	if (isset($_GET['edit']) && ($_GET['edit'] !== "")) {
		$row_slide    = @array_pop( dbRead( 'slideshow',    ['id'=>sanitizeInt($_GET['edit'])], 1 ) );
	}
	$avatarFormats = ".jpg,.png,.gif,.jpeg";
?>
<style>
    .user-avatar {
        margin-top: 10px;
        margin-bottom: 10px;
        float: right;
        width: 96px;
        height: 96px;
        object-fit: cover;
    }
</style>
<?php echo @$status; ?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <div class="row">
        <div class="col-sm-12">				
            <div class="panel panel-card recent-activites">
                <!-- Start .panel -->
                <div class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss=""></a>
                    </div>
                </div>
                <form method="post" action="" class="panel-body">
                    <div class="pull-left">
                        <button type="submit" class="btn btn-primary button btn-pahn">
                            <span class="fa fa-floppy-o"></span> ثبت
                        </button>
                    </div>
                    <input type="hidden" name="go" value="edit">
                    <input type="hidden" name="id" value="<?php echo @$row_slide['id']; ?>">
                    <section data-type="all" >
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <img style="width:40%" src="<?= $row_slide['url'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6"><label class="control-label"><?php echo @$row_slide['title']; ?></label>
                                <input name="title" type="text" class="form-control" value="<?php echo @$row_slide['title']; ?>">
                            </div>
                        <script>
                            function url(input) {
                                if (input.files && input.files[0]) {
                                    var file = input.files[0];
                                    
                                    if (file.name.length < 1) ; else
                                    if (file.size > 2000000) alert("خطا: فايل از حد مجاز بزرگ تر است"); else
                                    if (file.type != 'image/png' && file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg' ) {
                                        alert("خطا: فرمت فايل مجاز نيست!");
                                    }
                                    
                                    else {
                                        var reader = new FileReader();

                                        reader.onload = function (e) {
                                            $('url.url').attr('src', e.target.result);
                                            $('input.url_src').attr('value', e.target.result); 
                                        }
                                        reader.readAsDataURL(input.files[0]);
                                    }
                                }
                            }
                            $(function() {
                                $("input[name=url]").change(function(){
                                    url(this);
                                });
                            });
                        </script>
                            <div class="col-sm-4">
                                <label class="control-label">عکس مطلب</label>
                                <img style="display:none;" class="user-avatar url img-circle" src="<?= $avatarURL; ?>" />
                                <input style="display:none;" class="form-control url_src" name="url_src" type="text" value="<?php echo @$row_slide['url']; ?>">
                                <input name="url" type="file" accept="<? $avatarFormats; ?>" class="form-control">
                            </div>
                        </div>
                    </section>
                    
                    <br><br>
                </form>
            </div>
            <!-- End .panel --> 
        </div>
    </div>
<?php require('../../layouts/footer.php');?>