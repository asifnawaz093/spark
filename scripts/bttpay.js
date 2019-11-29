function BttPay() {}
BttPay.prototype = {
    constructor: BttPay,
    apikey: false,
    endurl :"https://bttpay.com/pay/authorize/bttpay.php",
    logError: function(error){ console.log(error); },
    setApiKey: function(key){ this.apikey = key; },
    getJson:function(data,callback){
        $.ajax({
            url:this.endurl,data:data,type:"post", dataType:"jsonp",
            jsonpCallback: callback,
            success:callback,
            error:function(a,i,o){console.log("Failed to connect " + a + " " + i + " " + o)}
        });
    },
    getToken:function(cc,resonse){
        cc.action = "getToken";
        cc.apikey = this.apikey;
        this.getJson(cc,resonse);
    }
}
BttPay = new BttPay();