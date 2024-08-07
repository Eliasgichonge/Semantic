<?php

function conj($p,$q,$max)
  {

  $result=array();
	for($i=0;$i<$max;$i++)
    	{
    	if($p[$i]=='T'&&$q[$i]=='T')
        	{
            array_push($result,'T');
            }
        else{
            array_push($result,'F');
            }
        }
     return $result;
  }
  
  
function disj($p,$q,$max)
  {
  $result=array();
	for($i=0;$i<$max;$i++)
    	{
    	if($p[$i]=='F'&&$q[$i]=='F')
        	{
            array_push($result,'F');
            }
        else{
            array_push($result,'T');
            }
        }
  
    return $result;
  }


function implies($p,$q,$max)
  {
  $result=array();
	for($i=0;$i<$max;$i++)
    	{
    	if($p[$i]=='T'&&$q[$i]=='F')
        	{
            array_push($result,'F');
            }
        else{
            array_push($result,'T');
            }
        }
  
    return $result;
  }


function dimplies($p,$q,$max)
  {
  $result=array();
	for($i=0;$i<$max;$i++)
    	{
    	if($p[$i]==$q[$i])
        	{
            array_push($result,'T');
            }
        else{
            array_push($result,'F');
            }
        }
  
    return $result;
  }


function neg($p,$max)
  {
  $result=array();
  #print_r($p);
	for($i=0;$i<$max;$i++)
    	{
    	if($p[$i]=='F')
        	{
            array_push($result,'T');
            }
        else{
            array_push($result,'F');
            }
        }
  
    return $result;
  }
  
function assignTValues($noProp,$detected)
  {
	
	if($noProp==0)
		  {
			  exit("<span class='error'>Error! No propositions provided</span>");
		  } 
		  
	if($noProp>10)
	{

	echo"<script>alert('Warning..! More than ten (10) propositions detected!')</script><span class='warning'>*** Warning..! More than ten (10) propositions detected. This script will be little slower.***</span> <br><br>";

	}

 $reduced_tv_no=tv_no;
 $arrProp=array();
 for($i=0;$i<$noProp;$i++)
	{
    $reduced_tv_no=$reduced_tv_no/2;
    
    $strToAppend="";

    $appendedTValues=str_repeat("T",$reduced_tv_no).str_repeat("F",$reduced_tv_no);
   
    for($j=0;$j<(tv_no/($reduced_tv_no*2));$j++)
    {
       
     $strToAppend=$strToAppend.$appendedTValues;
    }
    $arrProp[$detected[$i]]=str_split($strToAppend);
   
    }
	  
	  return $arrProp;
  }
  
function logicalOperation($expr,$operator,$props,$tv_no)
  {

	$pregOperator = preg_replace('/(.*)_(.*)_(.*)/sm', '\2', $operator);

	if($expr[0]=="" or $expr[1]=="" or $operator=="")
	{
		exit("<span class='error'>Incomplete logical expression</span>");
	}

	switch($pregOperator)
	{
	case "conj":
		$keyName="(".$expr[0]."∧".$expr[1].")";
		$result=conj($props[$expr[0]], $props[$expr[1]], $tv_no);//logical operation
		 break;

	case "disj":
		$keyName="(".$expr[0]."∨".$expr[1].")";
		$result=disj($props[$expr[0]], $props[$expr[1]], $tv_no);//logical operation
		 break;

	case "implies":
		$keyName="(".$expr[0]."→".$expr[1].")";
		$result=implies($props[$expr[0]], $props[$expr[1]], $tv_no);//logical operation
		 break;
		
	case "dimplies":
		$keyName="(".$expr[0]."↔".$expr[1].")";
		$result=dimplies($props[$expr[0]], $props[$expr[1]], $tv_no);//logical operation
		 break;
		 
	case "self":
		$keyName=$expr[0];
		$result=$props[$expr[0]];//logical operation
		 break;

		
	default: exit("<span class='error'>No or Invalid operation selected</span>");
	}
	$result_arr=[$keyName,$result];

  return $result_arr;
		
  }

function deduceOperation($extracted)
{

	$replaced=array('_conj_ ','_disj_ ','_implies_ ','_dimplies_','_self_');
	
	$onlyProp=str_replace($replaced,',',$extracted[0]);

	$onlyProp=explode(",",$onlyProp);
	
	
	#condition to chek whether the given expression contains other word or space before the proposition like kp or(p..
	#.. apart from .neg
	
	foreach($onlyProp as $strToCheck)
	{
		if(strlen($strToCheck)!=1)
		{
			if(!str_contains($strToCheck,"neg."))
			{
			exit("<span class='error'>Invalid proposition detected</span>");
			}
		
		}
	}

	
	#-----------------------------------
	#This condition check whether we operate only two propositions.
	#Enable to avoid error in case of single proposition with no logical connectives like (p); 
	$arrValueCnt=count($onlyProp);
	if($arrValueCnt<2)
	{
		exit("<span class='error'>Oooooop! One propositon is invalid</span>"); 
	}
	#-----------------------------------------

	return $onlyProp;
	
}

function replaceArrayKeys($array,$oldKey,$newKey )
  {
    
	$replacedKeys = str_replace($oldKey, $newKey , array_keys($array));
	
    return array_combine($replacedKeys, $array);
 }


function hasMatchedParenthesis($string) 
  {
	$depth = 0;
	$string=str_split($string);

	foreach($string as $character)
		{
		$depth += $character == '(';
		$depth -= $character == ')';
	   
		if ($depth < 0)
		   break;
		
		}
		
		if($depth != 0)
		  {
			return false;
		  }
		  else
		  {
			 return true; 
		  }
	  
  }  


function checkTautology($result)
  {
	$lastColumn=end($result); 

	//check whether array contains olny T values
	if(count(array_unique($lastColumn))==1&&$lastColumn[0]=='T')
	{
		return true;
		
	}
	else
	{
		return false;	
	}
	
  }


function testValidity($isTautology)
	{

		if($isTautology)
		{
			return true;	
		}
		else
		{
			return false;	
		}
			
		
	}

function combineArgData($string)
  {	
	for($k=0;$k<count($string);$k++)
	{
		$detected_p=preg_replace("/[^a-zA-Z0-9]+/", "", $string[$k]);//remove all symbols to remain olnywith propositions
		
		if(strlen($detected_p)!=1)// avoid error in case of (p)
		{
		$string[$k]="(".$string[$k].")"; //add brackets to premises
		}
	}
	
	if(count($string)==1)
		{
			$new=$string[0];
		}
		else
		{
			$new='('.$string[0].'∧'.$string[1].")∧";
			
			for($i=2;$i<count($string);$i++)
			{
			$new="(".$new.$string[$i].")∧";
			}
			
			$new=rtrim($new,"∧");
		}
		
	return $new;
	
  }


function reconstruct($string)
 {
	$strOg=$string;

	$strArry = preg_split('/[&*-=]/', $string);

	
	$append=range('M','Z');

	for($i=0;$i<count($strArry);$i++)
	{
		if(strlen($strArry[$i])>2 or endsWith($strArry[$i],"~"))
		{
		exit("<span class='error'>Invalid expression detected</span>");
		}
		
		if(str_contains($strArry[$i],'~'))
		{
		 $keyResult[$append[$i]]=$strArry[$i];
		 $strOg=str_replace($strArry[$i],$append[$i],$strOg);
		}
		else
		{
		 $keyResult[$append[$i]]="";
		}

	}

	$new=$string=$strOg;
	preg_match_all('/[&*-=]/' ,$string, $matches);

	if(count($matches[0])>1)
	 {
		$detected_p=preg_replace("/[^a-zA-Z0-9]+/", "",$string);//remove all symbols
		if(strlen($detected_p)>2)
		{
		$new="(".substr($string,0,3).")";
			for($i=3;$i<strlen($string);$i++)
			{
				if(($i+1)==strlen($string))
				{
				$new="(".$new.$string[$i].")";	
				}
				else
				{
				$new="(".$new.$string[$i].$string[$i+1].")";	
				}
			  
			  $i+=1;
			  
			}
					
		}
	 }
		
	foreach(array_keys($keyResult) as $key)
	{
		$new=str_replace($key,$keyResult[$key],$new);	
	}
	
  return $new;
 }
 
 
 function replaceSymbols($receivedProp,$allSymbols,$replace,$mode)
  {
	if($mode=="reverse")
	{
		$result=str_replace($replace,$allSymbols,$receivedProp); 
	}
	else
	{
		$result=str_replace($allSymbols,$replace,$receivedProp);
	}

	return $result; 
  }
  
  function startsWith( $haystack, $needle ) {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}

 function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
if (! function_exists("array_key_last")) {
    function array_key_last($array) {
        if (!is_array($array) || empty($array)) {
            return NULL;
        }
       
        return array_keys($array)[count($array)-1];
    }
}

?>
