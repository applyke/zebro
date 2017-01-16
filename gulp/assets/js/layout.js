// $(document).ready(function () {
//     $("select").change(function () {
//         console.log('select');
//         if ($('#' + $(this).attr("id") + ' option:selected').val() === '') {
//             $(this).parent('div').removeClass('is-dirty');
//         } else {
//             $(this).parent('div').addClass('is-dirty');
//         }
//     });
//
//
// });
$(function () {
    $(".sortable").sortable({
        placeholder: "highlight",
        items: "> li",
        connectWith: ".sortable",
        receive: function () {
            var data = $(this).attr('id');
            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            $.ajax({
                type: "POST",
                url: "/boards/details/" + id,
                data: 'idColumn=' + data + '&idIssue=' + $(this).sortable('toArray'),
                dataType: "html",
                success: function () {}
            });
        },
        update: function () {
            var data = $(this).attr('id');
            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            $.ajax({
                type: "POST",
                url: "/boards/details/" + id,
                data: 'idColumn=' + data + '&idIssue=' + $(this).sortable('toArray'),
                dataType: "html",
                success: function () {}
            });
        }
    }).disableSelection();

});
