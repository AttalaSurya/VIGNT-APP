<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
</head>
<style>
    body {
    background-color: #f5f5f5;
    color: #636b6f;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    font-family: 'Nunito', sans-serif;
}


.container {
    text-align: center;
}

.error-message {
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.error-message h1 {
    font-size: 96px;
    margin: 0;
}

.error-message p {
    font-size: 18px;
    margin: 10px 0;
}

button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    margin: 5px;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

form button {
    margin-top: 10px;
}
</style>
<body>
    <div class="container">
        <div class="error-message">
            <h1>403</h1>
            <p>Forbidden</p>
            <p>Only {! $method !} method is allowed</p>
            <button onclick="goHome()">Go Home</button>
        </div>
        
    </div>
</body>
</html>
<script>

function goHome() {
    window.location.href = '/';
}

</script>
