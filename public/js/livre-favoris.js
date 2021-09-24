//on verifie s'il ya des boutton d'ajout en favori dans le DOM
if ($(".bt-favori").length > 0) {
    //on leur ajoute un ecouteur d'evenement sur le click al'aide de la method jquety "on(evenement, claback)"
    $(".bt-favori").on("click", function (event) {
        event.preventDefault();
        event.stopPropagation();
        var bt = $(this);
        var livreId = $(this).attr("data-livreid");//$(this)fait reference le boutton click
        $.ajax({
            url:'/profile/addfavori',
            type:'post',
            data: 'id='+livreId
        }).done(function(response){
               $(bt).hide(); 
        }).fail(function(error){
            console.log("ajax error :", error);
        })
    });
}