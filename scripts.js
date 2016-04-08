$(document).ready(function() {

	//
	// Search Bar
	//
	$("#search_bar").click(function() {
		if($("#search_bar").val() == "Indtast produktnavn") {
			$("#search_bar").val("");
		}
	});
	$("#search_bar").focusout(function() {
		if($("#search_bar").val() == "") {
			$("#search_bar").val("Indtast produktnavn");
		}
	});
		
	// 
	// PostIt
	//
	$("#postit_hidden").click(function() {
		$("#postit_hidden").hide(1, function() {
		$("#postit").show();
		});
		$.ajax({
			type: "GET",
			url: "ajax/hide.ajax.php"
			});
	});
	
	$("#paperclip").click(function() {
		$("#postit").hide(1, function() {
		$("#postit_hidden").show();
		});
		$.ajax({
			type: "GET",
			url: "ajax/hide.ajax.php"
			});
	});

	//
	// Webshop Carousel
	//
	var owl = $("#slider");
	owl.owlCarousel({
	items : 5,
	itemsDesktop : [1000,5],
	itemsDesktopSmall : [900,3],
	itemsTablet: [600,2],
	itemsMobile : false
	});
	owl.trigger('owl.play',3000);

	$(".next").click(function(){
	owl.trigger('owl.next');
	})
	$(".prev").click(function(){
	owl.trigger('owl.prev');
	})

	var categories_height = $("#categories").height()+5;
	var user_height = categories_height + "px";
	$("#user").css("margin-top", user_height);

	
	//
	// Cart Handler
	//
	/*
	$('#add_item').change(function() {
		$('#color_load_img').show();
		$('#selectbox_color').attr('disabled','disabled');
		$('#selectbox_size').attr('disabled','disabled');
		$('#selectbox_stock').attr('disabled','disabled');
		int product_id = $('#hidden_id').val();
		int id = $('#hidden_stock_id').val();
		var price = $('#price_amount').val();
		int stock = $('#selectbox_stock').val();
		var stock_total = $('#stock_amount').val();
		var form = $(this.form);
		if(product_id > 0 && id > 0 && stock > 0 && stock_total > 0) {
			$.ajax({
			type: "POST",
			url: "ajax/cart_handler.ajax.php?pid="+product_id+"&id=",
			data: $(this.form).serialize(),
			success: function(data) {
				$('#selectbox_size').empty();
				if(data === "error") {
					$('#selectbox_size').append("<option selected>Vælg størrelse</option>");
					$('#selectbox_stock').append("<option selected>0</option>");
					$('#selectbox_size').attr('disabled','disabled');
				} else {
					$('#selectbox_size').append(data);
				}
				$('#color_load_img').hide();
			}
			});
		}
		$('#selectbox_color').removeAttr('disabled');
		$('#selectbox_size').removeAttr('disabled');
	});
	*/
	
	

	//
	// Webshop Dropdowns
	//
	$('#selectbox_color').change(function() {
		$('#color_load_img').show();
		$('#selectbox_color').attr('disabled','disabled');
		$('#selectbox_size').attr('disabled','disabled');
		$('#selectbox_stock').attr('disabled','disabled');
		var hidden_id = $('#hidden_id').val();
		var color_id = $('#selectbox_color').val();
		var form = $(this.form);
		$.ajax({
		type: "POST",
		url: "ajax/product_list.ajax.php?t=color&id=" + hidden_id + "&cid=" + color_id,
		data: $(this.form).serialize(),
		dataType: 'json',
		success: function(data) {
			$('#selectbox_size').empty();
			if(data.status === "error") {
				$('#selectbox_size').append("<option selected>Vælg størrelse</option>");
				$('#selectbox_stock').append("<option selected>0</option>");
				$('#selectbox_size').attr('disabled','disabled');
				$('#stock_amount').val(null);
				$('#hidden_stock_id').val(null);
			} else {
				$('#selectbox_size').append(data.dropdown);
			}
			$('#color_load_img').hide();
		}
		});
		$('#selectbox_color').removeAttr('disabled');
		$('#selectbox_size').removeAttr('disabled');
	});
	
	$('#selectbox_size').change(function() {
		$('#size_load_img').show();
		$('#selectbox_color').attr('disabled','disabled');
		$('#selectbox_size').attr('disabled','disabled');
		$('#selectbox_stock').attr('disabled','disabled');
		var hidden_id = $('#hidden_id').val();
		var color_id = $('#selectbox_color').val();
		var size_id = $('#selectbox_size').val();
		var form = $(this.form);
		$.ajax({
		type: "POST",
		url: "ajax/product_list.ajax.php?t=size&id=" + hidden_id + "&cid=" + color_id + "&sid=" + size_id,
		data: $(this.form).serialize(),
		dataType: 'json',
		success: function(data) {
			$('#selectbox_stock').empty();
			if(data.status === "error") {
				$('#selectbox_stock').append("<option selected>0</option>");
				$('#selectbox_stock').attr('disabled','disabled');
				$('#stock_amount').val(null);
				$('#hidden_stock_id').val(null);
			} else {
				$('#selectbox_stock').append(data.dropdown);
			}
			$('#size_load_img').hide();
		}
		});
		$('#selectbox_color').removeAttr('disabled');
		$('#selectbox_size').removeAttr('disabled');
		$('#selectbox_stock').removeAttr('disabled');
	});
});