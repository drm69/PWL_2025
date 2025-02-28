<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>baby-kid</title>
</head>
<body>
    <h1>baby-kid</h1>
    <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. <br>
        Distinctio asperiores veniam magni vero odio facere delectus <br>
        cum quia culpa commodi! Explicabo nostrum numquam nisi harum a. <br>
        Non at corrupti, officia consequatur illum sunt iste laboriosam <br>
        dolorem aliquam? Consequatur, neque adipisci, architecto nulla <br>
        accusantium inventore cum veritatis quibusdam repellat ea nam. <br>
    </p>
    <br><br>
    <h3>Cek Penjualan</h3>
    <label for="month">Bulan</label>
    <input type="text" name="month">
    <br>
    <label for="date">Tanggal</label>
    <input type="text" name="date">
    <br>
    <a id="loginLink" href="#">Cek Penjualan</a>

    <script>
        document.getElementById("loginLink").addEventListener("click", function(event) {
            event.preventDefault();
            let month = document.querySelector('input[name="month"]').value;
            let date = document.querySelector('input[name="date"]').value;

            if(month && date) {
                window.location.href = "{{ url('/sales') }}/" + encodeURIComponent(month) + "/" + encodeURIComponent(date);
            } else {
                alert("Masukkan Tanggal dan Bulan!");
            }
        });
    </script>
</body>
</html>