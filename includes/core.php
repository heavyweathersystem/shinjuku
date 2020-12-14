<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// This file will handle all functions used by Laika's Shinjuku Panel
session_start();
require_once 'database.inc.php';

function register($email, $pass, $code)
{
    global $db;
    $do = $db->prepare("SELECT code, used, level FROM invites WHERE email = (:email)");
    $do->bindParam(':email', $email);
    $do->execute();
    $result = $do->fetch();
    if (!$result['code'] == $code) {
        header('Location: ../register/index.html#fail2');
    } elseif ($result['used'] == '1') {
        header('Location: ../register/index.html#fail3');
    } else {
        $do = $db->prepare("INSERT INTO accounts (email, pass, level) VALUES (:email, :pass, :level)");
        $do->bindParam(':email', $email);
        $do->bindParam(':level', $result['level']);
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $do->bindParam(':pass', $hash);
        $do->execute();
        $do = $db->prepare("UPDATE invites SET used = (:used) WHERE email = (:email)");
        $do->bindValue(':used', '1');
        $do->bindParam(':email', $email);
        $do->execute();
        $_SESSION['id'] = $result['id'];
        $_SESSION['email'] = $result['email'];
        header('Location: api.php?do=cp');
    }
}

function generate($email, $level)
{
    global $db;
    if ($_SESSION['level'] === '1') {
        if (empty($email) or empty($level)) {
            include_once('../templates/invites.php');
        } else {
            $do = $db->prepare("INSERT INTO invites (email, code, level) VALUES (:email, :code, :level)");
            $do->bindParam(':email', $email);
            $code = generateRandomString();
            $do->bindParam(':code', $code);
            $do->bindParam(':level', $level);
            $do->execute();
            require_once('Mail.php');
            $from = "Invites <invites@".LAIKA_ADDRESS.">";
            $to = $email;
            $subject = LAIKA_NAME." Account Invite";
            $body = "This is a automated message from ".LAIKA_NAME." \n Your invite code is: ".$code."\n Your invite email is: ".$email." \n Access level: ".$level." \n Register at ".SHINJUKU_URL."/register";

            $host = SMTPD_HOST;
            $username = SMTPD_USERNAME;
            $password = SMTPD_PASSWORD;

            $headers = array ('From' => $from,
                    'To' => $to,
                    'Subject' => $subject);
            $smtp = Mail::factory(
                'smtp',
                array ('host' => $host,
                        'auth' => true,
                        'username' => $username,
                'password' => $password)
            );

                    $mail = $smtp->send($to, $headers, $body);

            if (PEAR::isError($mail)) {
                echo("<p>" . $mail->getMessage() . "</p>");
            } else {
                echo("<p>Message successfully sent!</p>");
            }
        }
    } else {
        echo 'What are you doing here? Go away!';
    }

}

function generateRandomString()
{
    $characters = ID_CHARSET;
    $randomString = '';
    for ($i = 0; $i < LENGTH; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function login($email, $pass)
{
    global $db;
    $do = $db->prepare("SELECT pass, id, email, level FROM accounts WHERE email = (:email)");
    $do->bindParam(':email', $email);
    $do->execute();
    $result = $do->fetch(PDO::FETCH_ASSOC);

    if (password_verify($pass, $result['pass'])) {
        $_SESSION['id'] = $result['id'];
        $_SESSION['email'] = $result['email'];
        $_SESSION['level'] = $result['level'];
        header('Location: api.php?do=cp');
    } else {
        header('Location: ../login/index.html#fail');
    }
}

function cfdelete($file)
{

    $cloudflare = array(
        'a' => 'zone_file_purge',
        'tkn' => CF_TOKEN,
        'email' => CF_EMAIL,
        'z' => LAIKA_URL,
        'url' => urlencode(LAIKA_URL.$file),
    );

    foreach ($cloudflare as $dick => $cum) {
        $cloudflare_string .= $dick.'='.$cum.'&';
    }
    rtrim($cloudflare_string, '&');

    $hue = curl_init();
    curl_setopt($hue, CURLOPT_URL, 'https://www.cloudflare.com/api_json.html');
    curl_setopt($hue, CURLOPT_POST, count($cloudflare));
    curl_setopt($hue, CURLOPT_POSTFIELDS, $cloudflare_string);
    curl_setopt($hue, CURLOPT_RETURNTRANSFER, true);
    curl_exec($hue);
    curl_close($hue);
}

function delete($filename, $deleteid)
{
    if (empty($filename)) {
        echo "You did something wrong, baka.";
    } else {
        global $db;
        $do = $db->prepare("SELECT filename, delid, id, user FROM files WHERE filename = (:filename)");
        $do->bindParam(':filename', $filename);
        $do->execute();
        $result = $do->fetch(PDO::FETCH_ASSOC);

        if ($_SESSION['level'] === '1' || $result['user'] === $_SESSION['id']) {
            $do = $db->prepare("DELETE FROM files WHERE id = (:id)");
            $do->bindParam(':id', $result['id']);
            $do->execute();
            unlink(LAIKA_FILES_ROOT.$filename);
            cfdelete($filename);
            echo "<br/>File deleted and hopefully deleted from Cloudflares cache in a moment..<br/>";
        } else {
            echo 'Shame on you';
        }
    }
}

function fetchFiles($date, $count, $keyword)
{
    global $db;
    if ($_SESSION['level'] > '0') {
        $do = $db->prepare("SELECT * FROM files WHERE originalname LIKE (:keyword) AND date LIKE (:date) OR filename LIKE (:keyword) AND date LIKE (:date) ORDER BY id DESC LIMIT 0,:amount");
    } else {
        $do = $db->prepare("SELECT * FROM files WHERE originalname LIKE (:keyword) AND date LIKE (:date) AND user = (:userid) OR filename LIKE (:keyword) AND date LIKE (:date) AND user = (:userid) ORDER BY id DESC LIMIT 0,:amount");
        $do->bindValue(':userid', $_SESSION['id']);
    }

    $do->bindValue(':date', "%".$date."%");
    $do->bindValue(':amount', (int) $count, PDO::PARAM_INT);
    $do->bindValue(':keyword', "%".$keyword."%");

    require('../templates/search.php');

    $do->execute();
    $i = 0;
    while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
        $i++;
        echo '<tr><td>'.$row['id'].'</td>
            <td>'.strip_tags($row['originalname']).'</td>
            <td><a href="'.LAIKA_URL.$row['filename'].'" target="_BLANK">'.$row['filename'].'</a> ('.$row['originalname'].')</td>
            <td>'.$row['size'].'</td>
            <td><a class="btn btn-default" href="'.SHINJUKU_URL.'/includes/api.php?do=delete&action=remove&fileid='.$row['id'].'&filename='.$row['filename'].'" target="_BLANK">Remove</a></td></tr>';
    }
    echo '</table>';
    require('../templates/footer.php');
    echo '<p>'.$i.' Files in total at being shown.</p>';


}

function report($file)
{
    global $db;
    if (empty($file)) {
        include('../templates/report.php');
    } else {
        $do = $db->prepare("select id, hash from files where filename = :file");
        $do->bindValue(':file', strip_tags($file));
        $do->execute();
        $query = $do->fetch(PDO::FETCH_ASSOC);

        $do = $db->prepare("INSERT INTO reports (hash, date, file, fileid, reporter) VALUES (:hash, :date, :file, :fileid, :reporter)");
        $do->bindValue(':file', strip_tags($file));
        $do->bindValue(':date', date('Y-m-d'));
        $do->bindValue(':reporter', $_SESSION['email']);
        $do->bindValue(':fileid', $query['id']);
        $do->bindValue(':hash', $query['hash']);
        $do->execute();
        echo 'Thank you, report has been sent. The file will be reviewed and probably deleted.';
    }

}

function mod($action, $date, $count, $why, $file, $keyword, $fileid, $hash, $orginalname)
{
    if ($_SESSION['level'] > '0') {
        global $db;
        switch ($action) {
            case "fetch":
                fetchFiles($date, $count, $keyword);
                break;

            case "report":
                report($file, $fileid, $count);
                break;

            case "reports":
                if ($_SESSION['id'] === '1') {
                    $do = $db->prepare("SELECT * FROM reports WHERE status = '0'");
                    $do->execute();

                    $i = 0;
                    require('../templates/reports.php');
                    while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
                        $i++;
                        echo '<tr><td>'.$row['id'].'</td>
                        <td><a href="'.LAIKA_URL.strip_tags($row['file']).'" target="_BLANK">'.strip_tags($row['file']).'</td>
                        <td>'.$row['fileid'].'</td>
                        <td>'.$row['reporter'].'</td>
                        <td>'.$row['status'].'</td>
                        <td><a class="btn btn-default" href="'.SHINJUKU_URL.'/includes/api.php?do=mod&action=remove&fileid='.$row['fileid'].'&file='.$row['file'].'" target="_BLANK">Remove file</a></td></tr>';
                    }
                    echo '</table>';
                    require 'footer.php';
                    echo $i.' Reports in total at being shown.';
                } else {
                    echo 'You are not allowed to be here, yet.';
                }
                break;

            case "remove":
                if ($_SESSION['id'] < '0') {
                    delete($file, $fileid);
                }
                if ($_SESSION['id'] > '0') {
                    $do = $db->prepare("DELETE FROM files WHERE id = (:id)");
                    $do->bindParam(':id', $fileid);
                    $do->execute();
                    unlink(LAIKA_FILES_ROOT.$file);
                    cfdelete($file);
                    $do = $db->prepare("UPDATE reports SET status = (:status) WHERE fileid = (:fileid)");
                    $do->bindValue(':status', '1');
                    $do->bindValue(':fileid', $fileid);
                    $do->execute();
                    echo 'Deleted';
                    break;
                }
        }
    }
}
