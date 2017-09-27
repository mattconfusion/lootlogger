```
 __,  ____, ____, ____,  __,  ____, ____, ____, ____,____, 
(-|  (-/  \(-/  \(-|    (-|  (-/  \(-/ _,(-/ _,(-|_,(-|__) 
 _|__,_\__/,_\__/,_|,    _|__,_\__/,_\__| _\__| _|__,_|  \,
(    (     (     (      (    (   A loot log parser, really.
```
# Loot Logger

 Write in a csv file (or in your favourite spreadsheet) all the events occuring in a session
 which require tracking for later accounting (xp gained, spells spent, gold pieces earned). Follow this example:

```
 Teniviel,xp,+15,found secret door
 Muradin,gp,+10,found valuable necklace on the floor
 party,xp,+60,fought goblins
 Aldestro+Bayern+Everard+Ramone,gp,+40,found hidden stash
 ```
 The CSV is written following these cases:

 **PC,stat,value,comment**: take PC,modify stat according to value, because comment

 **PARTY-KEYWORD,stat,value,comment**: for the whole party modify stat according to value. This will be divided to each specified PC at the end of parsing

 **PC+PC+PC,stat,value,comment**: take all the PCs, modify stat according to value/number of PCs

 Run **LootLogger** from an ANSI console:
 ```
 $ php lootlogger.php path/to/csv/file -o table
 ```
Results will be displayed in an ASCII table directly in the console.

Available params:

**-p --party-keyword**: change the party keyowrd, default is **party**
**-o --output**: output format, either *table* or *list* (default)

## TODO

[X] Add comments to the table
[√] Add colors to the cli!
[X] Add tests
 