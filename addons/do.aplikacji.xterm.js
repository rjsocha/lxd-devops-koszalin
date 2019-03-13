window.addEventListener("message", function(e){
	if (event.origin !== "https://zero.nauka.ga/app.php")
    	return;
    try{ 
    	var data = JSON.parse(e.data);
    }catch(e){ console.log(e.data);}
    window.term[data.method](data.value);
}, false);
