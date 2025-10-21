<?php
// XSS対策
function h($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, "UTF-8");
}

// セッションにトークンセット
function setToken() {
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

// トークンチェック（CSRF対策）
function checkToken() {
    if (empty($_SESSION['token']) || ($_SESSION['token'] != ($_POST['token'] ?? ''))) {
        echo 'Invalid POST', PHP_EOL;
        exit;
    }
}

// バリデーション
function validation($datas, $confirm = true) {
    $errors = [];

    // ユーザー名の確認
    if (empty($datas['u_name'])) {
        $errors['u_name'] = 'ユーザー名を入力してください。';
    } else if (mb_strlen($datas['u_name']) > 20) {
        $errors['u_name'] = 'ユーザー名は20文字以内で入力してください。';
    }

    // メールアドレスの確認
    if (empty($datas['u_mail'])) {
        $errors['u_mail'] = 'メールアドレスを入力してください。';
    } else if (!filter_var($datas['u_mail'], FILTER_VALIDATE_EMAIL)) {
        $errors['u_mail'] = '正しいメールアドレスの形式で入力してください。';
    }

    // パスワードの確認
    if (empty($datas["u_password"])) {
        $errors['u_password'] = "パスワードを入力してください。";
    } else if (!preg_match('/\A(?=.*[a-z])(?=.*\d)[a-z\d]{8,100}\z/i', $datas["u_password"])) {
        $errors['u_password'] = 'パスワードは英字と数字を含む8文字以上で入力してください。';
    }

    // パスワード入力確認（再入力チェック）
    if ($confirm) {
        if (empty($datas["confirm_password"])) {
            $errors['confirm_password'] = "パスワード（再確認）を入力してください。";
        } else if (empty($errors['u_password']) && ($datas["u_password"] != $datas["confirm_password"])) {
            $errors['confirm_password'] = "パスワードが一致しません。";
        }
    }

    return $errors;
}
function validationlogin($datas, $confirm = true) {
     // メールアドレスの確認
    if (empty($datas['u_mail'])) {
        $errors['u_mail'] = 'メールアドレスを入力してください。';
    } else if (!filter_var($datas['u_mail'], FILTER_VALIDATE_EMAIL)) {
        $errors['u_mail'] = '正しいメールアドレスの形式で入力してください。';
    }

    // パスワードの確認
    if (empty($datas["u_password"])) {
        $errors['u_password'] = "パスワードを入力してください。";
    } else if (!preg_match('/\A(?=.*[a-z])(?=.*\d)[a-z\d]{8,100}\z/i', $datas["u_password"])) {
        $errors['u_password'] = 'パスワードは英字と数字を含む8文字以上で入力してください。';
    }
}