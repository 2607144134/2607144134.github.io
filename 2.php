<?php
if(!empty($_FILES['xhrUpload']['name'][0])){
	$json=array('err'=>0,'msg'=>'ok');		
	for($key=0;$key<count($_FILES['xhrUpload']['name']);$key++){
		$filename='/upload/'.date('mdHis',$_SERVER['REQUEST_TIME']).$key.'.'.pathinfo($_FILES['xhrUpload']['name'][0],PATHINFO_EXTENSION);
		if(!is_dir($pathname=dirname('.'.$filename)))mkdir($pathname);
		if(preg_match('/\.(gif|jpg|jpeg|bmp|png|mp4|avi)$/',$filename) && move_uploaded_file($_FILES['xhrUpload']['tmp_name'][$key],'.'.$filename)){
			$json['dir'][]=$filename;
		}else{
			$json['err']=1;	
			$json['msg']='上传失败';
		}		
	}
	header("Content-type: application/json");
	exit(json_encode($json));
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>上传内容测试</title>
<script src="//libs.baidu.com/jquery/1.10.1/jquery.min.js"></script>
</head>
<body>
<style type="text/css">
.upload_box{
	position:relative;
	display:inline-block;
	background:#46b8da;
	border-radius:5px;
	padding:6px 20px;
	font-size:13px;
	line-height:1em;	
	margin:5px 0;
	color:#fff;
}
.upload_box input{
	position:absolute;
	opacity:0.01;
	width:100%;
	left:0;
	top:0;
}
</style>
<script type="text/javascript">
$(function(){
	$('.upload_box input').upload('',function(d){
		d = JSON.parse(d);
		if(d.dir){
			$('#txt_test').val(d.dir.join('\n'));
			$('#img_test').attr('src','.'+d.dir[0]);
		}
	});
});
$.fn.upload = function(u,o,f) {
	$(this).each(function(){
		typeof(o) != 'object' && (f = o) && (o = null);
		var s,p,w,g,b,x,r,t=this;
		function y(n) {
			p=n>1?'上传成功！':'上传进度 '+(Math.floor(n*100))+'%';
			console.log(p,'上传进度');
		};
		$(this).change(function() {
			y(0.001);
			b = new FormData();
			for (var k in o) {b.append(k,typeof(o[k]) == 'object' ? JSON.stringify(o[k]) : o[k])}
			for (k in this.files) b.append('xhrUpload[]',this.files[k]);  
			x = window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject("Microsoft.XMLHTTP");
			x.upload.addEventListener('progress',function(e) {
				if (e.lengthComputable) y(e.loaded / e.total)
			},false);
			x.addEventListener('load',function(d) {
				y(2);
				f.call(t,d.target.response,d)
			},false);
			x.open('POST',u);
			x.send(b)
		})		
	});
}
</script>	
<div class="upload_box"><input type="file" accept=".jpg,.jpeg,.png,.gif,.bmp" multiple value="上传图片">上传卡片图片</div>
<br>
<textarea id="txt_test" cols="40" rows="6" ></textarea>
<br>
<br>
<img id="img_test" width="300">
</body>
</html>