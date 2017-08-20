$(document).ready(function(){
	console.log("ready!");
	updateLoadingBar(10);
	$.post("ajax.php", {"p": JSON.stringify({"action": "fetchQuestionListAndAnswer"})}, function(data, status){
		updateLoadingBar(5);
		if (status != "success"){
			alert("获取数据时发生错误，请刷新页面。");
		}else if(data["statusCode"] != 200){
			alert("服务器返回了异常的错误代码\n详细信息:" + data["message"]);
		}else{
			var message = data["message"];
			$.each(message, function (questionId, question){
				updateLoadingBar(3);
				var answers = question["answers"];
				var newAnswer = '';
				$.each(answers, function (answerId, answerItem){
					if (answerItem != null){
						newAnswer += '<option value="' + answerItem + '">' + answerItem + '</option>';
					}
				})
				var newQuestion = '<tr><th scope="row">' + question["title"] + '</th><td><select multiple class="form-control" id="qid' + questionId + '" name="qid' + questionId + '[]">' + newAnswer + '</select></td></tr>';
				$("#questionSelectorTable tbody").append(newQuestion);
				$("#output").append('<option value="' + questionId + '" selected>' + question["title"] + '</option>');
				updateLoadingBar(2);
			});
			window.setTimeout(function(){
				$("#loadingDiv").remove();
				$("#content").attr('style', '');
			}, 500);
		}
	},"json")
});
function updateLoadingBar(plusValue){
	var currentProgressValue = parseInt($("#loadingProgressBar").attr('style').replace('width: ','').replace('%',''));
	var newValue = currentProgressValue + plusValue;
	$("#loadingProgressBar").attr('style', 'width: ' + newValue + '%');
}
function submitButtonSwitch(flag){
	if ($('#btnSubmit').prop('disabled')){
		console.log ('enabling button.');
		$('#btnSubmit').prop('disabled', false);
		$('#btnSubmit').text('数据分析');
	}else{
		console.log("disabling button.");
		$('#btnSubmit').prop('disabled', true);
		$('#btnSubmit').text('数据加载中...');
	}
}
function startDataAnalysis(){
	submitButtonSwitch('disable');
	$("#outputChartDiv").empty();
	var createRequest = {"action": "dataAnalysis", "input": {}, "output": null};
	for (i = 1; i < 55; i++){
		var valueArray = $("#qid" + i).val();
		if (valueArray.length > 0){
			createRequest["input"][i] = valueArray;
		}
	}
	createRequest["output"] = $("#output").val();
	$.post("ajax.php", {"p": JSON.stringify(createRequest)}, function (data, status){
		if (status != 'success'){
			alert('服务器错误，请重试。');
			submitButtonSwitch('enable');
		}else if(data['statusCode'] != 200){
			alert('内部错误。');
			submitButtonSwitch('enable');
		}else{
			$("#outputDiv").attr('style', '');
			var message = data['message'];
			$.each(message, function (outId, outItem){
				var newChart = '<div id="chartContainerQs' + outId + '" style="height: 300px; width: 100%;"></div>';
				$("#outputChartDiv").append(newChart);
				var dataChart = [];
				$.each(outItem['count'], function (answerText, answerCount){
					var dChart = {y: answerCount, label: answerText, legendText: answerText};
					dataChart.push(dChart);
				});
				var thisChart = new CanvasJS.Chart('chartContainerQs' + outId,{
					title: {
						text: outItem['title']
					},
					legend: {
						maxWidth: 350,
						itemWidth: 120
					},
					data: [{
						type: "pie",
						showInLegend: true,
						legendText: "{indexLabel}",
						indexLabel: "{label} - {y}",
						dataPoints: dataChart
					}]
				});
				thisChart.render();
			});
			submitButtonSwitch('enable');
		}
	});
}