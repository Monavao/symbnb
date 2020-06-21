handleDeleteButtons();
$('#add-image').click(function () {
    const $adImages = $('#ad_images');
    const index     = +$('.gallery-element').length;
    const tmp       = $adImages.data('prototype').replace(/__name__/g, index);

    $adImages.append(tmp);

    handleDeleteButtons();
});

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    });
}
