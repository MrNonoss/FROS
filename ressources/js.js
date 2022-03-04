//Form submission
function formSubmit() {
    document.forms["osint"].submit();
}

//Load iframe
function changeUrl() {
    document.getElementsByName("iframe")[0].src = site;
}

//Bind functions
function bind(){
	changeUrl(); formSubmit();
}

//Print Help
function printHelper() {
    document.getElementsByName("iframe")[0].src = "ressources/infos.html";
}