<?php
session_start();
session_destroy();
?>

<div class="background background-main"></div>

<div class="content">
    <h1 class="text-center title-big">READY TO PLAY</h1>
    <h1 id="title-blackjack" class="text-center font-casino title-big"></h1>
    <button id="btn-blackjack-play" class="btn btn-center btn-play btn-animation" onclick="play();">HECK YEAH!</button>
    <br />
    <p class="text-center text-muted text-depth">By playing you state that you are at least 18 years of age and that gambling is legal in your country.</p>
    <p class="text-center text-muted text-depth">While <span class="web-url"></span> is free, gambling can become addictive. Play responsible!</p>
    <?php if (isset($_GET["bj"])) : ?>
        <a class="text-center text-depth" href="https://www.youtube.com/watch?v=Jqa1Ugmv9yY">More info on online gambling</a>
    <?php else : ?>
        <a class="text-center text-depth" href="https://en.wikipedia.org/wiki/Online_gambling">More info on online gambling</a>
    <?php endif ?>
</div>

<script>
    function play() {
        var f = document.createElement("form");
        f.action = window.location.href;
        f.method = "POST";
        f.target = "_self";

        var i = document.createElement("input");
        i.type = "hidden";
        i.name = "blackjack";
        i.value = "1";
        f.appendChild(i);

        document.body.appendChild(f);
        f.submit();
    }
</script>

<script type="text/javascript">
    let title_text_blackjack = "BLACKJACK";
    let title_text_progress = 0;
    let title_text_delay = 150;
    let title_effect_name = "titleColorFade";

    <?php if (isset($_GET["bj"])) : ?>
        // Because why not?
        // BONK
        title_text_blackjack = "BJ😏";
        title_text_delay = 0;
        title_effect_name = "titleColorRainbow";
        document.getElementById("btn-blackjack-play").innerText = "Y.. Yes D.. Daddy OwO";
    <?php endif ?>

    let title_interval = setInterval(() => {
        title_text_progress++;
        document.getElementById("title-blackjack").innerText = title_text_blackjack.substring(0, title_text_progress);
        if (title_text_progress == title_text_blackjack.length) {
            clearInterval(title_interval);
            document.getElementById("title-blackjack").classList.add(title_effect_name);
            document.getElementById("title-blackjack").classList.add("titleColorFlash");
            document.getElementById("btn-blackjack-play").classList.add("btn-play-show");
            setTimeout(() => {
                document.getElementById("title-blackjack").classList.remove("titleColorFlash");
            }, 600);
        }
    }, title_text_delay);

    document.querySelectorAll(".web-url").forEach((element) => {
        element.innerText = window.location.host;
    });
</script>