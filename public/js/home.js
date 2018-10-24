// X. Carrel
// Oct 2018
// The js that animates the home page

$(document).ready(function(){
    // link table cell to details page
    $('.clickable-cell').click(function() {
        window.location = 'observations/'+$(this).data('pid')+'/'+$(this).data('cid')
    })

    // popup explanation modal on click on row header
    $('.clickable-header').click(function() {
        $('#competence'+$(this).data('cid')).modal('show')
    })
})