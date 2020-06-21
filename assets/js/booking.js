$(document).ready(function () {
    const $startEndDate    = $('#booking_startDate, #booking_endDate');
    const notAvailableDays = $('#bookingJourney').data('booking-notavailabledays');

    $startEndDate.datepicker({
        format:        'dd/mm/yyyy',
        datesDisabled: JSON.parse(JSON.stringify(notAvailableDays)),
        startDate:     new Date(),
    });

    $startEndDate.on('change', calculateAmount);
});

function calculateAmount() {
    const endDate   = new Date($('#booking_endDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/, '$3-$2-$1'));
    const startDate = new Date($('#booking_startDate').val().replace(/(\d+)\/(\d+)\/(\d{4})/, '$3-$2-$1'));
    const adPrice  = $('#bookingJourney').data('booking-price');

    if (startDate && endDate && startDate < endDate) {
        const DAY_TIME = (24 * 60 * 60 * 1000);


        console.log(JSON.parse(JSON.stringify(adPrice)))

        const interval = endDate.getTime() - startDate.getTime();
        const days     = interval / DAY_TIME;
        const amount   = days * adPrice;

        $('#days').text(days);
        $('#amount').text(amount.toLocaleString('fr-FR'));
    }
}
