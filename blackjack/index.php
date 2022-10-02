<?php
$BLACKJACK_STARTED = isset($_POST["blackjack"]);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blackjack | svenar</title>
    <meta name="description" content="Blackjack | svenar">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/confetti.css">
</head>

<body>
    <div class="header">
        <a href="/" class="align-left header-title">svenar's blackjack <small>play responsible</small></a>
        <a class="align-right header-link" href="https://svenar.nl">svenar.nl</a>
    </div>

    <?php if (!$BLACKJACK_STARTED) {
        include("views/main.php");
    } else {
        include("views/game.php");
    }
    ?>
</body>

</html>