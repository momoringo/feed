<?php
/*
Plugin Name: Custom New User Mail
Description: 新規ユーザー登録時に送信されるメール内容を変更します
*/
//ユーザ通知機能を再定義
if ( !function_exists('wp_new_user_notification') ) {
 function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {

 $user = new WP_User($user_id);

 $user_login = stripslashes($user->user_login);
 $user_email = stripslashes($user->user_email);
 $login_url = wp_login_url();
 $blog_name = get_option('blogname');
 $home_url = get_option('home');

 //管理人に送信されるメールの内容
 $message = sprintf(__('「%s」への新規ユーザ登録が行われました'), $blog_name) . "\r\n\r\n";
 $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
 $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

 @wp_mail(
 get_option('admin_email'),
 sprintf(__('[%s] New User Registration'),$blog_name),
 $message
 );

 if ( empty($plaintext_pass) )
 return;

//新規ユーザに送信されるメールの内容
$message = <<< EOM

{$blog_name}サイトへメンバー登録いたしましたので
ログインする際に必要なIDとパスワードをお知らせします。

●━━━━━━━━━━━━━━━━━━━━━━━━━━━●

 ユーザー名: {$user_login}
 パスワード: {$plaintext_pass}

 ログインページ
 {$login_url}

●━━━━━━━━━━━━━━━━━━━━━━━━━━━●


ご不明な点がありましたら下記までお問い合わせください。

 株式会社○○○○
 東京都○○区○○ 12-34
 TEL 00-0000-0000　FAX 00-0000-0000
 MAIL {$user_email}
 URL {$home_url}

EOM;

 wp_mail(
 $user_email,
 sprintf('[%s] メンバーへ登録いたしました', $blog_name),
 $message
 );
 }
}
?>