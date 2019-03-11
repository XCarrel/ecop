// X. Carrel
// Oct 2018
// The js that animates the observations page

// Check input fields and enable/disable the submit button accordingly
function checkFields()
{
    let ok = false;
    $('input:radio').each(function () {
        if ($(this).prop('checked')) ok = true;
    })

    ok &= $('#details').val().length > 5
    ok &= $('#weight').val() != 0

    if ($('#otherev').prop('checked')) // The evidence description field must be filled
        if ($('#newevdesc').val().length <= 5) // insufficient description
            ok = false

    if (ok)
        $('#btnsubmit').removeClass('d-none')
    else
        $('#btnsubmit').addClass('d-none')
}

$(document).ready(function () {
    $('#btnsubmit').addClass('d-none')

    $('input:radio').click(function () {
        $('#weight').val($(this).data('weight'))
        checkFields()
    })

    $('#details').keydown(function () {
        checkFields()
    })
    $('#weight').keydown(function () {
        checkFields()
    })
    // because change doesn't fire everytime I click on numeric up/down (!?!?)
    $('#weight').click(function () {
        checkFields()
    })

    $('#newevdesc').keydown(function () {
        checkFields()
    })
})