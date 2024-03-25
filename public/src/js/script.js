(() => {
    $('body').on('click', '.add-like', function(e) {
        e.preventDefault()

        const btnLike = $(this)

        $.ajax({
            url: $(this).data('url'),
            success: function (response) {
                btnLike.html('<i class="bi bi-hand-thumbs-up-fill"></i>')
                btnLike.attr('class', 'remove-like')
                // let incr = parseInt(btnLike.data('nb-likes'))
                // incr++
                // btnLike.next('.nb-likes').html(incr)
            }
        })
    })

    $('body').on('click', '.remove-like', function(e) {
        e.preventDefault()

        const btnRemove = $(this)

        $.ajax({
            url: $(this).data('url'),
            success: function (response) {
                btnRemove.html('<i class="bi bi-hand-thumbs-up"></i>')
                btnRemove.attr('class', 'add-like')
                // let decr = parseInt(btnRemove.data('nb-likes'))
                // decr--
                // btnRemove.next('.nb-likes').html(decr)

            }
        })
    })
})()
