/**
 * Created by Logan on 23/05/2016.
 */
var Game = Game || {};
Game.Main = function(id, path) {
    self = this;
    this.id = id;
    this.speed = 200;
    this.result = 'Attrapez une image';

    Game.entities = {};
    Game.scene = new Phaser.Game('100%', '100%', Phaser.AUTO, this.id, {
        preload: function() {
            Game.scene.load.tilemap(Game.MapProperty.name,
                path + '/assets/tilemaps/maps/' + Game.MapProperty.file,
                null, Phaser.Tilemap.TILED_JSON);
            for(var i in Game.Entities)
                Game.scene.load.image(Game.Entities[i].name, path + '/assets/sprites/' + Game.Entities[i].file);
            for(var i in Game.MapProperty.entities)
                Game.scene.load.image(Game.MapProperty.entities[i].name, path + '/assets/tilemaps/tiles/' + Game.MapProperty.entities[i].file);
        },
        create: function() {
            //var ratio = Game.scene.world.width / Game.scene.world.height;
            //var scale = ratio < 1 ? (Game.scene.world.width / (25 * 32)) : (Game.scene.world.height / (25 * 32));
            Game.scale = Game.scene.world.width / (25 * 32);
            Game.map = new Game.Map();

            Game.collideGroup = Game.scene.add.physicsGroup(); Game.collideGroup.name = "collide";
            Game.playerGroup = Game.scene.add.physicsGroup(); Game.playerGroup.name = "player";
            Game.vehiculeGroup = Game.scene.add.physicsGroup(); Game.vehiculeGroup.name = "vehicule";

            self.speed = self.speed * Game.scale;
            Game.entities = Game.Entity.makeEntities();
            Game.modals = new Game.Modal();
            self.interact = new Game.Interact();
        },
        update: function() {
            if(Game.spriteSelected)
                self.interact.update();
        },
        render: function() {
            Game.scene.debug.text(self.result, 10, 20);
        }
    });
};
