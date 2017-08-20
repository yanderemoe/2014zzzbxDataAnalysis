<?php
header('Content-Type: application/json');
function returnJson($statusCode, $message){
	$statusCode = intval($statusCode);
	exit (json_encode(array("statusCode" => $statusCode, "message" => $message)));
}
$GLOBALS["mysql"] = new mysqli("localhost", "other-2014zzzbx", "QQgXGiS0oGiQYok8", "other-2014zzzbx");
if ($GLOBALS["mysql"]->connect_error){
	returnJson(500, "数据库连接失败。");
}
function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}
function fetchQuestion($id = ''){
	$getQuestionSql = null;
	if ($id == ''){
		$getQuestionSql = $GLOBALS["mysql"]->query("select * from `options`");
	}else{
		$getQuestionSql = $GLOBALS["mysql"]->query("select * from `options` where `id` = '".intval($id)."'");
	}
	$questions = array();
	while($questionRes = mysqli_fetch_array($getQuestionSql)){
		$questions[$questionRes["id"]] = $questionRes["question"];
	}
	return $questions;
}
function fetchAnswer($id){
	$id = intval($id);
	$resArr = array();
	if ($id > 50){
		$getAnswerQuery = $GLOBALS["mysql"]->query("select distinct `$id` from 2014data order by `$id`;");
		while($ansArray = mysqli_fetch_array($getAnswerQuery)){
			array_push($resArr, $ansArray[$id]);
		}
	}else{
		array_push($resArr, "强烈同意");
		array_push($resArr, "同意");
		array_push($resArr, "反对");
		array_push($resArr, "强烈反对");
	}
	return $resArr;
}
if (isset($_POST["p"])){
	if (!isJson($_POST["p"])){
		returnJson(500, "Parameter \"p\" is not a json.");
	}
	$actionArray = json_decode($_POST["p"], true);
	if (!isset($actionArray["action"])){
		returnJson(500, "Parameter \"action\" not found.");
	}
	if ($actionArray["action"] == "fetchQuestionListAndAnswer"){
		$return = array();
		foreach (fetchQuestion() as $qId => $qTitle){
			$answers = fetchAnswer($qId);
			$question = array("title" => $qTitle, "answers" => $answers);
			$return[$qId] = $question;
		}
		returnJson(200, $return);
	}else if($actionArray["action"] = "dataAnalysis"){
		$fieldFilterSql = '';
		if (isset($actionArray["input"]) && isset($actionArray["output"])){
			foreach ($actionArray["input"] as $inputId => $inputValue){
				$inputId = intval($inputId);
				$thisFilter = '';
				foreach ($inputValue as $thisV){
					$thisV = $GLOBALS["mysql"]->real_escape_string($thisV);
					if (empty($thisFilter)){
						$thisFilter = "(`$inputId` = '$thisV'";
					}else{
						$thisFilter .= " or `$inputId` = '$thisV'";
					}
				}
				$thisFilter .= ')';
				$fieldFilterSql .= " and ".$thisFilter;
			}
		}
		$outputValue = array();
		foreach ($actionArray["output"] as $oV){
			$singleQs = array("title" => fetchQuestion($oV)[$oV], "count" => array());
			$answers = fetchAnswer($oV);
			foreach ($answers as $answer){
				if (!empty($answer)){
					$answerGetCount = $GLOBALS["mysql"]->query("select count(*) as c from 2014data where `$oV`='$answer' ".$fieldFilterSql);
					$obj = mysqli_fetch_object($answerGetCount);
					$count = $obj->c;
					$singleQs["count"][$answer] = intval($count);
				}
			}
			$outputValue[$oV] = $singleQs;
		}
		returnJson(200, $outputValue);
	}
}else{
	returnJson(500, "Parameter \"p\" does not exist.");
}
?>