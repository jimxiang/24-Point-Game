<?php
@header('Content-type: application/json');

// $point = array();
$point = array(2, 3, 5, 9);
$expressions = array();
$points = array();

// foreach($_POST as $key => $value)
// {
//  	array_push($point, $value);
// }

// if(count($point) == 4)
// {
// 	foreach($point as $value) {
// 		if(!is_numeric($value) || $value < 1 || $value > 13)
// 		{
// 			$res = array("errCode" => 555, "msg" => "输入不合法");
// 		}
// 	}
// }

// else
// {
// 	$res = array("errCode" => 999, "msg" => "请输入四个合法整数");
// }

for($i = 0; $i < count($point); $i++)
{
	$p_arr = array();
	$p_arr1 = array_merge($p_arr, array($point[$i]));
	for($j = 0; $j < count($point); $j++)
	{
		if($j != $i)
		{
			$p_arr2 = array_merge($p_arr1, array($point[$j]));
			for($k = 0; $k < count($point); $k++)
			{
				if($k != $i && $k != $j)
				{
					$p_arr3 = array_merge($p_arr2, array($point[$k]));
					for($p = 0; $p < count($point); $p++)
					{
						if($p != $i && $p != $j && $p != $k)
						{
							$p_arr4 = array_merge($p_arr3, array($point[$p]));
							array_push($points, $p_arr4);
						}
					}
				}
			}
		}		
	}
}

foreach($points as $key => $value)
{
	build_expression($value);
}

//$res = execute_calculate();
//echo json_encode($res);
//var_dump($expressions);
//execute_calculate();

function build_expression($point)
{
	$set = array();
    $operators = array("+", "-", "*", "/");
    global $expressions;
    foreach($operators as $value)
    {
        $temary1 = array_merge($set, array($point[0], $value, $point[1]));
        foreach ($operators as $value2) {
            $temary2 = array_merge($temary1, array($value2, $point[2]));
            foreach ($operators as $value3) {
                $temary3 = array_merge($temary2, array($value3, $point[3]));
                array_push($expressions, $temary3);
                // 添加括号
                $temp = $temary3;
                array_splice($temp, 0, 0, "(");
                array_splice($temp, 4, 0, ")");
                array_push($expressions, $temp);
                unset($temp);

                $temp = $temary3;
                array_splice($temp, 2, 0, "(");
                array_splice($temp, 6, 0, ")");
                array_push($expressions, $temp);
                unset($temp);

                $temp = $temary3;
                array_splice($temp, 4, 0, "(");
                array_splice($temp, 8, 0, ")");
                array_push($expressions, $temp);
                unset($temp);

                $temp = $temary3;
                array_splice($temp, 0, 0, "(");
                array_splice($temp, 4, 0, ")");
                array_splice($temp, 6, 0, "(");
                array_splice($temp, 10, 0, ")");
                array_push($expressions, $temp);
                unset($temp);
            }
        }
    }
}

function execute_calculate() 
{
    global $expressions;
	for($i = 0; $i < 10; $i++) {
		$result = calculate_point($expressions[$i]);
		$point = $result[0];
		var_dump($point);
		if($point == 24) {
//			return array("errCode" => 200, "msg" => $expressions[$i]);
			var_dump($expressions[$i]);
		}
	}
//	return array("errCode" => 400, "msg" => "无解");
}

$test = array("(", "1", "+", "7", ")", "*", "(", "6", "/", "2", ")");
$result = calculate_point($test);
var_dump($result);

function calculate_point($arr)
{
	// 处理括号
    while(in_array("(", $arr) || in_array(")", $arr))
    {
       for($i = 0; $i < count($arr); $i++)
       {
           if($arr[$i] == "(" && $arr[$i+4] == ")")
           {
               $temp = 0;
               switch($arr[$i+2])
               {
                   case "+":
                       $temp = ($arr[$i+1]) + ($arr[$i+3]);
                       break;
                   case "-":
					   if($arr[$i+1] > $arr[$i+3])
					   {
						   $temp = ($arr[$i+1]) - ($arr[$i+3]);
					   }
                       break;
                   case "*":
                       $temp = ($arr[$i+1]) * ($arr[$i+3]);
                       break;
                   case "/":
					   if($temp[$i+3] != 0)
					   {
						   $temp = ($arr[$i+1]) / ($arr[$i+3]);
					   }
                       break;
               }
               $suffix = $i < (count($arr) - 1) ? array_slice($arr, $i+5) : NULL;
               $prefix = $i > 0 ? array_slice($arr, 0, $i) : NULL;
               if($suffix != NULL && $prefix != NULL)
               {
                   $arr = array_merge(array_merge($prefix, array($temp)), $suffix);
               }
               elseif($prefix == NULL && $suffix != NULL)
               {
                   $arr = array_merge(array($temp), $suffix);
               }
               elseif($suffix == NULL && $prefix != NULL)
               {
                   $arr = array_merge($prefix, array($temp));
               }
           }
       }
    }
	while(count($arr) > 1)
   	{
		if(in_array("*", $arr) || in_array("/", $arr))
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

		else
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

				if($arr[$i] == "-" && $arr[$i-1] && $arr[$i+1] && ($arr[$i+1] < $arr[$i-1]))
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