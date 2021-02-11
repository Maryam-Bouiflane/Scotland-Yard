// Basic example
$(document).ready(function () {
    $('#myTable').DataTable({
        "pagingType": "simple",
        "language": {
            "search": "Recherche",
            'paginate': {
                'previous': 'Précédent',
                'next': 'Suivant'
            }
        },
        "dom": '<"toolbar">frtip',
        "info": false
    });
    $("div.toolbar").html('');
    $('.dataTables_length').addClass('bs-select');
});
