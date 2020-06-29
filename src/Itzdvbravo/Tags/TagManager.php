<?php

namespace Itzdvbravo\Tags;

use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use jojoe77777\FormAPI\SimpleForm;

class TagManager extends PluginBase{
    public static $config;
    public $api;
    public $purechat;
    public $pureperm;

    public function onEnable(){
        if (!file_exists($this->getDataFolder()."config.yml")) {
            $this->saveResource("config.yml");
        }
        self::$config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->purechat = $this->getServer()->getPluginManager()->getPlugin("PureChat");
        if ($this->purechat === Null) {
            $this->getLogger()->critical("PureChat plugin not found");
            $this->getLogger()->critical("Disabling the plugin");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

        $this->pureperm = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        if ($this->pureperm === Null) {
            $this->getLogger()->critical("PurePerms plugin not found");
            $this->getLogger()->critical("Disabling the plugin");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }
    /**
     * @param Player $player
     * @param $tag
     * @param Item $item
     */
    public function giveTag(Player $player, $tag, Item $item){
        $cfg = self::$config->get($tag);
        $tagPerm = $cfg[0];
        if ($player->hasPermission($tagPerm)){
            $player->sendMessage(TextFormat::RED."You Already Have§r {$cfg[1]}§4 Tag");
        } else {
            $this->pureperm->getUserDataMgr()->setPermission($player, $tagPerm, null);
            $player->sendMessage("§eYou have been given §r{$cfg[1]} \n§aUse /tag to equip it");
            $player->getInventory()->remove($item);
        }
    }

    /**
     * @param Player $player
     * @param $tag
     */
    public function addTag(Player $player, $tag){
        $cfg = self::$config->get($tag);
        $item = Item::get(Item::CLOCK, 0, 1);
        $item->setCustomName(TextFormat::RESET."{$cfg[1]}".TextFormat::YELLOW." Tag");
        $item->setLore([TextFormat::GREEN."Right Click To Obtain It"]);
        $nbt = $item->getNamedTag();
        $nbt->setString("tag", $tag);
        $item->setNamedTag($nbt);
        $player->getInventory()->addItem($item);
        $player->sendMessage(TextFormat::GREEN."You have gotten {$cfg[1]} ".TextFormat::GREEN."tag");
    }

    /**
     * @param Player $player
     */
    public function addRandomTag(Player $player){
        $tag = array_rand(self::$config->getAll());
        $this->addTag($player, $tag);
    }

    /**
     *@param CommandSender $player
     *@param Command $cmd
     *@param string $label
     *@param array $args
     *@return bool
     */
    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args): bool{
        switch ($cmd->getName()){
            case "tag":
                if ($player->hasPermission("tag.use")) {
                    if ($player instanceof Player) {
                        $this->openForm($player);
                    } else {
                        $player->sendMessage(TextFormat::RED . "Use this in game");
                    }
                } else {
                    $player->sendMessage(TextFormat::RED."Insufficient permission");
                }
                break;
            case "givetag":
                if ($player->hasPermission("tags.give")){
                    if (!empty($args[0])) {
                        $person = Server::getInstance()->getPlayer($args[0]);
                        if ($person !== Null) {
                            if (empty($args[1])) {
                                $this->addRandomTag($person);
                                $person->sendMessage(TextFormat::GREEN . "You have gotten an random tag");
                            } else {
                                if (array_key_exists(strtolower($args[1]), self::$config->getAll())) {
                                    $this->addTag($person, $args[1]);
                                } else {
                                    $player->sendMessage(TextFormat::GOLD . "Tag doesn't exist");
                                }
                            }
                        } else {
                            $player->sendMessage(TextFormat::RED . "Player not found");
                        }
                    } else {
                        $player->sendMessage(TextFormat::RED."Provide a player");
                    }
                } else {
                    $player->sendMessage(TextFormat::RED."Insufficient permission");
                }
                break;
        }
        return true;
    }

    /**
     *@param Player $player
     *@return mixed
     */
    public function openForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data = NULL){
            if($data !== NULL) {
                $cfg = array_values(self::$config->getAll())[$data];
                $permCheck = $cfg[0];
                $tag = $cfg[1];
                $realTag = " {$tag} ";
                if ($player->hasPermission($permCheck)){
                    $this->purechat->setPrefix($realTag, $player);
                    $player->sendMessage("§aTag Changed To§r {$cfg[1]}");
                } else{
                    $player->sendMessage("§4You don't have permission to use this tag");
                }
            }
        });
        $form->setTitle("§aTags");
        $form->setContent("§eChoose Your Tag");
        $cfg = self::$config->getAll();
        $lock = TextFormat::RED . '§l§cLOCKED';
        $avaible = TextFormat::GREEN . '§l§aAVAILABLE';
        foreach ($cfg as $id => $tag){
            if ($player->hasPermission($tag[0])){
                $form->addButton("{$tag[1]}"."\n"."{$avaible}");
            } else {
                $form->addButton("{$tag[1]}"."\n"."{$lock}");
            }
        }
        $form->sendToPlayer($player);
        return $form;
    }
}
