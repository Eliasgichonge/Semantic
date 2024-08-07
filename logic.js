
$(document).ready(function(){
	
    $("select#operation").change(function(){
		
        var selectedOperaion = $(this).children("option:selected").val();
 
		if(selectedOperaion=="none")
		{
			$("#argumentLabel").hide();
			$("#propositionLabel").hide();
			$("#submitBtn1").hide();
			$("#submitBtn2").hide();
			$("#tdcheckbox").hide();
		}
		if(selectedOperaion=="truthtable")
		{
			$("#argumentLabel").hide();
			$("#tdcheckbox").hide();
			$("#propositionLabel").fadeIn(1000);
			$("#tdcheckbox").hide();
			$("#premis").val('');
			$("#conclusion").val('');
		}
		
		if(selectedOperaion=="tautology" )
		{
			$("#argumentLabel").hide();
			$("#tdcheckbox").hide();
			$("#propositionLabel").fadeIn(1000);
			$("#tdcheckbox").fadeIn(1000);
			$("#premis").val('');
			$("#conclusion").val('');
		}
		
		if(selectedOperaion=="validity")
		{
			$("#argumentLabel").fadeIn(1000);
			$("#propositionLabel").hide();
			$("#tdcheckbox").fadeIn(1000);
			$("#props").val('');
		}
    });
}); 



jQuery.fn.extend({
insertAtCaret: function(myValue){
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  })
}
});


$(document).ready(function(){

 var availableTxtArea=["props","premise","conclusion"];
  
 var focusElement = {};
		$("textarea").focus(function(){
		   focusElement = this;
		}); 
		
  $("#neg").click(function(){
  
		var id="#"+$(focusElement).attr("id");
		$(id).insertAtCaret('~');
    
  });
  
  $("#conj").click(function(){
		
		var id="#"+$(focusElement).attr("id");
		$(id).insertAtCaret('∧');
    
  });
  
  $("#disj").click(function(){
  
		var id="#"+$(focusElement).attr("id");
		$(id).insertAtCaret('∨');
    
  });
  
   $("#implies").click(function(){
  
		var id="#"+$(focusElement).attr("id");
		$(id).insertAtCaret('→');
  });
  
    $("#dimplies").click(function(){
		
		var id="#"+$(focusElement).attr("id");
		$(id).insertAtCaret('↔');
  });
 

  var id1="#props";
  
   $(id1).keydown(function (event) {
			
			
			//alert(id);
            var keycode = (event.keyCode ? event.keyCode : event.which);
	
		//alert('You pressed '+keycode)
		    if( event.shiftKey && (keycode==38)){
			event.preventDefault();
			$(id1).insertAtCaret('∧');}
			
		    if( event.shiftKey && ( keycode==40)){
			event.preventDefault();
			$(id1).insertAtCaret('∨');}
            	
			if ( event.shiftKey && ( keycode==39)){
			event.preventDefault();
			$(id1).insertAtCaret('→');}
			
			if ( event.shiftKey && ( keycode ==37)){
			event.preventDefault();
			$(id1).insertAtCaret('↔');}
			
			 if (keycode == 13) {
             event.preventDefault();}
        });
		
	var id2="#premis";
   $(id2).keydown(function (event) {
			
			
			//alert(id);
            var keycode = (event.keyCode ? event.keyCode : event.which);
	
		//alert('You pressed '+keycode)
		    if( event.shiftKey && (keycode==38)){
			event.preventDefault();
			$(id2).insertAtCaret('∧');}
			
		    if( event.shiftKey && ( keycode==40)){
			event.preventDefault();
			$(id2).insertAtCaret('∨');}
            	
			if ( event.shiftKey && ( keycode==39)){
			event.preventDefault();
			$(id2).insertAtCaret('→');}
			
			if ( event.shiftKey && ( keycode ==37)){
			event.preventDefault();
			$(id2).insertAtCaret('↔');}
			
			if (keycode == 13) {
             event.preventDefault();}
        });
		
var id3="#conclusion";
   $(id3).keydown(function (event) {
			
			
			//alert(id);
            var keycode = (event.keyCode ? event.keyCode : event.which);
	
		//alert('You pressed '+keycode)
		    if( event.shiftKey && (keycode==38)){
			event.preventDefault();
			$(id3).insertAtCaret('∧');}
			
		    if( event.shiftKey && ( keycode==40)){
			event.preventDefault();
			$(id3).insertAtCaret('∨');}
            	
			if ( event.shiftKey && ( keycode==39)){
			event.preventDefault();
			$(id3).insertAtCaret('→');}
			
			if ( event.shiftKey && ( keycode ==37)){
			event.preventDefault();
			$(id3).insertAtCaret('↔');}
			
			if (keycode == 13) {
             event.preventDefault();}
        });		
  
    $('input[type="checkbox"]').change(function(event) {
         if($(this).is(':checked'))
		  {
			//var checkedOne=$(this).val()
			//alert(checkedOne);
			$(".output").fadeIn(500);
			$(".tableTitle").fadeIn(500);
			$(".resultLast").fadeOut(500);
			$(".resultLast").fadeIn(500);
		  }
		  else
		  {
			  $(".output").fadeOut(500);
				$(".tableTitle").fadeOut(500);
				$(".resultLast").fadeOut(500);
				$(".resultLast").fadeIn(500);
			  
		  }
    });
$(".centered").html("Copyright &#169; 2023. All right reserved.");
});


$(document).ready(function(){
	
	$("#props").click(function(){
  
    $("#submitBtn1").fadeIn(500);
	$("#logicalSymbols").fadeIn(500);
	$(".lower").hide();
	$(".tdSymbols").css("margin-left","100px");
    
  }); 
  
  $("#premis").click(function(){
  
    $("#submitBtn2").fadeIn(500);
	$("#logicalSymbols").fadeIn(500);
	$(".lower").hide();
	$(".tdSymbols").css("margin-left","100px");
    
  });
  
  $("#conclusion").click(function(){
  
    $("#submitBtn2").fadeIn(500);
	$("#logicalSymbols").fadeIn(500);
	$(".lower").hide();
	$(".tdSymbols").css("margin-left","500px");
    
  });

});
$(document).mouseup(function(e){
    var container = $("#logicalSymbols");
	
    // If the target of the click isn't the container
    if(!container.is(e.target) && container.has(e.target).length === 0){
        container.fadeOut(500);
    }

});

$(document).ready(function () {  
         var focusElement = {};
		$("textarea").focus(function(){
		   focusElement = this;
		});

		$("#btnClick").click(function(e){
			e.preventDefault();
			var focused=$(focusElement).attr("id");
			alert(focused);
			//$("#p1").html($(focusElement).attr("id"));
		});
  
        });  
		
