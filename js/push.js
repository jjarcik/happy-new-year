$(function(){
    
   $("#firebutton").on("click", function(){
       
       var selected = $("#gpssource option:selected");
       var lat = selected.data("lat");
       var lng = selected.data("lng");
       
       if (lat-0 === 0 && lng-0 === 0){
           lat = Math.random() * 170 - 85;
           lng = Math.random() * 360 - 180;
       }
                     
       $.ajax({
           url:"app/synchro/index.php?action=push&gps_lat="  + lat + "&gps_lng=" + lng
       }); 
    }); 
    
    
});