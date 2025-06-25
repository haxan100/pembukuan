<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Futuristic</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap');
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: radial-gradient(circle, #001f3f, #003366);
            color: #ffcc00;
            font-family: 'Orbitron', sans-serif;
            text-align: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 60px;
            box-shadow: 0px 0px 25px #ffcc00;
            border-radius: 15px;
            text-align: center;
            max-width: 800px;
            position: relative;
            border: 4px solid #ffcc00;
        }
        .icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: url('https://cdn-icons-png.flaticon.com/512/1077/1077114.png') no-repeat center;
            background-size: contain;
        }
        h2 {
            margin-bottom: 20px;
            text-shadow: 0 0 15px #ffcc00;
            font-size: 36px;
            color: #003366;
        }
        p {
            margin-bottom: 30px;
            font-size: 20px;
            color: #003366;
        }
        .input-container {
            position: relative;
            width: 80%;
            margin: 20px auto;
            display: flex;
            align-items: center;
        }
        input {
            width: 100%;
            padding: 18px;
            border: 2px solid #ffcc00;
            background: rgba(255, 255, 255, 0.8);
            color: #003366;
            border-radius: 12px;
            font-size: 22px;
            text-align: center;
            outline: none;
            box-sizing: border-box;
        }
        input::placeholder {
            color: #666;
            text-align: center;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            cursor: pointer;
            font-size: 22px;
        }
        button {
            width: 80%;
            padding: 20px;
            background: linear-gradient(90deg, #ffcc00, #ffdd44);
            color: black;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0px 0px 20px #ffcc00;
        }
        button:hover {
            background: linear-gradient(90deg, #ffdd44, #ffcc00);
            box-shadow: 0px 0px 25px #ffcc00;
        }
        .error {
            color: red;
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="icon"></div>
        <h2>Selamat Datang</h2>
        <p>Silahkan login di <strong>Plus Minus Indonesia</strong></p>
        <div class="input-container">
            <input type="text" id="username" placeholder="Username">
        </div>
        <div class="input-container">
            <input type="password" id="password" placeholder="Password">
            <span class="toggle-password">👁️</span>
        </div>
        <button id="login-btn" type="submit">Login</button>
        <p class="error" id="error-msg"></p>
    </div>
	</body>
	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<script>
		$(document).ready(function() {
			$(".toggle-password").click(function() {
                let passwordField = $("#password");
                let type = passwordField.attr("type") === "password" ? "text" : "password";
                passwordField.attr("type", type);
                $(this).text(type === "password" ? "👁️" : "🙈");
            });
			$('#login-btn').click(function (e) { 
				e.preventDefault();
				

				const username = $('#username').val();
				const password = $('#password').val();

				$.ajax({
					url: '<?= base_url("Admin/process_login"); ?>',
					type: 'POST',
					data: {
						username,
						password
					},
					dataType: 'json',
					success: function(response) {
						if (response.status === 'success') {
							Swal.fire('Success', response.message, 'success').then(() => {
								window.location.href = response.redirect;
							});
						} else {
							Swal.fire('Error', response.message, 'error');
						}
					},
					error: function() {
						Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
					}
				});
			});
		});
	</script>



</html>
