<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>中国政治坐标系2014年数据分析</title>
		<link href="../static/bootstrap.css" rel="stylesheet">
		<link href="../static/main.css" rel="stylesheet">
		<script src="../static/jquery.min.js"></script>
		<script src="../static/canvasjs.min.js"></script>
		<script src="function.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="header clearfix">
				<nav>
					<ul class="nav nav-pills pull-right">
						<li role="presentation"><a href="../">Toolbox</a></li>
					</ul>
				</nav>
				<h3 class="text-muted">中国政治坐标系2014年数据分析</h3>
			</div>
			<div class="panel panel-default" id="loadingDiv">
				<div class="panel-heading">数据加载</div>
				<div class="panel-body">
					<div class="progress">
						<div class="progress-bar progress-bar-info" role="progressbar" id="loadingProgressBar" style="width: 0%"></div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">说明</div>
				<div class="panel-body">数据来源于 http://zuobiao.me/ 于 2014 年公开的数据。<br>开源于 <a href="https://github.com/yanderemoe/2014zzzbxDataAnalysis" target="_blank">Github</a>。</div>
			</div>
			<div class="form-group" id="content" style="display:none">
				<form method="POST">
					<div class="panel panel-default">
						<div class="panel-heading">条件组</div>
						<div class="panel-body">
							<p>使用选择框过滤投了这个选项的人，如过滤出投了（人权大于主权 = 同意、强烈同意）的人的票。使用 Ctrl（Mac 下 Command） 多选、取消选择。不选则对此问题无过滤。</p>
						</div>
						<table class="table" id="questionSelectorTable">
							<thead>
								<tr>
									<th>问题</th>
									<th>可能的答案</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">输出组</div>
						<div class="panel-body">
							<select multiple class="form-control" id="output" name="output[]">
							</select>
						</div>
					</div>
					<button type="button" id="btnSubmit" class="btn btn-primary btn-lg" onclick="startDataAnalysis();">数据分析</button>
				</form>
			</div>
			<div class="panel panel-default" id="outputDiv" style="display:none">
				<div class="panel-heading">数据输出结果</div>
				<div class="panel-body" id="outputChartDiv">
				</div>
			</div>
			<footer class="footer">
				<p>&copy; 2017 <a href="//tools.yandere.moe">tools.yandere.moe</a></p>
			</footer>
		</div>
	</body>
</html>