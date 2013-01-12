<?php
    require_once("../private.php");
    if (isset($_GET["oauth_token"]) && isset($_GET["oauth_verifier"])) {
        $twitter->setToken($_GET["oauth_token"]);
        try {
        $token = $twitter->getAccessToken(array('oauth_verifier' => $_GET['oauth_verifier']));
        } catch (Exception $e) {
            header("Location: /admin");
        }
        $tok = $token->oauth_token;
        $secret = $token->oauth_token_secret;
        $twitter->setToken($tok, $secret);
        $info = $twitter->get_accountVerify_credentials();
        $db = new db();
        if ($check = $db->prepare("SELECT username FROM twitter WHERE username=?")) {
            $check->bind_param("s", $info->screen_name);
            $check->execute();
            $check->bind_result($username);
            $check->fetch();
            $check->close();
            if (!$username) { // new authenticate
                if ($query = $db->prepare("INSERT INTO twitter (username, token, secret, active) VALUES (?, ?, ?, TRUE)")) {
                    $db->query("UPDATE twitter set active=0;"); // hacky
                    $query->bind_param("sss", $info->screen_name, $tok, $secret);
                    $query->execute();
                    $query->close();
                }
            } else { // logging back in for token
                if ($query = $db->prepare("UPDATE twitter SET token=? secret=? active=1 WHERE username=?")) {
                    $db->query("UPDATE twitter set active=0;"); // hacky
                    $query->bind_param("sss", $tok, $secret, $info->screen_name);
                    $query->execute();
                    $query->close();
                }
            }
        }
        $db->close();
    }
    header("Location: /admin");
?>