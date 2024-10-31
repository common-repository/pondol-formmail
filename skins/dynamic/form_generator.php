<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
jQuery(document).ready(function(){
	//field to form
	var prev_index;
	var current_index;
	var itemid = "<?php echo $data_info["id"];?>";

	
	var obj	= new Array();
	
	
	jQuery(".drag_ele").draggable({
		cursor:'move',
		connectWith: '.dynamicfield',
		helper:'clone',
		stop:function(e, ui) {
				//current_index = $("#drag-form-item .drag_ele").index(ui.item);//get current index;
				//console.log("draggable stop index:"+current_index);
				//reorder_data();
			}
	});
	
	var set_draggable = function(){

		$( ".dynamicfield" ).sortable({
			connectWith: '.dynamicfield',
			cursor:'move',
            start: function(e, ui) {
				prev_index = $("#drag-form-item .drag_ele").index(ui.item);//get current index;
				//console.log("sortable start:"+prev_index);
			},

			stop: function(e, ui) {
				current_index = $("#drag-form-item .drag_ele").index(ui.item);//get current index;
				//console.log("sortable stop index:"+current_index);
				reorder_data();
			},
            placeholder:'ui-state-highlight'

		}).droppable({
	    	cursor:'move',
	        drop: function(e, ui) {
				//$(e.target).append((ui.draggable).clone());
				if($(ui.draggable).hasClass("ui-sortable-helper")) $(e.target).append(ui.draggable);
				else{
					//console.log(ui.draggable);
				 	$(e.target).append(ui.draggable.clone());
				 	//$(e.target).append(ui.draggable);
					//console.log("droppable stop");
					prev_index = -1;
					current_index = $("#drag-form-item .drag_ele").index($(e.target).find(".drag_ele"));//get current index;
					//console.log("droppable drop index:"+current_index);
					reorder_data();
				}
	        }
	      
	  }).disableSelection();

	    jQuery("#trash_bin").droppable({
	        drop: function(e, ui) {
	        	//current_index = $("#drag-form-item .drag_ele").index(ui.draggable);
	        	//obj.splice(prev_index, 1);
				//console.log("trash_bin_current_index:" + current_index);
				//obj.splice(prev_index, 1);
				//console.log("trash_bin prev_index:"+prev_index+", trash_bin  current_index:"+current_index);
				//obj.splice(prev_index, 1);
				ui.draggable.remove();

				$("#btn_create_form").click();
	        }, stop: function(event, ui) { 
	        	//console.log("prev_index:"+prev_index+", current_index:"+current_index);

	        }
	    });
	    
	    
   }

	$("#btn_create_row").click(function(){
		
		var cnt = Number($("#row_count").val());
		var len = $("#drag-form-item tr").length;
		var i;
		if(cnt > 0){
			//console.log("cnt:"+cnt+", len:"+len);
			if(len > cnt){
				var limit	= len-cnt
				for(i = 0; i < limit; i++){
					$("#drag-form-item tr").last().remove();
				}	
			}else{
				var limit	= cnt-len
				//console.log("cnt:"+cnt+", len:"+len+", limit:"+limit);
				for(i = 0; i < limit; i++){
					$("#drag-form-item table").append('<tr><td class="dynamicfield"></td><td class="dynamicfield"></td></tr>');
				}	
			}
			
			set_draggable();//call 
		}
		$("#btn_create_form").click();
	});
	

	//input attribute
	$(document).on("dblclick", ".drag_ele", function(){
		//console.log("attibute available");
		var attr = $(this).attr("user-attr");
		current_index	= $("#drag-form-item .drag_ele").index(this);
		//console.log("obj:"+obj);
		//console.log("current_index:"+current_index);
		//retrive current attribute
		var attr_data	= obj[current_index];
		
		//console.log("attr_data:"+attr_data);
		var pairs = getVal(attr_data);
		
		$(".element_attr_box").hide();//close all attribute box
		$(".attr_"+attr).show();
		switch(attr){
			case "label":
				//console.log("pairs.input_label:"+pairs.input_label);
				$(".attr_label input[name$='input_label']").val(pairs.input_label);
				//$(".attr_label").show();
			break;
			case "text":
				$(".attr_text input[name$='validate_email']").prop("checked", (pairs.validate_email == "true" ? "checked":false));
				$(".attr_text input[name$='validate_text']").prop("checked", (pairs.validate_text == "true" ? "checked":false));
			case "password":
				//$(".attr_password").show();
			case "file":
				//$(".attr_file").show();
			case "check":
				//$(".attr_check").show();
			case "radio":
				//$(".attr_radio").show();
			case "select":
				//$(".attr_select").show();
			case "textarea":
				//$(".attr_textarea").show();
			break;
		}
		$("#btn_create_form").click();
	})
	
	$(".btn_save_attr").click(function(){
		var data = $(this).parents("form").serialize();
		obj[current_index]	= data;
		$(".element_attr_box").hide();
		$("#btn_create_form").click();
	});
	
	
	var reorder_data = function(){
		var i;
		if(prev_index == -1 || typeof(prev_index) == "undefined" ){//dragged from draggable
			
			//console.log("reorder_data current_index:"+current_index);
			var cur_element = $("#drag-form-item .drag_ele").eq(current_index).attr("user-attr");
			var newObj = $(".attr_"+cur_element+"_form").serialize();
			var objContainer	= new Array();
			
			if(obj.length == 0){//no contents
				//newObj = {}
				objContainer.push(newObj);
				//console.log("process 1");
			} else{
				for(i = 0; i <= obj.length; i++){
						//newObj = {}
						if(i == current_index) objContainer.push(newObj)
						if(obj[i]) objContainer.push(obj[i]);//이럴경우 기존 데이타를 불러올때 "" 에러가 발생
						//if(!obj[i]) obj[i] = {};
						//objContainer.push(obj[i]);
					}
				//console.log("process 2");
			} 
		}else{//draggaed from sortable		
			var source_obj = obj.splice(prev_index, 1);
			var objContainer = new Array();
			if(obj.length == 0){
				//if(source_obj[0] == "") source_obj[0] = {};
				objContainer.push(source_obj[0]);
			}else{
				for(var i = 0; i <= obj.length; i++){
					if(i == current_index) objContainer.push(source_obj[0]);
					if(obj[i]) objContainer.push(obj[i]);
				}
			}
			
		}
		
		obj	= objContainer;
		$("#btn_create_form").click();	
	}


	
	var getVal = function(queryString){
		var keyValues = {};
		if(typeof(queryString) != "object"){
			var elements = queryString.split("&");
			
			for(var i in elements) { 
				var key = elements[i].split("=");
				if (key.length > 1) {
					keyValues[decodeURIComponent(key[0].replace(/\+/g, " "))] = decodeURIComponent(key[1].replace(/\+/g, " "));
				}
			}
		}
		
		return keyValues;
	}
	
	$("#btn_create_form").click(function(){
		//console.log("form create start...");
		var ele_position = new Array();
		var ele_type	 = new Array();
		$("#drag-form-item td").each(function(index){
			var ele_len = $(this).find(".drag_ele").length;
			ele_position.push(ele_len);
			if(ele_len != 0) $(this).find(".drag_ele").each(function(i){
				ele_type.push($(this).attr("user-attr"));
			});
		});

		$.post(ajaxurl, {action:"pondol_dynamic_form_create_preveiw", id:itemid, obj:obj, row_cnt:Number($("#row_count").val()), ele_type:ele_type, ele_position:ele_position}, function(data){
			//console.log(data);
			$("#preview").html(data);
		});

	});

	$(".btn_save_current_form").click(function(){
		//console.log("form save start...");
		var ele_position = new Array();
		var ele_type	 = new Array();
		$("#drag-form-item td").each(function(index){
			var ele_len = $(this).find(".drag_ele").length;
			ele_position.push(ele_len);
			if(ele_len != 0) $(this).find(".drag_ele").each(function(i){
				ele_type.push($(this).attr("user-attr"));
			});

		});
		//console.log(".btn_save_current_form.click(function(){ obj:"+obj);
		$.post(ajaxurl, {action:"pondol_dynamic_form_save", id:itemid, obj:obj, row_cnt:Number($("#row_count").val()), ele_type:ele_type, ele_position:ele_position}, function(data){
			//console.log("save to database");
			//console.log(data);
			
		});
	});
	
	
	var init = function(){
		obj = <?php echo $call_obj; ?>;
		$("#btn_create_row").click();
		//$("#btn_create_form").click();//기존 자료를 불러올경우
	}
	init();
	

});

</script>

<style>
	#elementList{background-color:gray;}
	.dynamicfield {width:50px; height:50px; background-color:yellow;}
	#trash_bin {width:50px; height:50px; background-color:black;}
</style>
</br></br></br></br>

	<H2><?php _e('Step 1. Create Form Rows', 'pondol_formmail');?></H2>
	row count : <input type="text" value="<?php echo $form_field["row_cnt"];?>" id="row_count"> <button type="button" id="btn_create_row">create row</button>
	</br></br>
	
	<H2><?php _e('Step 2. Set html tag elements', 'pondol_formmail');?></H2>
 	<ul id="elementList">
	    <li><span class="drag_ele" user-attr="label">label</span></li>
	    <li><span class="drag_ele" user-attr="text">text</span></li>
	    <li><span class="drag_ele" user-attr="password">password</span></li>
	    <li><span class="drag_ele" user-attr="file">file</span></li>
	    <li><span class="drag_ele" user-attr="check">check</span></li>
	    <li><span class="drag_ele" user-attr="radio">radio</span></li>
	    <li><span class="drag_ele" user-attr="select">select</span></li>
	    <li><span class="drag_ele" user-attr="textarea">textarea</span></li>
	    <li><span class="drag_ele" user-attr="button">button</span></li>
	</ul>
	
	<div id="drag-form-item">	
		<table>
			<!-- <tr><td class="dynamicfield"></td><td class="dynamicfield"></td></tr> -->
			<?php 
			//print_r($info);
			foreach($form_field["ele_position"] as $key=>$val){
				echo '<td class="dynamicfield">';
				foreach($form_field["form_ele"][$key] as $k => $v){
					echo '<span class="drag_ele ui-sortable-helper" user-attr="'.$v.'">'.$v.'</span>';
				}
				echo '</td>';
			if($key%2) echo '</tr><tr>';
			}
			?>
		</table>
	</div>
<span>To remove an element from current form, drag an element to trash bin</span>
	<div id="trash_bin">trash bin</div>

<button type="button" id="btn_create_form" style="display: none;">create</button>


<form class="attr_label_form">
 	<div class="attr_label element_attr_box" style="display:none">
 		label text : <input type="text" name="input_label">
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form>
<form class="attr_button_form">
 	<div class="attr_button element_attr_box" style="display:none">
 		
 		label text : <input type="text" name="input_button_text">
 		<select name="input_button_type">
 			<option value="button">button</option>
 			<option value="submit">submit</option>
 		</select>
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form>
<form class="attr_text_form">
 	<div class="attr_text element_attr_box" style="display:none">
 		id(name) : 
 		<input type="text" name="element_id" value="">
 		validiate : 
 		<select name="sel_validation">
 			<option value="none">None</option>
 			<option value="text">Text</option>
 			<option value="email">Email</option>
 		</select>
 		validiate message : 
 		<input type="text" name="validate_text_message">
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form>
<form class="attr_checkbox_form">
 	<div class="attr_checkbox element_attr_box" style="display:none">
 		id(name) : 
 		<input type="text" name="element_id" value="">
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form> 
<form class="attr_radiobox_form">
 	<div class="attr_radiobox element_attr_box" style="display:none">
 		id(name) : 
 		<input type="text" name="element_id" value="">
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form> 
<form class="attr_select_form">
 	<div class="attr_select element_attr_box" style="display:none">
 		id(name) : 
 		<input type="text" name="element_id" value="">
 		option : <input type="text" name="input_label"> -  seperated by ","
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form> 
<form class="attr_textarea_form">	
 	<div class="attr_textarea element_attr_box" style="display:none">
 		id(name) : 
 		<input type="text" name="element_id" value="">
 		<button type="button" class="btn_save_attr">저장</button>
 	</div>
</form>	
 	
 	
 	<div id="preview"></div>
 	</br></br>
	
	<H2><?php _e('Step 3. Save current form', 'pondol_formmail');?></H2>
	
	<button type="button" class="btn_save_current_form">Save Current Form</button>
