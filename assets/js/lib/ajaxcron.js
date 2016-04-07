function crossDomainRequest(){
	/*
    Ext.Ajax.request({
        timeout:300000,
        url: site_url + '/tmessage/AdaPesanBaru',   
        scriptTag: true,
        success: function(r) {				
            var message = r.responseText;
            console.log(message);
			
            crossDomainRequest();
        }
    });*/
    //console.log(Ext.getCmp('tree-panel').getSelectionModel());
    //Ext.getCmp('tree-panel').getNodeById("00:01:04").setText('HHHHH');
	// set the date we're counting down to
var target_date = new Date("December 1, 2016").getTime();
// variables for time units
var days, hours, minutes, seconds;
// get tag element
var countdown = document.getElementById("countdown");
// update the tag with id "countdown" every 1 second
setInterval(function () {
    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;
    // do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;
  
    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;
  
    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);
  
    // format countdown string + set tag value
  countdown.innerHTML = days + " <span class=\'teks\'>hari</span> " + hours + " <span class=\'teks\'>jam</span> "
  + minutes + " <span class=\'teks\'>menit</span> " + seconds + " <span class=\'teks\'>Detik</span>";

}, 1000);
}