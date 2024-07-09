/* global $ */

const optionsDefaultDataTable = {
    paging: false,
    searching: false,
    responsive: true,
    //bInfo: false,
    dom: '<"html5buttons"B>lTfgitp',
    buttons: [],
    "columnDefs": [{
        "targets": 'no-sort',
        "orderable": false,
    }],
};

$(document).ready(function () {

    let spinner = $('#spinner');
    let searchForm = $('#tech-data-search-form');
    let serial = searchForm.find('[name=q]');
    let tableContent = $('#tech-data-documents-table-content');
    let table = tableContent.find('table.tech-data-table').DataTable(optionsDefaultDataTable);
    let modalId = '#tech-data-document-modal';
    let documentModal = $(modalId);
    let documentContent = documentModal.find('.modal-body');

    function handleOpenDocument() {
        $('.tech-data-document').on('click', function (event) {
            event.preventDefault();
            documentContent.html(spinner.html());

            let url = $(this).data('url');
            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {

                    // open pdf link in the new tab
                    if (data.hasOwnProperty('pdf_link') &&
                        data.pdf_link
                    ) {
                        $(modalId).modal('hide');
                        window.open(data.pdf_link).focus();
                        return;
                    }

                    // show html content
                    documentContent.html(data);
                },

                error: function (data) {
                    if (data.status === 401 || data.status === 404) {
                        window.location.reload();
                    } else {
                        const json = $.parseJSON(data.responseText);
                        let error = '';
                        $.each(json, function (k, v) {
                            error += v;
                        });
                        toastr.error('', error);
                        $(modalId).modal('hide');
                    }
                }
            });
        });
    }

    function handleActionViewDocuments() {
        $('.tech-data-view-documents').on('click', function (event) {
            event.preventDefault();

            let deviceId = $(this).data('device-id');
            $('.device-documents-' + deviceId).toggle();

            // close all other tr with documents
            $('tr[class*="device-documents"]').each(function (index, item) {
                let tr = $(item);
                if (!tr.hasClass('device-documents-' + deviceId)) {
                    tr.hide();
                }
            });
        });
    }

    function handleSearchFormSubmit() {
        if (searchForm.length === 0) {
            return;
        }

        searchForm.on('submit', function (event) {
            event.preventDefault();
            event.stopPropagation();

            spinner.show();
            tableContent.hide();
            table.clear().draw();

            let xhr = $.ajax({
                type: 'GET',
                data: {q: serial.val()},
                url: searchForm.attr('action'),
                success: function (data) {
                    let i = 1;
                    $.each(data.documents, function (key, document) {
                        let html = '<a ' +
                            'class="tech-data-document" ' +
                            'href="' + modalId + '" ' +
                            'data-toggle="modal" ' +
                            'data-url="' + document.url + '">' + document.title + '</a>';
                        table.row.add([i, html]);
                        ++i;
                    });
                    table.draw();
                },

                error: function (data) {
                    table.clear().draw();
                    if (data.status === 401 || data.status === 404) {
                        //window.location = data.getResponseHeader('Location');
                        window.location.reload();
                    } else {
                        const json = $.parseJSON(data.responseText);
                        let error = '';
                        $.each(json, function (k, v) {
                            error += v;
                        });
                        toastr.error('', error);
                    }
                }
            });

            xhr.always(function () {
                spinner.hide();
                tableContent.show();
                handleOpenDocument();
            });
        });

        if (serial.length > 0 && serial.val().length > 0) {
            searchForm.trigger('submit');
        }
    }

    handleOpenDocument();
    handleActionViewDocuments();
    handleSearchFormSubmit();

});
