<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyurtma berish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h1>Сделат заказ</h1>
    <form id="orderForm" action="/api/orders" method="POST">
        @csrf
        <div class="mb-3">
            <label for="event_id" class="form-label">Заказ ID:</label>
            <input type="number" class="form-control" id="event_id" name="event_id" required>
        </div>
        <div class="mb-3">
            <label for="event_date" class="form-label">День заказа:</label>
            <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
        </div>
        <div class="mb-3">
            <label for="ticket_adult_price" class="form-label">Сумма билета:</label>
            <input type="number" class="form-control" id="ticket_adult_price" name="ticket_adult_price" required>
        </div>
        <div class="mb-3">
            <label for="ticket_adult_quantity" class="form-label">Количество билетов:</label>
            <input type="number" class="form-control" id="ticket_adult_quantity" name="ticket_adult_quantity" required>
        </div>
        <div class="mb-3">
            <label for="ticket_kid_price" class="form-label">Сумма детских билетов:</label>
            <input type="number" class="form-control" id="ticket_kid_price" name="ticket_kid_price" required>
        </div>
        <div class="mb-3">
            <label for="ticket_kid_quantity" class="form-label">Количество билетов:</label>
            <input type="number" class="form-control" id="ticket_kid_quantity" name="ticket_kid_quantity" required>
        </div>
        <button type="submit" class="btn btn-primary">Заказать</button>
    </form>

    <div id="responseMessage" class="mt-3"></div>
</div>

<script>
    $(document).ready(function() {
        $('#orderForm').on('submit', function(event) {
            event.preventDefault();

            const formData = {
                event_id: $('#event_id').val(),
                event_date: $('#event_date').val(),
                ticket_adult_price: $('#ticket_adult_price').val(),
                ticket_adult_quantity: $('#ticket_adult_quantity').val(),
                ticket_kid_price: $('#ticket_kid_price').val(),
                ticket_kid_quantity: $('#ticket_kid_quantity').val(),
                _token: $('input[name="_token"]').val(),
            };

            $.ajax({
                type: 'POST',
                url: '/api/orders',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                },
                error: function(xhr) {
                    const errorResponse = xhr.responseJSON;
                    const errors = errorResponse.errors ? errorResponse.errors : [errorResponse.error];
                    $('#responseMessage').html(`<div class="alert alert-danger">${errors.join(', ')}</div>`);
                }
            });
        });
    });
</script>

</body>
</html>
