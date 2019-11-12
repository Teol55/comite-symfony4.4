
tickets=document.getElementsByClassName('ticket__offer');
rowSelectedElts=document.getElementsByClassName('')
function close(){
    divsCard=document.getElementsByClassName('ticket__offer__card');
    divsCard.forEach(function (div){

        div.style.display='none';

    })
    triangleElts=document.getElementsByClassName('ticket__offer__triangle');
    triangleElts.forEach(function (triangle) {
        triangle.style.opacity=0;

    })

    // delete the row seleceted by an other clic
    var rowSelecetedElts=document.getElementsByClassName('test__row--selected');
    rowSelecetedElts.forEach(function(row){
        row.classList.remove("test__row--selected");
    })

    // delete the offer activate by an other clic

    var offerActiveElts=document.getElementsByClassName('test__offer--active');
    offerActiveElts.forEach(function (offer) {
        offer.classList.remove('test__offer--active');

    })
}
tickets.forEach(function (ticket) {
        ticket.addEventListener('click',function  (e){console.log('j ai cliqué');

        close();


        // add class test__row__selected at the row who offer was actived
        var rowOfferElt=e.target.closest(".row");
       rowOfferElt.classList.add('test__row--selected')

        // add class offer--active for check the offer was clic
        var offerActiveElt=e.target.closest('.ticket__offer__container');
        offerActiveElt.classList.add('test__offer--active')
            // show the card element with triangle
        var cardElt=offerActiveElt.querySelector('.ticket__offer__card');
        var triangleElt=offerActiveElt.querySelector('.ticket__offer__triangle');
        cardElt.style.display='block';
        triangleElt.style.opacity=1;

            closeElt=offerActiveElt.querySelector('.ticket__cardBox__close');

            closeElt.addEventListener('click',close);


    });


})

