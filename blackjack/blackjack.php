<?php
session_start();

class Blackjack {
    private $cards = array("2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10, "J" => 10, "Q" => 10, "K" => 10, "A" => 11);
    private $types = array("C", "D", "H", "S");
    private $gameEnded = false;
    private $gameStateMessageLevel = "";
    private $gameStateMessage = "";
    private $winMultiplier = 2.0;

    function setup() {
        if ($_POST["tryagain"]) {
            unset($_SESSION["used_cards"]);
            unset($_SESSION["bet"]);
            unset($_POST["tryagain"]);
        }

        if (isset($_POST["bet"])) {
            $_SESSION["bet"] = intval($_POST["bet"]);
            unset($_POST["bet"]);
        }

        if ($_POST["reset"]) {
            session_destroy();
            session_start();
        }

        if (!isset($_SESSION["score_cpu"])) {
            $_SESSION["score_cpu"] = 0;
        }
        if (!isset($_SESSION["score_plr"])) {
            $_SESSION["score_plr"] = 0;
        }

        if (!isset($_SESSION["money"])) {
            $_SESSION["money"] = 1000;
        }

        if (!isset($_SESSION["used_cards"])) {
            $_SESSION["used_cards"] = array();

            $deck_player = array();
            $deck_computer = array();

            array_push($deck_player, $this->deck_pull_card());
            array_push($deck_player, $this->deck_pull_card());
            array_push($deck_computer, $this->deck_pull_card());
            array_push($deck_computer, $this->deck_pull_card());

            $_SESSION["deck_player"] = $deck_player;
            $_SESSION["deck_computer"] = $deck_computer;
        }

        if ($_POST["hit"] && $this->gameEnded != true) {
            array_push($_SESSION["deck_player"], $this->deck_pull_card());

        } elseif ($_POST["stand"] && $this->gameEnded != true) {
            while ($this->calculate_card_deck($_SESSION["deck_player"]) > $this->calculate_card_deck($_SESSION["deck_computer"])) {
                array_push($_SESSION["deck_computer"], $this->deck_pull_card());

                $this->check_winner($_SESSION["deck_player"], $_SESSION["deck_computer"]);
            }

            if ($this->calculate_card_deck($_SESSION["deck_player"]) == $this->calculate_card_deck($_SESSION["deck_computer"])) {
                $this->gameStateMessageLevel = "info";
                $this->gameStateMessage = "Tie game";
                $this->save_score(false, false);
                $this->gameEnded = true;
            }

            if (($this->calculate_card_deck($_SESSION["deck_computer"]) > $this->calculate_card_deck($_SESSION["deck_player"])) && $this->gameEnded != true) {
                $this->gameStateMessageLevel = "warning";
                $this->gameStateMessage = "Dealer won!";
                $_SESSION["money"] -= $_SESSION["bet"];
                $this->save_score(true, false);
                $this->gameEnded = true;
            }
        }

        $this->check_winner($_SESSION["deck_player"], $_SESSION["deck_computer"]);
    }

    function card_value($card) {
        return $this->cards[$card];
    }

    function save_score($cpuWon, $playerWon) {
        if ($cpuWon) {
            $_SESSION["score_cpu"] = $_SESSION["score_cpu"] + 1;
        }

        if ($playerWon) {
            $_SESSION["score_plr"] = $_SESSION["score_plr"] + 1;
        }
    }

    function reset() {
        $_SESSION["score_cpu"] = 0;
        $_SESSION["score_plr"] = 0;
    }

    function get_cpu_score() {
        return $_SESSION["score_cpu"];
    }

    function get_player_score() {
        return $_SESSION["score_plr"];
    }

    function deck_pull_card() {
        $tmp["card"] = array_rand($this->cards);
        $tmp["type"] = $this->types[array_rand($this->types)];

        while (in_array($tmp["card"] . $tmp["type"], $_SESSION["used_cards"])) {
            $tmp["card"] = array_rand($this->cards);
            $tmp["type"] = $this->types[array_rand($this->types)];
        }

        $_SESSION["used_cards"][] = $tmp["card"] . $tmp["type"];

        return $tmp;
    }

    function calculate_card_deck($deck) {
        $count = 0;
        $aces = 0;

        foreach ($deck as $card) {
            if ($card["card"] != "A") {
                $count += $this->cards[$card["card"]];
            } else {
                $aces++;
            }
        }

        for ($index = 0; $index < $aces; $index++) {
            if ($count + 11 > 21) {
                $count += 1;
            } else {
                $count += 11;
            }
        }

        return $count;
    }

    function check_winner($player, $computer) {
        if ($this->gameEnded != true) {
            if ($this->calculate_card_deck($player) == 21 && $this->calculate_card_deck($computer) == 21) {
                $this->gameStateMessageLevel = "info";
                $this->gameStateMessage = "Tie game";
                $this->save_score(false, false);
                $this->gameEnded = true;

            } elseif ($this->calculate_card_deck($player) > 21 || $this->calculate_card_deck($computer) == 21) {
                $this->gameStateMessageLevel = "warning";
                $this->gameStateMessage = "Dealer won!";
                $_SESSION["money"] -= $_SESSION["bet"];
                $this->save_score(true, false);
                $this->gameEnded = true;

            } elseif ($this->calculate_card_deck($computer) > 21 || $this->calculate_card_deck($player) == 21) {
                $this->gameStateMessageLevel = "success";
                $this->gameStateMessage = "You won!";
                $_SESSION["money"] += $_SESSION["bet"] * $this->winMultiplier;
                $this->save_score(false, true);
                $this->gameEnded = true;

            } else {
                return false;
            }
        }
    }

    function has_game_ended() {
        return $this->gameEnded;
    }

    function get_game_state_message_level() {
        return $this->gameStateMessageLevel;
    }

    function get_game_state_message() {
        return $this->gameStateMessage;
    }

    function get_money() {
        return $_SESSION["money"];
    }

    function has_placed_bet() {
        return isset($_SESSION["bet"]);
    }

    function get_bet() {
        return $_SESSION["bet"];
    }
}
