<!DOCTYPE html>
<html>

<head>
    <title>Logical Operations</title>
    <script src="jquery.min.js"></script>
    <script src="logic.js"></script>
    <link rel="stylesheet" href="logic.css" type="">

</head>

<body>
    <div class="upper">
        <form action="" , method="POST">
            <table class="form">
                <td id="operation"><label for="operation">Select Operation:</label></td>
                <td id="operation">

                    <select id="operation" name="operation">
                        <option value="none" id="truthTable">----------select----------</option>
                        <option value="truthtable" id="truthTable">Generate Truth Table</option>
                        <option value="tautology">Check for Tautology</option>
                        <option value="validity">Test for Validity</option>
                    </select>

                </td>
                <td id="tdcheckbox" class="proposition">
                    <input type="checkbox" id="includeTruthtable" name="includeTruthtable" value="includeTruthtable"
                        checked>
                    <label for="includeTruthtable" id="includeTruthtable"> Include truth table</label>
                </td>
            </table>
            <br><br>
            <table id="propositionLabel" class="form">
                <td class="proposition"><label for="lname">Enter your proposition:</label></td>
                <td class="proposition"><textarea type="text" name="proposition" id="props" class="proposition"
                        title="Enter your Proposition"></textarea></td>
                <td class="argument" id="submitBtn1"><input type="submit" name="submit" , value="Submit"
                        title="Click to submit"></td>
            </table>

            <table id="argumentLabel" class="form">
                <td class="argument"><label for="lname">Enter your argument:<br><small>(Separate by
                            comma)</small></label></td>
                <td class="argument">
                    <table>
                        <td><textarea type="text" name="premis" id="premis" class="argument"
                                title="Enter your Premises (P1, P2, ... Pn)"></textarea></td>
                        <td id="arrow">&#8866;</td>
                        <td><textarea type="text" id="conclusion" name="conclusion" class="conclusion"
                                title="Enter your Conclusion (Q)"></textarea></td>
                    </table>
                </td>
                <td class="argument" id="submitBtn2"><input type="submit" name="submit" , value="Submit"
                        title="Click to submit"></td>
            </table>

            <table id="logicalSymbols" class="form" style="position:absolute">
                <td></td>
                <td class="proposition" align="center">
                    <div class="tdSymbols">

                        <div id="neg" class="symbols" name="#props">~</div>
                        <div id="conj" class="symbols" name="#props" title="Shift+Up Arrow(&#8593)">&#8743;</div>
                        <div id="disj" class="symbols" name="#props" title="Shift+Down Arrow(&#8595)">&#8744;</div>
                        <div id="implies" class="symbols" name="#props" title="Shift+Right Arrow(&#8594)">&#8594;</div>
                        <div id="dimplies" class="symbols" name="#props" title="Shift+Left Arrow(&#8592)">&#8596;</div>

                    </div>
                </td>
            </table>
            <br><br>
            <!--<table id="submitBtn" class="form" style="position:relative">
 <td></td><td class="argument"><input type="submit" name="submit", value="Submit" title="Clock to submit"></td>
 </table> -->

        </form>
    </div>
    <div class="lower">
        <center>

            <?php

require_once("main.php"); //include the main fail
if(isset($_POST['submit'])) //check whether the submit button has clicked 
{
	// condition to check whether the input box for proposition is not empty
	if(!empty($_POST['proposition']))
	{
		$receivedPropOg=$_POST['proposition']; //extract submitted proposition
	}

	// condition to check whether the input box for premises and conclusion is not empty and combine an argument
	else if(!empty($_POST['premis'])&&!empty($_POST['conclusion']))
	{
		$premis=$_POST['premis']; 				//extract submitted premises
		
		$conclusion=$_POST['conclusion'];		//extract submitted conclusion 

		$premis=rtrim($premis,","); 			//remove in case user input last character is ,
		
		$conclusion=rtrim($conclusion,","); 	//remove in case user input last character is ,
		
		$argument=$premis."  <big>&#8866; </big> ".$conclusion; //cerate an argument

		$premis=explode(',', $premis); 			//extract premises separate them by comma
		
		$conclusion=explode(',', $conclusion);  //extract conclusion separate them by comma
		
		$strPremis=combineArgData($premis); 	//combine premises with conjunction operator
		
		$strConclusion=combineArgData($conclusion); //combine conclusion with conjunction operator
		
		$receivedPropOg=$strPremis."→".$strConclusion; //converting an argument to proposition
			
		
	}
	else
	{
	exit("<span class='error'>Error!! Missing proposition/argument</span>");//give error message and exit the execution	
	}
#--------------------------------------------

	$receivedPropOg=strtolower($receivedPropOg); //convert propositions label to small letters

	$receivedPropOg=str_replace(' ','',$receivedPropOg); //remove spaces within expression

//check whether there are numeric characters
	if (preg_match('~[0-9]+~', $receivedPropOg)) 
	{
		exit("<span class='error'>Error! Numeric characters are not allowed</span>");
	}

//check whether there are unmatched brackets/parenthesis
	if(hasMatchedParenthesis($receivedPropOg)==false)
	{
		exit("<span class='error'>Unmached paranthesis in your expression! </span>");
	}
	
//define styles for the last column
	$styleTautology="<style>td#last{color:forestgreen; background:; font-weight:bold}</style>";
	$styleFallacy="<style>td#last{color:#e10303; background:; font-weight:bold}</style>";

//check whether user provided special characters different from ~,(,),∧,∨,→,↔
	if(preg_match('/[^A-Za-z0-9\~\(\)\∧\∨\→\↔ ]/',$receivedPropOg)==1) 
	{
		exit("<span class='error'>Error! Invalid symbols detected</span>");
	}



	/*----------------------------------------------------------------------------
	The block of codes below used to reconstruct the proposition in the case that 
	user provides more than two operations without specifying order of operation 
	by using brackets e.g
		 p∧q∧r	   =>  will be reconstructed as (p∧q)∧r)
		 p→(q∧r∨s) =>  will be reconstructed as p→((q∧r)∨s)
	------------------------------------------------------------------------------*/	
	$letters=range('A','Z');
	$symbols=array('∧','∨','→','↔'); //these symbols are sometimes not supported by encoding in str functions, therefore we replace them temporary

	$replacer=array('&','*','-','=');
	$rs=replaceSymbols($receivedPropOg,$symbols,$replacer,'');

	$cnt=0;
	for ($i=0;$i<(substr_count($rs,"(")+15);$i++)
	{
		
		if (preg_match('/(?<=\()[^\(\)]+(?=\))/', $rs, $match))
		{

		$cnt=1;
		$resultArray[$letters[$i]]=reconstruct($match[0]);

		$rs=str_replace('('.$match[0].')',$letters[$i],$rs);
		
		}
		else
		{
		$rs=reconstruct($rs);
		goto here;
		}
		
	}
	
	here:
	
	if($cnt!=0)
	{
		$arrayKey=array_keys($resultArray);
		
		$reversedArrayKey=array_reverse($arrayKey);
	
		foreach($reversedArrayKey as $key)
		{
			$rs=str_replace($key,"(".$resultArray[$key].")",$rs);
		}
	}
	$rs=replaceSymbols($rs,$symbols,$replacer,'reverse'); //we return to our original symbols

/*----------------------------END OF RECONSTRUCTION-------------------------*/
	
	
	$result=generateTruthTable($rs); //function to generate truth table
	

/*---------------------- PRINTING THE RESULTS---------------------------------*/

	//outputting headers
echo "<div id='output'  class='tableTitle'>Truth Table for: &nbsp; $receivedPropOg</div>";

echo "<table id='output' class='output'>";

$lstHeader = count($result); //how many logical operations performed

for ($i = 0; $i < count($result); $i++) {

    $arrKey = array_keys($result)[$i]; //get the array keys for all the operations performed

    // Check if the first and last characters are parentheses and remove them if true
    if ($arrKey[0] == '(' && $arrKey[strlen($arrKey) - 1] == ')') {
        $arrKey = substr($arrKey, 1, -1); // Remove the first and last character
    }

    //this condition will check for the td of the last column of the table
    if ($lstHeader == ($i + 1)) {
        echo "<td class='header' id='last' style='background-color:honeydew'><b>" . $arrKey . "</b></td>";
    } else {
        echo "<td id='regular' class='header'><b>" . $arrKey . "</b></td>";
    }
}

//outputting data
echo "<tr>";

for ($i = 0; $i < tv_no; $i++) {
    $cnt = 0;
    foreach ($result as $key) {
        $cnt = $cnt + 1;

        //this condition will check for the td of the last column of the table
        if ($cnt == $lstHeader) {
            echo "<td id='last'>" . ($key[$i]) . "</td>";
        } else {
            echo "<td id='regular'>" . ($key[$i]) . "</td>";
        }
    }
    echo "<tr>";
}

echo "</table><br>";

echo "<div class='resultLast'>";

//this condition will check for tautology
if ($_POST['operation'] == "tautology") {
    $isTautology = checkTautology($result);

    if ($isTautology) {
        echo "The proposition : &nbsp; $receivedPropOg &nbsp; is &nbsp; <span class='green'>TAUTOLOGY</span>";

        echo $styleTautology; //this will apply different formatting style to the last column
    } else {
        echo "The proposition : &nbsp; $receivedPropOg &nbsp; is  &nbsp; <span class='red'>NOT TAUTOLOGY</span>";

        echo $styleFallacy; //this will apply different formatting style to the last column
    }
}

//this condition will check for validity
if ($_POST['operation'] == "validity") {
    $isTautology = checkTautology($result);

    $isValid = testValidity($isTautology);

    if ($isValid) {
        echo "The argument: &nbsp; [$argument] &nbsp; is &nbsp; <span class='green'>VALID </span>";

        echo $styleTautology; //this will apply different formatting style to the last column
    } else {
        echo "The argument: &nbsp; [$argument] &nbsp; is &nbsp;  <span class='red'>NOT VALID </span>";

        echo $styleFallacy; //this will apply different formatting style to the last column
    }
}
echo "</div>";

//this condition will hide the truth table if the checkbox is not checked
if (!isset($_POST['includeTruthtable'])) {
    echo "<style>#output{display:none}</style>";
}

################################## END OF PRINTING THE RESULT#############################
}

?>
        </center>
    </div>
    <div class='centered'></div>
</body>

</html>