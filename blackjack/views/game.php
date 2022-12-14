<?php
include("blackjack.php");

$image_path = "/images/cards/" . (isset($_GET["bj"]) ? "alt" : "normal") . "/";

$game = new Blackjack();
$game->setup();
?>

<div class="background background-game"></div>

<div class="content">
    <h1 class="text-center"><small>Dealer <?php echo $game->get_cpu_score(); ?> | Player <?php echo $game->get_player_score(); ?></small></h1>

    <?php if ($game->get_game_state_message_level() == "success") : ?>
        <div class="confetti-container">
            <?php for ($index = 0; $index < 20; $index++) : ?>
                <div class="confetti"></div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <?php if ($game->has_placed_bet()) : ?>
        <br />
        <br />

        <h1 class="text-center">Dealers's cards</h1>
        <div class="card-holder">
            <?php $comp_count = 0; ?>
            <?php foreach ($_SESSION['deck_computer'] as $card) : ?>
                <?php if ($comp_count != 0 || $game->has_game_ended() == true) : ?>
                    <img class="gamecard 3d-perspective" src="<?= $image_path . $card['card'] . $card['type'] ?>.png" width="200px" />
                    <span><?= $game->card_value($card['card']) ?></span>
                <?php else : ?>
                    <img class="gamecard 3d-perspective" src="<?= $image_path ?>closed.png" width="200px" />
                    <span>?</span>
                <?php endif; ?>
                <?php $comp_count++; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($game->has_game_ended() == true) : ?>
            <p class="text-center">Total: <?= $game->calculate_card_deck($_SESSION['deck_computer']) ?></p>
        <?php else : ?>
            <p class="text-center">Total: ?</p>
        <?php endif; ?>

        <br />

        <h1 class="text-center">Your cards</h1>
        <div class="card-holder">
            <?php $comp_count = 0; ?>
            <?php foreach ($_SESSION['deck_player'] as $card) : ?>
                <img class="gamecard 3d-perspective" src="<?= $image_path . $card['card'] . $card['type'] ?>.png" width="200px" />
                <span><?= $game->card_value($card['card']) ?></span>
            <?php endforeach; ?>
        </div>

        <p class="text-center">Total: <?= $game->calculate_card_deck($_SESSION['deck_player']); ?></p>
        <p id="game-state-notification" class="text-center notification notification-<?= $game->get_game_state_message_level(); ?>"><?= $game->get_game_state_message(); ?></p>
        <h3 class="text-center"><?= $game->get_game_state_message(); ?></h3>

        <form method="post" class="btngroup">
            <input type="text" name="blackjack" value="1" class="hidden" />
            <?php if ($game->has_game_ended() == true) : ?>
                <?php if ($game->get_money() > 0) : ?>
                    <input type="submit" name="tryagain" class="btngroup-btn btn-success btn-animation" value="TryAgain" />
                <?php else : ?>
                    <span>Busted! You have no money left.</span>
                <?php endif; ?>
                <input type="submit" name="reset" class="btngroup-btn btn-danger btn-animation" value="Reset" />
            <?php else : ?>
                <input type="submit" name="hit" class="btngroup-btn btn-primary btn-animation" value="Hit" />
                <input type="submit" name="stand" class="btngroup-btn btn-primary btn-animation" value="Stand" />
            <?php endif; ?>
        </form>
        <h1 class="text-center"><small>$<?= $game->get_money() ?>
                <?php if ($game->has_placed_bet()) : ?>
                    <span class="text-muted"> bet: $<?= $game->get_bet() ?></span>
                <?php endif; ?>
            </small>
        </h1>

    <?php else : ?>
        <br />
        <br />
        <form method="post" class="btngroup popup-bet">
            <input type="text" name="blackjack" value="1" class="hidden" />
            <h1>Place a bet</h1>
            <p>Max $<?= $game->get_money() ?></p>
            <input type="number" name="bet" value="<?= $game->get_previous_bet() ?>" min="1" max="<?= $game->get_money() ?>" />
            <br />
            <br />
            <input type="submit" class="btngroup-btn btn-primary btn-animation" value="Bet!" />
        </form>
    <?php endif; ?>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="js/card_perspective.js"></script>

<script type="text/javascript">
    var notification_element = document.querySelector("#game-state-notification");

    function callback() {
        notification_element.style.display = "none";
    }

    notification_element.addEventListener("webkitAnimationEnd", callback, false);
</script>