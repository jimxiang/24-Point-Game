<?php

$values = array();
$result = 24;

$list = array();
$res = array();

foreach($_POST as $key => $value)
{
     array_push($values, $value);
}

if(count($values) == 4)
{
    foreach($values as $value) {
        if(!is_numeric($value) || $value < 1 || $value > 13)
        {
            $res = array("errCode" => 555, "msg" => "输入不合法");
        }
    }
}

else
{
    $res = array("errCode" => 999, "msg" => "请输入四个合法整数");
}

build_expression($values);

if(count($list) > 0)
{
    // $res = array("errCode" => 200, "msg" => $list[0]);
    $res = $list;
}
else
{
    $res = array("errCode" => 400, "msg" => "无解");
}

echo json_encode($res);

function build_expression($values, $set=array())
{
    $words = array("+", "-", "*", "/");
    if(sizeof($values)==1)
    {
        $set[] = array_shift($values);
        return add_brackets($set);
    }

    foreach($values as $key=>$value)
    {
        $tmpValues = $values;
        unset($tmpValues[$key]);
        foreach($words as $word)
        {
            build_expression($tmpValues, array_merge($set, array($value, $word)));
        }
    }
}

function add_brackets($set)
{
    $size = sizeof($set);

    if($size<=3 || !in_array("/", $set) && !in_array("*", $set))
    {
        return calculate($set);
    }

    for($len=3; $len<$size-1; $len+=2)
    {
        for($start=0; $start<$size-1; $start+=2)
        {
            if(!($set[$start-1]=="*" || $set[$start-1]=="/" || $set[$start+$len]=="*" || $set[$start+$len]=="/"))
                continue;
            $subSet = array_slice($set, $start, $len);
            if(!in_array("+", $subSet) && !in_array("-", $subSet))
                continue;
            $tmpSet = $set;
            array_splice($tmpSet, $start, $len-1);
            $tmpSet[$start] = "(".implode("", $subSet).")";
            add_brackets($tmpSet);
        }
    }
}

function calculate($set)
{
    global $result, $list;
    $str = implode("", $set);
    @eval("\$num=$str;");
    if($num==$result && !in_array($str, $list))
    {
        $list[] = $str;
    }
}
