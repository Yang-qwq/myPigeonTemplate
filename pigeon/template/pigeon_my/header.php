<?php
global $pigeon;
if (!$pigeon) {
	exit();
}
?>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=11">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/styles/github.min.css" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aplayer/dist/APlayer.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.fancybox@2.1.5/source/jquery.fancybox.css">
	<!-- <link rel="stylesheet" href="http://lab.mkblog.cn/sweetalert/dist/sweetalert.css"> -->
	<link rel="stylesheet" href="/pigeon/template/<?php echo $pigeon->config['template']; ?>/css/style.css">
	<link rel="shortcut icon" href="/pigeon/favicon.png">

	<title><?php echo $pigeon->config['sitename']; ?></title>

	<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.fancybox@2.1.5/source/jquery.fancybox.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.13.1/highlight.min.js"></script>
	<script src="/pigeon/template/<?php echo $pigeon->config['template']; ?>/js/highlight.pack.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/stevenjoezhang/live2d-widget@latest/autoload.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<script>
		fetch('https://v1.hitokoto.cn')
			.then(response => response.json())
			.then(data => {
				const hitokoto = document.getElementById('hitokoto_text')
				hitokoto.href = 'https://hitokoto.cn/?uuid=' + data.uuid
				hitokoto.innerText = data.hitokoto
			})
			.catch(console.error)
			.hitokoto.innerText = '加载失败 :('
	</script>

	<?php
	if (isset($_GET['s']) && ($_GET['s'] == 'login' || $_GET['s'] == 'register') && $pigeon->config['recaptcha_key'] !== '') {
		echo '<script src="https://recaptcha.net/recaptcha/api.js" async defer></script>';
	}
	?>
</head>

<body>
	<!-- Pigeon 1.0.170 Update start -->
	<div class="messagebg" id="messagebg" style="display: none;">
		<div class="messagebox">
			<table>
				<tbody>
					<tr>
						<td>
							<h2>提示信息 <small><i class="fa fa-close close-msg" onclick="closemsg()"></i></small></h2>
						</td>
					</tr>
					<tr>
						<td>
							<div id="msgcontent"></div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<!-- Update end -->
	<div id="imgscan" onclick="$(this).fadeOut();">
		<div class="imgcontent">
			<div class="imgrow">
				<img src="" id="imgsrc">
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 logo animate__animated animate__bounceInDown">
				<h2><a href="?"><?php echo $pigeon->config['sitename']; ?></a></h2>
				<p><?php echo $pigeon->config['description']; ?></p>
				<hr>
			</div>
			<div class="col-sm-9 animate__animated animate__bounceInLeft">
				<?php
				if (isset($_SESSION['user'])) {
				?>
					<p><textarea class="form-control newpost" placeholder="在想些什么？" id="newpost"></textarea></p>
					<table style="width: 100%;">
						<tr>
							<td style="width: 40%;">
								<select class="form-control" id="ispublic">
									<option value="0">所有人可见</option>
									<option value="1">登录后可见</option>
									<option value="2">仅自己可见</option>
								</select>
							</td>
							<td>
								<!--<input type="checkbox" checked="checked" id="ispublic" style="margin-top: 8px;">&nbsp;&nbsp;公开消息（无需登录即可查看）</input>-->
								<button class="btn btn-primary pull-right" onclick="newpost()"><i class="fa fa-twitter"></i>&nbsp;&nbsp;立即发布</button>
							</td>
						</tr>
					</table>
					<hr>
					<center>
						<p><a href="?">公共时间线</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?user=<?php echo $_SESSION['user']; ?>">我的时间线</a></p>
					</center>
					<div id="alert_success"></div>
					<div id="alert_danger"></div>
				<?php
				}
				?>