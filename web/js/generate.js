function generate_link() {
    let link = document.getElementById('basic-url').value;
        const isValidUrl = urlString=> {
          var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
      return !!urlPattern.test(urlString);
    }

    if (!isValidUrl(link))
        alert('ССылка не валидная');

    $.ajax({
        url: '/link/create-short-link',
        type: 'POST',
        data: {link: link},
        success: function(response) {
            response = JSON.parse(response);

            if (response.short != undefined) {
                $('#short-url').val(response.short);
                $('#qr-text').remove();
                console.log(response);
                $('#qr-image-container').attr('src', response.src);
            } else if (response.error != undefined) {
                alert('Возникла ошибка:' . response.error);
                console.log(response.error);
            }
    },
        error: function(xhr, status, error) {
            alert('Ошибка: ' +  error);
        }
    });
    $('#qr-text').remove();
    $('#qr-image-container').after('<span style="color:red;" id="qr-text"> ОЖИДАЕМ QR генерируется</span>');

//     $.ajax({
//        type: 'POST',
//         url: '/link/generate-qr',
//         data: { link: link },
//         success: function(base64Data) {
//           $('#qr-text').remove();
//           console.log(base64Data);
//             $('#qr-image-container').attr('src', base64Data);
//         }
// });

}

