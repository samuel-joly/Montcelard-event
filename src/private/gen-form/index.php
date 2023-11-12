<?php
if (isset($_POST["button"])) {
    var_dump($_POST);
    die();
}

function create_guid() { // From comment https://www.php.net/manual/fr/function.com-create-guid.php#124635
        $guid = '';
        $namespace = rand(11111, 99999);
        $uid = uniqid('', true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash,  0,  8) . '-' .
                substr($hash,  8,  4) . '-' .
                substr($hash, 12,  4) . '-' .
                substr($hash, 16,  4) . '-' .
                substr($hash, 20, 12);
        return $guid;
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Fich-o-matic</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body class="font-ledger text-secondary bg-primary h-screen flex justify-center items-center">
        <form action="" method="POST">
            <input type="text" name="destinataire" required/>
            <input type="email" name="email-destinataire" required/>
            <input style="display:none" type="code" name="code" value=<?php echo "\"".create_guid()."\""; ?>/>
            <input type="submit" name="button"/>
        </form>
</body>
</html>
