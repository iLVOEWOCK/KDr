<?php

namespace wockgod\command;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wockgod\KDR;

class StatsCommand extends Command {

    public function __construct(){
        parent::__construct("stats", "View another players K/D statistics", "/stats <player>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can only be used in-game.");
            return;
        }
        if (count($args) < 1) {
            $sender->sendMessage("§cUsage: /stats <player>");
            return;
        }

        $playerName = $args[0];
        $playerStats = KDR::getInstance()->getConfig()->getNested("players.$playerName");

        if ($playerStats === null) {
            $sender->sendMessage("§cPlayer not found.");
            return;
        }

        $kills = $playerStats["kills"];
        $deaths = $playerStats["deaths"];
        $kdr = $deaths > 0 ? round($kills / $deaths, 2) : $kills;

        if (KDR::getInstance()->getConfig()->get("form-ui")) {
            $form = new SimpleForm(function (Player $player, ?int $data) use ($playerName, $kills, $deaths, $kdr) {
                if ($data === null) {
                    return;
                }

               /* $message = KDR::getInstance()->getConfig()->getNested("messages.stats", "{player}'s Statistics: KDR: {kdr} (Kills: {kills}, Deaths: {deaths}).");
                $message = str_replace("{player}", $playerName, $message);
                $message = str_replace("{kdr}", $kdr, $message);
                $message = str_replace("{kills}", $kills, $message);
                $message = str_replace("{deaths}", $deaths, $message);
                $player->sendMessage($message);*/
            });

            $form->setTitle(KDR::getInstance()->getConfig()->getNested("ui.stats.title", "Stats"));
            $form->setContent(KDR::getInstance()->getConfig()->getNested("ui.stats.content", "{player}'s Statistics:\nKDR: {kdr}\nKills: {kills}\nDeaths: {deaths}"));
            $form->addButton(KDR::getInstance()->getConfig()->getNested("ui.stats.close-button", "Close"));

            $form->setContent(str_replace("{player}", $playerName, $form->getContent()));
            $form->setContent(str_replace("{kdr}", $kdr, $form->getContent()));
            $form->setContent(str_replace("{kills}", $kills, $form->getContent()));
            $form->setContent(str_replace("{deaths}", $deaths, $form->getContent()));

            $sender->sendForm($form);
        } else {
            $message = KDR::getInstance()->getConfig()->getNested("messages.stats", "{player}'s Statistics: KDR: {kdr} (Kills: {kills}, Deaths: {deaths}).");
            $message = str_replace("{player}", $playerName, $message);
            $message = str_replace("{kdr}", $kdr, $message);
            $message = str_replace("{kills}", $kills, $message);
            $message = str_replace("{deaths}", $deaths, $message);
            $sender->sendMessage($message);
        }
    }
}
