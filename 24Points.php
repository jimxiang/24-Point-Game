<?php
@header('Content-type: application/json');
$res = array();
$point = array();
$expressions = array();

foreach($_POST as $key => $value)
{
   global $point;
   if(isset($key))
   {
       array_push($point, $value);
   }
}

build_expression($point);

echo execute_calculate($expressions);

function build_expression($point)
{
	$set = array();
    $operators = array("+", "-", "*", "/");
    global $expressions;
    foreach($operators as $key => $value) {
        $temary1 = array_merge($set, array($point[0], $value, $point[1]));
        foreach ($operators as $key => $value) {
            $temary2 = array_merge($temary1, array($value, $point[2]));
            foreach ($operators as $key => $value) {
                $temary3 = array_merge($temary2, array($value, $point[3]));
                // 添加括号

                array_push($expressions, $temary3);
            }
        }
    }
}

function execute_calculate($expressions) 
{
    global $res;
	for($i = 0; $i < count($expressions); $i++) {
		$result = calculate_point($expressions[$i]);
		$point = $result[0];
		if($point == 24) {
			$res = array("errCode" => 200, "msg" => $expressions[$i]);
            return json_encode($res);
		}
	}
	$res = array("errCode" => 400, "msg" => "无解");
    return json_encode($res);
}


function calculate_point($arr)
{
	while(count($arr) > 1)
	{
		if(is_multiple_or_divide($arr) === true)
		{
            // 优先处理乘法和除法
			for($i = 0; $i < count($arr); $i++)
			{
				if($arr[$i] == "*") 
				{
					$temp = ($arr[$i-1]) * ($arr[$i+1]);
					$suffix = array_slice($arr, $i+2);
					$prefix = array_slice($arr, 0, $i-1);
					$arr = array_merge(array_merge($prefix, array($temp)), $suffix);

				}

				if($arr[$i] == "/" && ($arr[$i+1]) != 0) 
				{
					$temp = ($arr[$i-1]) / ($arr[$i+1]);
					$suffix = array_slice($arr, $i+2);
					$prefix = array_slice($arr, 0, $i-1);
					$arr = array_merge(array_merge($prefix, array($temp)), $suffix);
				}
			}
		}

		elseif(is_multiple_or_divide($arr) === false)
		{
			// 这里只剩加法和减法
			for($i = 0; $i < count($arr); $i++)
			{
				if($arr[$i] == "+") 
				{
					$temp = ($arr[$i-1]) + ($arr[$i+1]);
					$suffix = array_slice($arr, $i+2);
					$prefix = array_slice($arr, 0, $i-1);
					$arr = array_merge(array_merge($prefix, array($temp)), $suffix);
					calculate_point($arr);
				}

				if($arr[$i] == "-") 
				{
					$temp = ($arr[$i-1]) - ($arr[$i+1]);
					$suffix = array_slice($arr, $i+2);
					$prefix = array_slice($arr, 0, $i-1);
					$arr = array_merge(array_merge($prefix, array($temp)), $suffix);
				}
			}
		}
	}
	return $arr;
}

function is_multiple_or_divide($arr) {
	foreach($arr as $value)
	{
		if($value == "*" || $value == "/")
		{
			return true;
		}
	}
	return false;
}