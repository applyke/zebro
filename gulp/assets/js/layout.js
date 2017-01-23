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


    $('.disableUser').click(function () {
        var userId = $(this).data('id');
        var project_id = $('.project').data('id');
        $.ajax({
            type: "POST",
            url: "/projects/users/" + project_id,
            data: 'user='+userId+ '&disabledUser='+true,
            dataType: "html",
            success: function () {
                $('.disableUser[data-id="'+userId+'"]').hide();
            }
        });
    });
    $('.showPermission').click(function () {
        var id = $(this).data('id');
        var project_id = $('.project').data('id');
        $.ajax({
            type: "POST",
            url: "/projects/users/" + project_id,
            data: 'user='+id,
            dataType: "html",
            success: function (data) {
                var form = $.parseJSON(data);
                $('.permissionForm').html(form.html);
                $("#dialogAccount").show();
            }
        });
    });
    $('.modal-actionAccount').click(function () {
        var data = $( "form" ).serialize();
        var project_id = $('.project').data('id');
        $.ajax({
            type: "POST",
            url: "/projects/users/" + project_id,
            data: data,
            dataType: "html",
            success: function (data) {
               $( "form" ).remove();
            }
        });
        $("#dialogAccount").hide();
    });
    $('.buttonCloseAccount').click(function () {
        $("#dialogAccount").hide();
    });

});
