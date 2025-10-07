<?php
//XSS対策
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
//セッションにトークンセット
function setToken()
{
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}
function checkToken()
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])) {
        echo 'Invalid POST', PHP_EOL;
        exit;
    }
}
function validation($datas, $confirm = true)
{
    $errors = [];
    //ユーザー名の確認
    if (empty($datas['name'])) {
        $errors['name'] = 'ユーザー名を入力してください。';
    } else if (mb_strlen($datas['name']) > 20) {
        $errors['name'] = 'ユーザー名は20文字以内で入力してください。';
    }
    //パスワードの確認
    if (empty($datas["password"])) {
        $errors['password'] = "パスワードを入力してください。";
    } else if (!preg_match('/\A(?=.*[a-z])(?=.*\d)[a-z\d]{8,100}\z/i', $datas["password"])) {
    $errors['password'] = 'パスワードは英字と数字を含む8文字以上で入力してください。';
    }

    //パスワード入力確認 ユーザー新規登録時
    if ($confirm) {
        if (empty($datas["confirm_password"])) {
            $errors['confirm_password'] = "パスワードを設定してください。";
        } else if (empty($errors['password']) && ($datas["password"] != $datas["confirm_password"])) {
            $errors['confirm_password'] = "パスワードが一致しません。";
        }
    }

    return $errors;
}
?>