$(function () {
    $('body').on('click', '.btn-favorite', function(e) {
        e.preventDefault();

        const isFavorite = $(this);
        const route = isFavorite.data('href');

        console.log(isFavorite.children().attr('class'));

        $.ajax({
            url: route,
            success: function (response) {
                if (isFavorite.children().hasClass('bi-hand-thumbs-up-fill')) {
                    isFavorite.children().removeClass('bi-hand-thumbs-up-fill')
                    isFavorite.children().addClass('bi-hand-thumbs-up')
                } else {
                    isFavorite.children().removeClass('bi-hand-thumbs-up')
                    isFavorite.children().addClass('bi-hand-thumbs-up-fill')
                }
            }
        })
    })
});


