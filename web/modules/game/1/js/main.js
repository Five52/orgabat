/**
 * Created by Logan on 23/05/2016.
 */
var Game = Game || {};
Game.Main = function(id, path) {
    var self = this;
    this.id = id;
    this.speed = 200;
    this.result = 'Attrapez une image';

    this.tileHits = [];
    this.plotting = false;

    Game.sprites = {};
    Game.scene = new Phaser.Game('100%', '100%', Phaser.AUTO, this.id, {
        preload: function() {
            Game.scene.load.image('grass', path + '/textures/grass.jpg');
            Game.scene.load.image('phaser', path + '/assets/sprites/phaser-dude.png');
            Game.scene.load.tilemap('map', path + '/assets/tilemaps/maps/sans-titre.json', null, Phaser.Tilemap.TILED_JSON);
            Game.scene.load.image('wall', path + '/assets/tilemaps/tiles/Spr_wall.png');

            for(var i in Game.Entities)
                Game.scene.load.image(Game.Entities[i].name, path + '/textures/' + Game.Entities[i].name + '.png');
        },
        create: function() {
            /*Game.scene.physics.startSystem(Phaser.Physics.ARCADE);
            Game.scene.add.tileSprite(0, 0, Game.scene.world.width, Game.scene.world.height, 'grass');

            for(var i in Game.Entities)
                Game.sprites[Game.Entities[i].name] = new Game.Entity(
                    Game.Entities[i].name,
                    Game.Entities[i].x,
                    Game.Entities[i].y,
                    Game.Entities[i].rules
                ).sprite;*/

            Game.scene.scale.scaleMode = Phaser.ScaleManager.RESIZE;
            self.map = Game.scene.add.tilemap('map');
            self.map.addTilesetImage('wall');

            var ratio = Game.scene.world.width / Game.scene.world.height;
            var scale = ratio < 1 ? (Game.scene.world.width / (25 * 32)) : (Game.scene.world.height / (25 * 32));
            console.log(scale);
          //  var scaleInverted = scale < 1 ? (25 * 32) / Game.scene.world.width : scale;
            self.layer = self.map.createLayer(
                'Calque de Tile 1',
                Game.scene.world.width,
                Game.scene.world.height
            );

            self.layer.setScale(scale);
            self.layer.resizeWorld();

            self.map.setCollisionBetween(1, 1);
            //self.layer.debug = true;

            self.sprite = Game.scene.add.sprite(260, 70, 'phaser');
            Game.scene.physics.enable(self.sprite);
            Game.scene.camera.follow(self.sprite);

            self.spriteSelected = self.sprite;

            //self.interact = new Game.Interact(Game.sprites);
        },
        update: function() {
            //Game.scene.physics.arcade.collide(Game.sprites.betonniere, Game.sprites.brouette, self.collisionHandler, null, this);
            if(self.spriteSelected) {
                Game.scene.physics.arcade.collide(self.spriteSelected, self.layer);

                self.spriteSelected.body.velocity.x = self.spriteSelected.body.velocity.y = 0;
                if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.LEFT))
                    self.spriteSelected.body.velocity.x = -self.speed;
                else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.RIGHT))
                    self.spriteSelected.body.velocity.x = self.speed;
                if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.UP))
                    self.spriteSelected.body.velocity.y = -self.speed;
                else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.DOWN))
                    self.spriteSelected.body.velocity.y = self.speed;
            }
        },
        render: function() {
           // Game.scene.debug.bodyInfo(Game.sprites.brouette, 16, 24);
            Game.scene.debug.text(self.result, 10, 20);
        }
    });
};

Game.Main.prototype.collisionHandler = function(obj1, obj2) {
    //  The two sprites are colliding
};

