function showGallery(id) {
    var selectedId = document.getElementById("selectedImg").value;
    if (id == "") {
        document.getElementById("gallery").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("gallery").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","/admin/files/images.php/?gallery="+id+"&selectedId="+selectedId,true);
        xmlhttp.send();
    }
}

function setMainImage(img){
    var x = document.getElementsByClassName("prev_images");
    var i;
    for(i=0; i<x.length; i++){
        x[i].style.border = "none";
    }
    img.style.border = "3px solid green";
    document.getElementById("selectedImg").value = img.id;
}

function copyUrl(url){
    var x = document.getElementsByClassName("ft");
    document.getElementById("url").value = url.id;
    var i;
    for(i=0; i<x.length; i++){
        x[i].style.border = "none";
    }
    url.style.border = "3px solid green";
}


function showFolder(id) {
    if (id == "") {
        document.getElementById("doc").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("doc").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","/admin/files/documents.php/?folder="+id,true);
        xmlhttp.send();
    }
}