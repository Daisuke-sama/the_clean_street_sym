$(document).ready(function () {
    $('.article-like-button').on('click', function (e) {
        e.preventDefault();

        let $link = $(e.currentTarget);
        $link.toggleClass('fa-heart-o').toggleClass('fa-heart');

        $.ajax({
            method: 'POST',
            url: $link.prop('href')
        }).done(function (data) {
            $('.article-like-count').html(data.likes);
        });
    })
});