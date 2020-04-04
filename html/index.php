<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>WebAuthn登録</title>
    <link rel="stylesheet" href="css/bundle.css"/>
</head>
<body translate="no">
<div class="login-page">
    <div class="form">
        <input type="text" placeholder="ユーザ名" value="taro" id="username"/>
        <button onclick="registerAsync()">アカウント作成</button>
        <p class="message">アカウントは作成済みですか？<a href="signin.html">ログイン</a></p>
    </div>
</div>
<script src="js/webauthn.js"></script>
</body>
</html>