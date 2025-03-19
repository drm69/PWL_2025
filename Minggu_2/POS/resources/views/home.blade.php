<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sales</title>
</head>
<body>
    <h1>Selamat Datang</h1>
    <label for="id">ID (NIM)</label>
    <input type="text" name="id">
    <br>
    <label for="name">Nama</label>
    <input type="text" name="name">
    <br>
    <a id="loginLink" href="#">Login</a>

    <script>
        document.getElementById("loginLink").addEventListener("click", function(event) {
            event.preventDefault();
            let id = document.querySelector('input[name="id"]').value;
            let name = document.querySelector('input[name="name"]').value;

            if(id && name) {
                window.location.href = "{{ url('/user') }}/" + encodeURIComponent(id) + "/" + encodeURIComponent(name);
            } else {
                alert("Masukkan ID dan Nama!");
            }
        });
    </script>
</body>
</html>
