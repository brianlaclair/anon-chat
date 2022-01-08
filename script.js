var input 		  	= document.getElementById("chatinput");
var messages		= document.getElementById("messages");
var myCount			= check_message_count();

// Press enter on input
input.addEventListener("keydown", function(event) {
	if (event.keyCode === 13) {
		submit_input();
	}
});

setInterval(function () { check_message_count() }, 300); // check for new messages

function check_message_count() {
	$.post('getCount.php', { rm: room }, 
	function(returnedData){
        if (returnedData > myCount) {
			get_messages(myCount);
		}
        myCount = returnedData;
	});
}

function get_messages(count) {
	// Returns all messages greater than count
	$.post('getMessages.php', { rm: room, start: count },
	function (returnedData) {
		messages.innerHTML += returnedData;
	});
}

function submit_input() {
	
	var message = input.value;
	input.value = "";
	
	$.post('submit.php', { rm: room, msg: message }, 
	function(returnedData){
         console.log(returnedData);
	});
	
}