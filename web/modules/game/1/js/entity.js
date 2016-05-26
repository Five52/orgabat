/**
 * Created by Logan on 25/05/2016.
 */
var Game = Game || {};
Game.Entities = [
    {
        name: "player", file: "player.png", x: 64, y: 224, playable: true, collision: true, type: "player"
    },
    {
        name: "elevateur", file: "elevateur.png", x: 32, y: 16, playable: true, collision: true, type: "vehicule"
    },
    {
        name: "brouette", file: "brouette.png", x: 176, y: 48, playable: true, collision: true, type: "vehicule"
    },
    {
        name: "transpalette", file: "transpalette.png", x: 256, y: 48, playable: true, collision: true, type: "vehicule"
    },

    //TOP RIGHT
    {
        name: "parpaings", file: "parpaings.png", x: 368, y: 48, collision: true
    },
    {
        name: "liants", file: "Liants.png", x: 448, y: 48, collision: true
    },

    //Bottom RIGHT
    {
        name: "sable", file: "Sable.png", x: 224, y: 336, collision: true
    },
    {
        name: "gravier", file: "Gravier.png", x: 302, y: 336, collision: true
    },
    {
        name: "betonniere", file: "Betonniere.png", x: 384, y: 336, collision: true
    },
    {
        name: "mortier", file: "Mortier.png", x: 480, y: 336, collision: true
    }
];

Game.Entity = function(name, x, y) {
    this.sprite = Game.scene.add.sprite(x * Game.scale, y * Game.scale , name);
    this.sprite.scale.setTo(Game.scale);
    Game.scene.physics.enable(this.sprite);
    Game.Entity.setMovable(this.sprite, false);
};

//static function
Game.Entity.setMovable = function(sprite, bool) {
    sprite.body.moves = bool;
};

Game.Entity.makeEntities = function () {
    //ENTITIES
    var entities = {};
    for(var i in Game.Entities) {
        entities[Game.Entities[i].name] = new Game.Entity(
            Game.Entities[i].name,
            Game.Entities[i].x,
            Game.Entities[i].y
        );

        //Add collidable objects to collideGroup
        if(Game.Entities[i].collision)
            Game.collideGroup.add(entities[Game.Entities[i].name].sprite);

        //Add vehicule object to vehiculeGroup
        if(Game.Entities[i].playable && Game.Entities[i].type == "vehicule")
            Game.vehiculeGroup.add(entities[Game.Entities[i].name].sprite);

        //Set player
        if(Game.Entities[i].playable && Game.Entities[i].type == "player") {
            Game.playerGroup.add(entities[Game.Entities[i].name].sprite);
            Game.Interact.selectPlayed(entities[Game.Entities[i].name].sprite);
        }
    }
    return entities;
};