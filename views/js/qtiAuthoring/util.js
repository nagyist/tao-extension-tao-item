// alert('util loaded');

util = new Object();

CL = function(arg1, arg2){
	if(typeof(console)!='undefined' || console!==null){
		if(console.log){
			if(arg1){
				if(arg2){
					console.log(arg1, arg2);
				}else{
					console.log(arg1);
				}
			}
		}
	}
}

CD = function(object, desc){
	if(typeof(console)!='undefined' || console!==null){
		if(console.log && console.dir){
			if(desc){
				console.log(desc+':');
			}
			console.dir(object);
		}
	}
}

util.htmlEncode = function(encodedStr){
	
	//<br...> are replaced by <br... />
	var encodedStr = encodedStr.replace(/<br([^>]*)?>/ig, '<br />');
	 encodedStr = encodedStr.replace(/<hr([^>]*)?>/ig, '<hr />');
	 
	  //<img...> are replaced by <img... />
	encodedStr = encodedStr.replace(/<img([^>]*)?[^\/]>/ig,
		function($0, $1){
			return '<img ' + $1 + ' />';
		});
	
	//url encode component:
	encodedStr = encodeURIComponent(encodedStr);
	
	return encodedStr;
}

//custom object serialization method to jQuery:
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

