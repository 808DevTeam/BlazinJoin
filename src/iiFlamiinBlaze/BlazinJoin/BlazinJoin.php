<?php
/**
 *  ____  _            _______ _          _____
 * |  _ \| |          |__   __| |        |  __ \
 * | |_) | | __ _ _______| |  | |__   ___| |  | | _____   __
 * |  _ <| |/ _` |_  / _ \ |  | '_ \ / _ \ |  | |/ _ \ \ / /
 * | |_) | | (_| |/ /  __/ |  | | | |  __/ |__| |  __/\ V /
 * |____/|_|\__,_/___\___|_|  |_| |_|\___|_____/ \___| \_/
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze for the PocketMine-MP Community!
 *
 * @author iiFlamiinBlaze
 * Twitter: https://twitter.com/iiFlamiinBlaze
 * GitHub: https://github.com/iiFlamiinBlaze
 * Discord: https://discord.gg/znEsFsG
 */
declare(strict_types=1);

namespace iiFlamiinBlaze\BlazinJoin;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\player\PlayerJoinEvent;

class BlazinJoin extends PluginBase implements Listener{

    const VERSION = "v1.1.3";
    const PREFIX = TextFormat::AQUA . "Join" . TextFormat::GOLD . " > ";

    public function onEnable() : void{
        $this->getLogger()->info("BlazinJoin " . self::VERSION . "by iiFlamiinBlaze enabled");
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
    }

    public function onJoin(PlayerJoinEvent $event) : void{
        $this->getServer()->getScheduler()->scheduleDelayedTask(new JoinTitleTask($this, $event->getPlayer()), 35);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if($command->getName() === "blazinjoin"){
            if(empty($args[0])){
                $sender->sendMessage(self::PREFIX . TextFormat::GRAY . "Usage: /join <info | set> <title | subtitle | message | curse> <message>");
                return false;
            }
            if(!$sender instanceof Player){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Use This Command In-Game");
                return false;
            }
            if(!$sender->hasPermission("blazinjoin.command")){
                $config = $this->getConfig();
                $message = str_replace("&", "§", $config->get("no-permission"));
                $message = str_replace("{player}", $sender->getName(), $message);
                $message = str_replace("{line}", "\n", $message);
                $sender->sendMessage($message);
                return false;
            }
            switch($args[0]){
                case "info":
                    $sender->sendMessage(TextFormat::DARK_GRAY . "-=========BlazinJoin " . self::VERSION . " =========-");
                    $sender->sendMessage(TextFormat::GREEN . "Author: iiFlamiinBlaze");
                    $sender->sendMessage(TextFormat::GREEN . "GitHub: https://github.com/iiFlamiinBlaze");
                    $sender->sendMessage(TextFormat::GREEN . "Support: https://discord.gg/znEsFsG");
                    $sender->sendMessage(TextFormat::GREEN . "Description: Allows you to customize multiple things when a player joins your server");
                    $sender->sendMessage(TextFormat::DARK_GRAY . "-===============================-");
                    break;
                case "set":
                    switch($args[1]){
                        case "title":
                            if(is_string($args[2])){
                                $config = $this->getConfig();
                                $config->set("title", $args[2]);
                                $config->save();
                                $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You Have Now Set A New Title In BlazinJoin Config");
                            }else{
                                $sender->sendMessage(self::PREFIX . TextFormat::RED . "You Have To Set The Title To A String.");
                                return false;
                            }
                            break;
                        case "subtitle":
                            if(is_string($args[2])){
                                $config = $this->getConfig();
                                $config->set("subtitle", $args[2]);
                                $config->save();
                                $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You Have Now Set A New Subtitle In BlazinJoin Config");
                            }else{
                                $sender->sendMessage(self::PREFIX . TextFormat::RED . "You Have To Set The Subtitle To A String.");
                                return false;
                            }
                            break;
                        case "message":
                            if(is_string($args[2])){
                                $config = $this->getConfig();
                                $config->set("message", $args[2]);
                                $config->save();
                                $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You Have Now Set A New Message In BlazinJoin Config");
                            }else{
                                $sender->sendMessage(self::PREFIX . TextFormat::RED . "You Have To Set The Message To A String.");
                                return false;
                            }
                            break;
                        case "curse":
                            switch($args[2]){
                                case "enabled":
                                    $config = $this->getConfig();
                                    $config->set("guardian-curse", "enabled");
                                    $config->save();
                                    $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You Have Now Set The Guardian Curse To Enabled In BlazinJoin Config");
                                    break;
                                case "disabled":
                                    $config = $this->getConfig();
                                    $config->set("guardian-curse", "enabled");
                                    $config->save();
                                    $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You Have Now Set The Guardian Curse To Disabled In BlazinJoin Config");
                                    break;
                                default:
                                    $sender->sendMessage(self::PREFIX . TextFormat::RED . "You Must Set The Curse To Enabled Or Disabled!");
                                    break;
                            }
                            break;
                    }
                    break;
            }
        }
        return true;
    }
}
