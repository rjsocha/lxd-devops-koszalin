window.addEventListener("message", function(e){
	if (e.origin !== "https://zero.nauka.ga")
    	return;
    try{ 
    	var data = JSON.parse(e.data);
    }catch(e){ console.log(e.data);}
    console.log(window.term, data);
    window.term[data.method](data.value);
}, false);
