var xmlhttp;



function loadXMLDoc(url,cfunc) {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=cfunc;
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}



function word_expansion(word, divid) {
    
    loadXMLDoc("http://cobi.dcs.bbk.ac.uk/moodle/local/libe-personalised-word-expansion/wordexpansion.php?word=" + word,function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var myArr = JSON.parse(xmlhttp.responseText);
            document.getElementById(divid).innerHTML = myArr.wordexpansion;
		}
	});   
}



function word_popup(word, divid) {
    
    loadXMLDoc("http://cobi.dcs.bbk.ac.uk/moodle/local/libe-personalised-word-expansion/wordexpansion.php?word=" + word,function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            var myArr = JSON.parse(xmlhttp.responseText);
            document.getElementById(divid).title = myArr.wordexpansion;
		}
	});   
}



function word_restore(word, divid) {
    document.getElementById(divid).innerHTML = word;
}



function create_profile(page, type) {
    
    if (type == 0) {
		var str = "page";
	} else {
		var str = "quiz";
    }
    
    loadXMLDoc("http://cobi.dcs.bbk.ac.uk/moodle/local/libe-personalised-word-expansion/addlearnerprofile.php",function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            window.location='http://cobi.dcs.bbk.ac.uk/moodle/mod/' + str + '/view.php?id=' + page;
		}
	});
}



function update_ability_level(quiz, page, type) {
	
	if (type == 0) {
		var str = "page";
	} else {
		var str = "quiz";
    }
    
    loadXMLDoc("http://cobi.dcs.bbk.ac.uk/moodle/local/libe-personalised-word-expansion/updateabilitylevel.php?quiz=" + quiz,function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            window.location='http://cobi.dcs.bbk.ac.uk/moodle/mod/' + str + '/view.php?id=' + page;
		}
	});
}



function log_ability_level(quiz) {
    
    loadXMLDoc("http://cobi.dcs.bbk.ac.uk/moodle/local/libe-personalised-word-expansion/abilitylevellog.php?quiz=" + quiz,function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
            update_ability_level(quiz, page);
		}
	});
}
