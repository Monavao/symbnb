handleDeleteButtons();
$('#add-image').click(function () {
    const index = +$('.gallery-element').length;
    const tmp   = $('#ad_images').data('prototype').replace(/__name__/g, index);

    $('#ad_images').append(tmp);

    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;

        $(target).remove();
    });
}
