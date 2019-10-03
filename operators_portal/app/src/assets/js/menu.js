// Listeners
$(".menu-item").click(function (event) {
    
    $(".menu-item").unbind();
    $(event.target.children)[0].click();
});