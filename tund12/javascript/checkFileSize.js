//window.alert("420 blaze it");
//console.log("Ganja main");

window.onload = function(){
	document.getElementById("submitImage").disabled = true;
	document.getElementById("fileToUpload").addEventListener("change", checkSize);
}

function checkSize(){
	let fileToUpload = document.getElementById("fileToUpload").files[0];
	if(fileToUpload.size <= 2500000){
		document.getElementById("submitImage").disabled = false;
	} else {
		document.getElementById("infoPlace").innerHTML = "Kahjuks valisite liiga suure faili!";
	}

}