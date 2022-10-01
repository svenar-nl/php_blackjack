<?php
include("blackjack.php");

$image_path = "/images/cards/" . (isset($_GET["bj"]) ? "alt" : "normal") . "/";

$game = new Blackjack();
$game->setup();
?>

<div class="background background-game"></div>

<div class="content">
    <h1 class="text-center">CPU <?php echo $game->get_cpu_score(); ?> | Player <?php echo $game->get_player_score(); ?></h1>

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
    <h3 class="text-center"><?= $game->get_game_state_message(); ?></h3>

    <form method="post" class="btngroup">
        <input type="text" name="blackjack" value="1" class="hidden" />
        <?php if ($game->has_game_ended() == true) : ?>
            <input type="submit" name="tryagain" class="btngroup-btn btn-success" value="TryAgain" />
            <input type="submit" name="reset" class="btngroup-btn btn-danger" value="Reset" />
        <?php else : ?>
            <input type="submit" name="hit" class="btngroup-btn btn-primary" value="Hit" />
            <input type="submit" name="stand" class="btngroup-btn btn-primary" value="Stand" />
        <?php endif; ?>
    </form>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="js/card_perspective.js"></script>