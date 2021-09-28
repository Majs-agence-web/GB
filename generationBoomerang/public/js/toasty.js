$(document).ready(function(){
    $('.toast').toast('hide');
})
$('.fa-star').click(function(){
   $('.toast').toast('show');
   var audio = document.getElementById("mp3");
   audio.play();
});