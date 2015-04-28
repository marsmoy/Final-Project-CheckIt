var pubKey = 'pub-c-234be608-9e97-43e7-b10a-cca06d4daf82';
var subKey = 'sub-c-42bb9cbc-b7e2-11e4-addc-0619f8945a4f';
var channelName = "web_stock";

function initPN() {
 
     pubnub = PUBNUB.init({                                  
         publish_key   : pubKey,
         subscribe_key : subKey
     })
 
     pubnub.subscribe({                                      
         channel : channelName,
         message : function(message,env,channel){
           document.getElementById('chat').innerHTML +=
           message.username + ": " + message.text  + '<br>'
         },
         connect: pub
     })
 
     function pub() {
        
     }
 };

function sendMessage(){
	var textBox = document.getElementById('textBox');
	var sendMsg = textBox.value;
	var user    = "Anonymous"; 
	// Check cookies
	// if isset($cookie_email) $username=$_COOKIE[$cookie_email];
	// else { $username = "Anonymous"; }
	var payload = {text:sendMsg, username:user};
	pubnub.publish({                                     
         channel : channelName,
         message : payload,
         callback: function(m){ console.log(m) }
    })
}
initPN();