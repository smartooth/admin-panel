<?php
    require_once("../private.php");
    if (isset($_GET["oauth_token"])) {
        $twitter->setToken($_GET["oauth_token"]);
        $token = $twitter->getAccessToken();
        $tok = $token->oauth_token;
        $secret = $token->oauth_token_secret;
        $twitter->setToken($tok, $secret);
        $info = $twitter->get_accountVerify_credentials();
        $info->response;
        $db = new db();
        if ($query = $db->prepare("INSERT INTO twitter (username, token, secret, active) VALUES (?, ?, ?, TRUE)")) {
            $query->bind_param("sss", $info->screen_name, $tok, $secret);
            $query->execute();
            $query->close();
        }
        $db->close();
    }
    print_r($info->result);
    //header("Location: /admin");
?>