$("#uploadimages").submit(function(event) {
    event.preventDefault();
    $("#message").empty();
    $("#loading").show();
    $.ajax({
        url: "/admin/files/images.php/?action=upload", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,       // The content type used when sending data to the server.
        cache: false,             // To unable request pages to be cached
        processData:false,        // To send DOMDocument or non processed data file it is set to false
        success: function(data)   // A function to be called if request succeeds
        {
        $("#update_form").html(data);
        $("#galleries").trigger("change");
        }
    });
});

$("#galleries").trigger("change");

$("#create_gallery").on("click",function(){
    $.ajax({
        url: "/admin/files/images.php/?action=new_gallery", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { gallery_title: $('input[name="gallery_title"]').val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#update_form").html(data);
            $("#galleries").trigger("change");
        }
    });
})

$("#menus").on("change",function(){
   if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php?action=menu", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { menu_type: $(this).val(), view: $(this).attr("name"),  state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#menu_categories").html(data);
        }
    });

    $.ajax({
        url: "/admin/files/ajax.php?action=menu&update=list", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { menu_type: $(this).val(), view: $(this).attr("name"),  state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$("#menu_categories").on("change",function(){
   if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { menu_categories: $(this).val(), view: $(this).attr("name"),  state: state, search_text: $("#search_text").val(), menu_type: $("#menus").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$("#categories").on("change",function(){
   if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { selected_category: $(this).val(), view: $(this).attr("name"),  state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$("#show_deleted").on("change",function(){
    if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { selected_category: $("#categories").val(), view: $(this).attr("name"), state: state, search_text: $("#search_text").val(), menu_categories: $("#menu_categories").val(), menu_type: $("#menus").val()  }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$("#search").on("click",function(){
    if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { selected_category: $("#categories").val(), view: $(this).attr("name"), state: state, search_text: $("#search_text").val(), menu_categories: $("#menu_categories").val(), menu_type: $("#menus").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$("#list").on("click",".delete",function(){
    if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php?action=delete", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: {id: $(this).next().val(), delete: $(this).next().attr("name"), selected_category: $("#categories").val(), view: $(this).attr("name"), state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$("#group_delete").on("click",function(){
    var selected = new Array();
    $(".check:checked").each(function() {
        selected.push($(this).next().val());
    });
    if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php?action=group_delete", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: {ids: selected, delete: "0", selected_category: $("#categories").val(), view: $(this).attr("name"), state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
  })

$("#group_restore").on("click",function(){
    var selected = new Array();
    $(".check:checked").each(function() {
        selected.push($(this).next().val());
    });
    if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php?action=group_delete", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: {ids: selected, delete: "1", selected_category: $("#categories").val(), view: $(this).attr("name"), state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
  })

$("#group_copy").on("click",function(){
    var selected = new Array();
    $(".check:checked").each(function() {
        selected.push($(this).next().val());
    });
    if($("#show_deleted").is(":checked")) state = 1; else state = 0;
    $.ajax({
        url: "/admin/files/ajax.php?action=group_copy", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: {ids: selected, selected_category: $("#categories").val(), view: $(this).attr("name"), state: state, search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
  })

$("#menu_type").on("change",function(){
    $.ajax({
        url: "/admin/files/ajax.php?action=menu_type", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { menu_type: $(this).val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#category").html(data);
        }
    });
})

$("#links").on("change",function(){
    $.ajax({
        url: "/admin/files/ajax.php?action=link_type", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { link_type: $(this).val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#link").html(data);
        }
    });
})

$(".menu").on("change","#article_categories",function(){
    $.ajax({
        url: "/admin/files/ajax.php?action=setArticles", // Url to which the request is send
        type: "POST",             // Type of request to be send, called as method
        data: { selected_category: $(this).val(), search_text: $("#search_text").val() }, 
        success: function(data)   // A function to be called if request succeeds
        {
            $("#list").html(data);
        }
    });
})

$(".menu").on("click", ".article_select", function(){
    $(".article_select").each(function() {
        $(this).css("background-color", "transparent");
    });
    $("#selectedArticle").val(this.id);
    $(this).css("background-color", "#B2FFB2");
})