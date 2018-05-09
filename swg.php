<?php
function json2swag($json)
{
	$data = json_decode($json);
	$swag = '
* @SWG\Response(
*     response="200",
*     description="接口响应",
*     @SWG\Schema(
';
	if(is_object($data))
	{
		$vars = get_object_vars($data);
		foreach($vars as $varName=>$varValue)
		{
			$swag .= _json2swg($varName , $varValue , 2);
		}
	}
	$swag.="*     )
* )";
	return $swag;
}

function _json2swg($varName,$varValue,$level)
{
	$swag = '';
	if(is_object($varValue))
	{
		$prefix = '*'.str_repeat("    ", $level);
		$swag .= $prefix.'@SWG\Property( property="'.$varName.'" ,type="object",'."\n";
		$subvars = get_object_vars($varValue);
		
		foreach($subvars as $varName=>$varValue)
		{
			$swag .= _json2swg($varName,$varValue,$level+1);
		}
		$swag .= $prefix."),\n";
	}else if(is_array($varValue)){
		$prefix = '*'.str_repeat("    ", $level);
		$swag .= $prefix.'@SWG\Property( property="'.$varName.'" ,type="array",'."\n";
		$swag .= $prefix.'@SWG\Items(type="object",'."\n";
		$swagInner= _json2swg('',$varValue[0],$level);
		$swagInners = explode("\n" , trim($swagInner));
		array_pop($swagInners);
		array_shift($swagInners);
		$swag .= implode("\n",$swagInners);
		
		$swag .= $prefix.")\n";
		$swag .= $prefix."),\n";

	}else{
		$varT = gettype($varValue);
		$swag .= '*'.str_repeat("    ", $level) 
		.'@SWG\Property( property="'.$varName.'" , type="'.$varT.'" , example="'.$varValue.'",description="'.$varName.' 描述"),'."\n";
	}

	return $swag;
}



if($_POST['action'] == '')
{

	echo '<html><Head><title>Json2Swag</title><script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script></head><body style="background:#EEE">';
	echo '<h1>Json2Swag <small>@vincent</small></h1> ';
	echo '<table style="width:100%;height:80%" cellpadding=0 cellspacing=0 border=0>
			<tr>
				<td width=50%>
					<textarea id=data style="height:500px;width:100%;background:"></textarea>
				</td>
				<td width=50% style=overflow:scroll>
					<div id="result" style="padding:10px;overflow:scroll;width:100%;height:100%"></div>
				</td>
			</tr>
		<table>';

	echo '<script>
	$(
		function(){
			$("#data").change(
				function(){
					$.post(
						"'.basename(__FILE__).'" ,
						{ 
							action: "convert" , 
							data : $("#data").val()},
							function(result){
								$("#result").html(result)
							}
						)
				}
			)
		}
	)
	</script>';
	echo '</body></html>';
}else if($_POST['action'] == 'convert')
{
	//print_r($_POST);
	$data = json2swag($_POST['data']);
	if(!$data)
	{
		echo 'JSON 数据无效';
	}
	echo nl2br(str_replace(" ","&nbsp;",$data));
}
