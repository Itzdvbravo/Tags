# Tags<img src="https://raw.githubusercontent.com/Itzdvbravo/Tags/master/new.png" height="64" width="64" align="left"></img>  
[![Poggit](https://poggit.pmmp.io/shield.state/Tags)](https://poggit.pmmp.io/p/Tags)  
***I GOT THE PERMISSION SYSTEM IDEA FROM TITLEADR PLUGIN (OUTDATED/NOT IN POGGIT) SO THANKS TO IT.***  

Add custom tags to your server using this plugin!

![Form](https://raw.githubusercontent.com/Itzdvbravo/Tags/master/pics/form.png)
![Item](https://raw.githubusercontent.com/Itzdvbravo/Tags/master/pics/item.png)

### Features  
- [x] Unlimited tags.
- [x] Permissions i.e locked/unlocked tags.
- [x] Get tags from interacting to items given by plugin.

### Commands  
- [x] /tag - Opens the tag ui.  
- [x] /givetag - Give someone a specific or random tag  
***Note*** - *for /givetag* Don't provide a tag to give a random tag, or provide a tag, helpful in crates etc, gives an item you can interact.

### How to use?

- **Creating Custom Tags**<br>
For this go to *plugin_data/tags/config.yml* and then follow a pattern with the help of the pre-made plugins.  
```
  xd:
    - tag.perm.xd
    - "§f[§bXD§f]§r"
  ```
  The first one, **xd** is used to identify the tag/giving someone the tag using it *(recommended is in lower case with no symbols)*.  
  The second one, **tag.perm.xd** is the permission needed to use the tag.  
  The third one, **§f[§bXD§f]§r** is what the tag will look like in the tag ui and when given to a player.  
- **For PureChat**<br>  
`{prefix}`, it changes the prefix of the player.

### Plugins needed to use this plugin  
[PureChat](https://poggit.pmmp.io/p/purechat)  
[PurePerms](https://poggit.pmmp.io/p/pureperms)  
