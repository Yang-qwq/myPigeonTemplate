<?php
global $pigeon;
if (!$pigeon) {
	exit();
}
?>
</div>
<div class="col-sm-3 animate__animated animate__bounceInRight">
	<p>
	<div class="input-group">
		<input type="text" id="search" class="form-control" placeholder="搜索">
		<span class="input-group-btn">
			<button class="btn btn-primary" onclick="search()" style="height: 34px;"><i class="fa fa-search"></i></button>
		</span>
	</div>
	</p>
	<hr>

	<!-- 公告以及提示板块 -->
	<div class="row message">
		<p>
		<h3 style="color: green">公告：</h3>
		<p>本站头像显示服务由<abbr title="Gravatar是Globally Recognized Avatar的缩写,是gravatar推出的一项服务，意为“全球通用头像”">Gravatar</abbr>提供，请先注册Gravatar后以便显示头像！</p>
		<a href="https://gravatar.com/" target="_blank">https://gravatar.com/</a>
		<p>此外，注册并登陆了之后就可以发表消息了哦~</p>
	</div>
	<div class="row message">
		<p>
		<h3 style="color: red">提示：</h3>
		</p>
		<p>本站已全面使用了ssl证书，大家可不必担心自己的账号会在本站泄露，或被中间者截获！</p>
		<p>此外，您的密码会被使用<abbr title="一种不可逆的概要算法，例如MD5，sha256等">摘要算法</abbr>保存，也不必在意管理员窃取密码的问题</p>
		<p></p>
	</div>

	<?php
	if (isset($_SESSION['user']) && isset($_SESSION['email'])) {
	?>
		<center>
			<img src="https://sdn.geekzu.org/avatar/<?php echo md5($_SESSION['email']); ?>?s=256" class="loginhead">
		</center>
		<h3><?php echo $_SESSION['user']; ?></h3>
		<p>欢迎回来！<a href="?s=logout&seid=<?php echo isset($_SESSION['seid']) ? $_SESSION['seid'] : ""; ?>">[退出登录]</a></p>
		<p>你的 Token（可用于 API 发布）</p>
		<p>
		<pre><?php echo $_SESSION['token']; ?></pre>
		</p>
	<?php
	} else {
	?>
		<p>欢迎来到本站，请登陆。</p>
		<div class="row">
			<div class="col-sm-6">
				<p><a href="?s=login"><button class="btn btn-primary right-btn">立即登录</button></a></p>
			</div>
			<div class="col-sm-6">
				<p><a href="?s=register"><button class="btn btn-success right-btn">注册账号</button></a></p>
			</div>
		</div>
	<?php
	}
	?>
	<hr>
	<p><b>输入一个时间来进行筛选</b></p>
	<p>时间格式：<?php echo date("Y-m-d H:i:s"); ?></p>
	<p>
	<div class="input-group">
		<input type="text" id="time" class="form-control">
		<span class="input-group-btn">
			<button class="btn btn-primary" placeholder="yyyy-mm-dd HH:ii:ss" onclick="setTime()">确定</button>
		</span>
	</div>
	</p>
	<hr>
	<!-- <iframe frameborder="no" border="0" marginwidth="0" marginheight="0" width=330 height=450 src="//music.163.com/outchain/player?type=0&id=6920064959&auto=1&height=430"></iframe> -->
</div>
</div>
<p id="hitokoto"><a href="#" id="hitokoto_text">:D 获取中...</a></p>
<div class="row">
	<div class="col-sm-12">
		<hr>
		<!-- 版权以及友链区域 -->
		<p>&copy; <?php echo date("Y"); ?> <?php echo $pigeon->config['sitename']; ?> | Powered by <a href="https://github.com/kasuganosoras/Pigeon" target="_blank">Pigeon</a></p>
		<p>友链交换：</p>
		<p><a href="https://net-r-studio.top/">NRS</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://blog.1107-1108.top/">1107の个人博客</a></p>
	</div>
</div>
<script type="text/javascript">
	var seid = '<?php echo isset($_SESSION['seid']) ? $_SESSION['seid'] : ""; ?>';
	var auto_refresh = true;
	var ptime = '';
	var psearch = '';
	var puser = "<?php $user = isset($_GET['user']) ? $_GET['user'] : "";
					echo str_replace('"', "", $user); ?>";
	var storage = '<?php echo $_SESSION['ids']; ?>';
	var dismiss_success = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	var dismiss_danger = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
	var isblur = false;
	var pagetitle = document.title;
	hljs.initHighlightingOnLoad();

	function setTime() {
		ptime = $("#time").val();
		RefreshHome();
	}

	function search() {
		psearch = $("#search").val();
		RefreshHome();
	}

	function newpost() {
		var htmlobj = $.ajax({
			type: 'POST',
			url: "?s=newpost&seid=" + seid,
			data: {
				ispublic: $("#ispublic").val(),
				content: $("#newpost").val()
			},
			async: true,
			error: function() {
				// 修改：使用swal
				swal("服务器出现错误！", htmlobj.responseText, "error")
				return;
			},
			success: function() {
				$("#newpost").val("");
				RefreshHome();
				return;
			}
		});
	}

	function RefreshHome() {
		current_page = '1';
		auto_refresh = true;
		var htmlobj = $.ajax({
			type: 'GET',
			url: "?s=timeline",
			data: {
				page: '1',
				time: ptime,
				user: puser,
				search: psearch
			},
			async: true,
			error: function() {
				// 修改：使用swal
				swal("服务器出现错误！", htmlobj.responseText, "error")
				return;
			},
			success: function() {
				var ids = htmlobj.getResponseHeader('ids');
				if (storage != ids) {
					$("#pagecontent").html(htmlobj.responseText);
					if (isblur && storage != '') {
						document.title = "[新消息] " + pagetitle;
					}
					storage = ids;
					$('pre code').each(function(i, block) {
						hljs.highlightBlock(block);
					});
					$('.message img').click(function() {
						imgsrc.src = this.src;
						$("#imgscan").fadeIn();
					});
				}
				return;
			}
		});
	}

	function loadMore() {
		auto_refresh = false;
		var newPage = parseInt(current_page) + 1;
		var htmlobj = $.ajax({
			type: 'GET',
			url: "?s=timeline",
			data: {
				ajax: 1,
				page: newPage,
				time: ptime,
				user: puser,
				search: psearch
			},
			async: true,
			error: function() {
				return;
			},
			success: function() {
				$(".loadMore").css({
					display: 'none'
				});
				$("#pagecontent").append(htmlobj.responseText);
				current_page = newPage;
				$('.message img').click(function() {
					imgsrc.src = this.src;
					$("#imgscan").fadeIn();
				});
				return;
			}
		});
	}

	function deletepost(id) {
		auto_refresh = false;
		var htmlobj = $.ajax({
			type: 'GET',
			data: {
				s: "deletepost",
				id: id,
				seid: seid
			},
			async: true,
			error: function() {
				// 修改：使用swal
				swal("服务器出现错误！", htmlobj.responseText, "error")
				return;
			},
			success: function() {
				storage = '';
				// 修改：使用swal
				swal("消息删除成功！", "", "success")
				RefreshHome();
				return;
			}
		});
	}

	function changepublic(id, newstatus) {
		auto_refresh = false;
		var htmlobj = $.ajax({
			type: 'GET',
			data: {
				s: "changepublic",
				id: id,
				newstatus: newstatus,
				seid: seid
			},
			async: true,
			error: function() {
				// 修改：使用swal
				swal("服务器出现错误！", htmlobj.responseText, "error")
				return;
			},
			success: function() {
				storage = '';
				// 修改：使用swal
				swal("消息状态修改成功！", "", "success")
				RefreshHome();
				return;
			}
		});
	}

	// 禁用不再需要的函数
	// function SuccessMsg(text) {
	// 	$("#alert_success").html(dismiss_success + text + "</div>");
	// 	$("#alert_success").fadeIn(500);
	// }

	// function ErrorMsg(text) {
	// 	$("#alert_danger").html(dismiss_danger + text + "</div>");
	// 	$("#alert_danger").fadeIn(500);
	// }
	/* Pigeon 1.0.170 Update start */
	var editid = '';
	var isopenmsgbox = false;

	function showmsg(text) {
		$("#messagebg").fadeIn(300);
		$("#msgcontent").html(text);
		isopenmsgbox = true;
	}

	function closemsg() {
		if (isopenmsgbox) {
			$("#messagebg").fadeOut(300);
			isopenmsgbox = false;
		}
	};

	function progressshow(text) {
		$("#messagebg").fadeIn(300);
		$("#msgcontent").text(text);
	}

	function progressunshow() {
		$("#messagebg").fadeOut(300);
	}

	function edit(id) {
		var htmlobj = $.ajax({
			type: 'GET',
			data: {
				s: "getmsg",
				id: id,
				seid: seid
			},
			async: true,
			error: function() {
				// 修改：使用swal
				swal("服务器出现错误！", htmlobj.responseText, "error")
				return;
			},
			success: function() {
				editid = id;
				try {
					var data = JSON.parse(htmlobj.responseText);
					var public_0 = "";
					var public_1 = "";
					var public_2 = "";
					switch (data.public) {
						case "0":
							var public_0 = ' selected="selected"';
							break;
						case "1":
							var public_1 = ' selected="selected"';
							break;
						case "2":
							var public_2 = ' selected="selected"';
							break;
					}
					showmsg('<p>请输入内容</p><p><textarea class="form-control newpost editpost" placeholder="在想些什么？" id="editpost">' + data.content.replace("<", "&lt;").replace(">", "&gt;").replace("&", "&amp;").replace(" ", "&nbsp;") + '</textarea></p><table style="width: 100%;margin-bottom: 12px;"><tr><td style="width: 40%;"><select class="form-control" id="edit_ispublic"><option value="0"' + public_0 + '>所有人可见</option><option value="1"' + public_1 + '>登录后可见</option><option value="2"' + public_2 + '>仅自己可见</option></select></td><td><button class="btn btn-primary pull-right" onclick="submitedit()"><i class="fa fa-twitter"></i>&nbsp;&nbsp;保存修改</button></td></tr></table>');
				} catch (e) {
					// 修改：使用swal
					swal("错误！", e.message, "error")
				}
				return;
			}
		});
	}

	function submitedit() {
		var htmlobj = $.ajax({
			type: 'POST',
			url: "?s=editpost&id=" + editid,
			data: {
				ispublic: $("#edit_ispublic").val(),
				content: $("#editpost").val()
			},
			async: true,
			error: function() {
				closemsg();
				alert("错误：" + htmlobj.responseText);
				return;
			},
			success: function() {
				$("#editpost").val("");
				closemsg();
				storage = '';
				// 修改：使用swal
				swal("消息内容保存成功！", "", "success")
				RefreshHome();
				return;
			}
		});
	}
	/* Update end */
	window.onload = function() {
		setInterval(function() {
			if (auto_refresh) {
				RefreshHome();
			}
		}, 10000);
		$('pre code').each(function(i, block) {
			hljs.highlightBlock(block);
		});
		$('.message img').click(function() {
			imgsrc.src = this.src;
			$("#imgscan").fadeIn();
		});
	}
	window.onblur = function() {
		isblur = true;
	}
	window.onfocus = function() {
		isblur = false;
		document.title = pagetitle;
	}
</script>
<!-- require APlayer -->
<script src="https://cdn.jsdelivr.net/npm/aplayer/dist/APlayer.min.js"></script>
<!-- require MetingJS -->
<script src="https://cdn.jsdelivr.net/npm/meting@2/dist/Meting.min.js"></script>
<!-- MetingJS使用 看自己决定放置的位置 -->
<meting-js server="netease" type="playlist" id="7123866465" autoplay="true" order="random" fixed="true">
</meting-js>
</body>

</html>