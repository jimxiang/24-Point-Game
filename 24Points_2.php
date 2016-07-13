<?php
set_time_limit(0);
$values = array(5, 5, 5, 1);
$result = 24;

$list = array();

makeValue($values);
print_r($list);

function makeValue($values, $set=array())
{
    $words = array("+", "-", "*", "/");
    if(sizeof($values)==1)
    {
        $set[] = array_shift($values);
        return makeSpecial($set);
    }

    foreach($values as $key=>$value)
    {
        $tmpValues = $values;
        unset($tmpValues[$key]);
        foreach($words as $word)
        {
            makeValue($tmpValues, array_merge($set, array($value, $word)));
        }
    }
}

function makeSpecial($set)
{
    $size = sizeof($set);

    if($size<=3 || !in_array("/", $set) && !in_array("*", $set))
    {
        return makeResult($set);
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
            makeSpecial($tmpSet);
        }
    }
}

function makeResult($set)
{
    global $result, $list;
    $str = implode("", $set);
    @eval("\$num=$str;");
    if($num==$result && !in_array($str, $list))
        $list[] = $str;
}
