<?php
require_once ("functions.php"); //include the functions file

function generateTruthTable($receivedProp)
{
	$appendedKeys=range('A','Z'); 				// this is array of letters from A to Z. Used in exctracting expressions inside brackets
	
	$detected_p=preg_replace("/[^a-zA-Z0-9]+/", "", $receivedProp); //remove all the symbols and leave only propositions
	
	$detected_p=str_split($detected_p); 		//make the detected propositions as an array 
	
	$detected_p=array_unique($detected_p);		//remove the duplicate letters in detected propositions array;
	
	sort($detected_p);							//sort detected propositions array in ascending order
	
	$pr_no=count($detected_p);					//know how many propositions were detected

	$tv_no=2**$pr_no;							//compute number of truth values  by using the formula N=2^n
	
	define("tv_no",$tv_no); 					//number of truth value is defined as constant so as to be used in other related scripts without re defining as variable

	$props=assignTValues($pr_no,$detected_p); 	//assign truth values to the detected propositions
	
	//condition to checke whether the expression has bracket
	if(!preg_match('/[\(\)]/',$receivedProp))
	{
	//$receivedProp=reconstruct($receivedProp);
	}
	
	//loop to remove unnecessary brackets like (p), ~(p), (~p), (p)∧q, etc
	foreach($detected_p as $det)
	{
		$receivedProp=str_replace("(".$det.")",$det,$receivedProp);
		$receivedProp=str_replace("(~".$det.")","~".$det,$receivedProp);
	}
	
	
	$numOfLooping=substr_count($receivedProp,"(")+1;// determine how many brackets opened,
													//number of brackets opened helps to determine how many times we should loop around to extract expresions inside brackets
	
	
	//begine global for loop
	for($i=0;$i<$numOfLooping;$i++)
	{
	
	//condition to remove extra brackets to appended keys  such as (A), (B), etc, while looping
	if(strlen(preg_replace("/[^a-zA-Z0-9]+/", "", $receivedProp))==1)
	{
		$receivedProp=str_replace(['(',')'],"",$receivedProp);
		
	}
	
	//loop to remove unnecessary brackets like (p), ~(p), (~p), (p)∧q, etc, while looping
	for($j=0;$j<$numOfLooping;$j++)
	{
	$receivedProp=str_replace("(".$appendedKeys[$j].")",$appendedKeys[$j],$receivedProp);
	$receivedProp=str_replace("(~".$appendedKeys[$j].")","~".$appendedKeys[$j],$receivedProp);
	
	//this helps to remove double and tripple brackets  like ((p)), ~((p)), (((~p))), (((p)∧q)) etc.
	$receivedProp=str_replace("(".$appendedKeys[$j].")",$appendedKeys[$j],$receivedProp);
	$receivedProp=str_replace("(~".$appendedKeys[$j].")","~".$appendedKeys[$j],$receivedProp);
	
	} 
	
	###echo "<big><b>".$receivedProp."</b></big><br><br>";

	$NewDetected=preg_replace("/[^a-zA-Z0-9]+/", "", $receivedProp);//know how many propositions are there in the looping
	
	//this condition will chek whether there is only one proposition and asign the self operation
	if(count(str_split($NewDetected))<2)
	{
	$receivedProp=$receivedProp."_self_".$receivedProp;
	}

#*** In some cases the symbols ~,∧,∨,→ and ↔ are not recognized by encoding system of some browsers, so we choose to use words instead ***

	$allSymbols=array('∧','∨','→','↔','~~','~');

	$replace=array('_conj_ ','_disj_ ','_implies_ ','_dimplies_','dneg.','neg.');
	
	$getProp=replaceSymbols($receivedProp,$allSymbols,$replace,""); //replace symbols with words so as to comply with encoding


	
//chek and extract the expressions inside the bracket one after another
	if (preg_match('/(?<=\()[^\(\)]+(?=\))/', $getProp, $match))
	{
		$deducted=deduceOperation($match);  //this gives the two propositions and their operation to be performed
		$operation[$match[0]]=$deducted; 	//assign the propositions and their operations in to operation array
	}

	else //this will be done in case no expression in brackets
	{
	$getProp=array($getProp); //make the the expression as an array
	$deducted=deduceOperation($getProp);
	$operation[$getProp[0]]=$deducted; 
	}
	

//Loop to perform logic operations
	foreach ($operation as $operator => $expr) {
		
	//condition to test for negation operation
		for($k=0;$k<2;$k++)
		{

		    //check for invalid  negation operation like ~∧p, etc
			if($expr[$k]=="neg." or $expr[$k]=="dneg.")
			{
				exit("<span class='error'>Invalid negation operation detected</span>");
			}
			
			//check for extra invalid symbols after negation
			if(strlen($expr[$k])>6)
			{
				exit("<span class='error'>Invalid expression detected after negation symbol</span>");
			}
			
			//test for double negation
			if(substr($expr[$k],0,5)=="dneg.")
			{
			$expr[$k]=trim($expr[$k],"dneg.");  //remove the prefix dneg. form expression
			$keyName="~~".$expr[$k]; 			//create array key with double negation symbol
			$result=$props[$expr[$k]]; 			//assign the truth values to double negation operation
			$props[$keyName]=$result; 			//append the double negation operation in to global array props
			$expr[$k]=$keyName; 				//restore the original expression 
			}
			
			//test for negation
			else if(substr($expr[$k],0,4)=="neg.")
			{
			$expr[$k]=trim($expr[$k],"neg."); 		//remove the prefix neg. form expression
			
			//condition to avoid error in the expressions like ~pq∧r
			if(strlen($expr[$k])!=1)
			{
			exit("<span class='error'>Invalid expression detected after negation symbol</span>");
			}
			
			$keyName="~".$expr[$k]; 				//create array key with negation symbol
			$result=neg($props[$expr[$k]], $tv_no); //perform negation operation
			$props[$keyName]=$result; 				//append the negation operation in to global array props
			$expr[$k]=$keyName; 					//restore the original expression 
			}
		
		} //end of loop for negation
		
		

	   $opr_result=logicalOperation($expr,$operator,$props,$tv_no); //perform logical operations

		
	   $props[$opr_result[0]]=$opr_result[1]; // add the result of operation to global array props 
		
	} //end of loop to perform logic operations
	
		$last=end($props); 						  //get array of the last operation performed
		
		$arrKeyLast=array_key_last($props); 	  //get the array key of the last operation performed

		$receivedProp=str_replace($arrKeyLast,$appendedKeys[$i],$receivedProp); //replace part of the propositions with appended keys;
		
		$props[$appendedKeys[$i]]=$last;		   // add the result of operation with appended keys to global array props 
		
		$keyTitles[$appendedKeys[$i]]=$arrKeyLast; //generate key titles to match with appended keys

	}//enf of for global loop 
	

	$output= $props; //copy the results from above operation to new array variable output
	
	
	//this loop replaces all the appended key above with their original array keys
	for ($k=0;$k<$numOfLooping;$k++)
	{
	 $output=replaceArrayKeys($output,$appendedKeys,$keyTitles);
	}
	
return $output;	//returm the result
	
}



?>