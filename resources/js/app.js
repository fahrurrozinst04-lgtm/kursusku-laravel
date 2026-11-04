import './bootstrap';
import 'bootstrap';
import $ from 'jquery';

window.$ = window.jQuery = $;

$(document).on('change', '#course_id', function () {
    const id = this.value;

    if (!id) {
        $('#availability')
            .removeClass('alert-success alert-danger alert-warning')
            .addClass('alert-secondary')
            .text('Pilih kursus untuk melihat kuota dan status.');
        return;
    }

    $('#availability')
        .removeClass('alert-success alert-danger alert-warning')
        .addClass('alert-secondary')
        .text('Memuat ketersediaan...');

    $.ajax({
        url: `/availability/${id}`,
        method: 'GET',
        dataType: 'json',
        success(data) {
            const status = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            const open = data.status === 'open';

            $('#availability')
                .removeClass('alert-secondary alert-danger alert-warning alert-success')
                .addClass(open ? 'alert-success' : 'alert-warning')
                .text(`${data.code} · ${data.name}. Kuota ${data.quota}, status ${status}.`);
        },
        error() {
            $('#availability')
                .removeClass('alert-secondary alert-success alert-warning')
                .addClass('alert-danger')
                .text('Gagal memuat ketersediaan kursus.');
        }
    });
});
