<?php

namespace wockgod\command;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use wockgod\KDR;

class KDRCommand extends Command {

    public function __construct(){
        parent::__construct("kdr", "View your K/D statistics", "/kdr");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can only be used in-game.");
            return;
        }

        $playerName = $sender->getName();
        $kills = KDR::getInstance()->getConfig()->getNested("players.$playerName.kills", 0);
        $deaths = KDR::getInstance()->getConfig()->getNested("players.$playerName.deaths", 0);
        $kdr = $deaths > 0 ? round($kills / $deaths, 2) : $kills;

        if (KDR::getInstance()->getConfig()->get("form-ui")) {
            $form = new SimpleForm(function (Player $player, ?int $data) use ($kills, $deaths, $kdr) {
                if ($data === null) {
                    return;
                }

                $message = KDR::getInstance()->getConfig()->getNested("messages.kdr", "§aYour kill/death ratio is {kdr} (Kills: {kills}, Deaths: {deaths}).");
                $message = str_replace("{kdr}", $kdr, $message);
                $message = str_replace("{kills}", $kills, $message);
                $message = str_replace("{deaths}", $deaths, $message);
                $player->sendMessage($message);
            });

            $form->setTitle(KDR::getInstance()->getConfig()->getNested("ui.kdr.title", "KDR"));
            $form->setContent(KDR::getInstance()->getConfig()->getNested("ui.kdr.content", "§6Your kill/death ratio is {kdr} (Kills: {kills}, Deaths: {deaths})."));
            $form->addButton(KDR::getInstance()->getConfig()->getNested("ui.kdr.close-button", "Close"));

            $form->setContent(str_replace("{player}", $playerName, $form->getContent()));
            $form->setContent(str_replace("{kdr}", $kdr, $form->getContent()));
            $form->setContent(str_replace("{kills}", $kills, $form->getContent()));
            $form->setContent(str_replace("{deaths}", $deaths, $form->getContent()));

            $sender->sendForm($form);
        } else {
            $message = KDR::getInstance()->getConfig()->getNested("messages.kdr", "§aYour kill/death ratio is {kdr} (Kills: {kills}, Deaths: {deaths}).");
            $message = str_replace("{kdr}", $kdr, $message);
            $message = str_replace("{kills}", $kills, $message);
            $message = str_replace("{deaths}", $deaths, $message);
            $sender->sendMessage($message);
        }
    }
}
