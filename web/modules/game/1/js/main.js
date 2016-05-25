/**
 * Created by Logan on 23/05/2016.
 */
var Game = Game || {};
Game.Main = function(id, spritesPath) {
    var self = this;
    this.id = id;
    
    this.speed = 100;
    this.result = 'Attrapez une image';
    Game.sprites = {};
    Game.scene = new Phaser.Game(window.innerWidth, window.innerHeight, Phaser.AUTO, this.id, {
        preload: function() {
            Game.scene.load.image('terrain', spritesPath + '/terrain.jpg');
            for(var i in Game.Entities)
                Game.scene.load.image(Game.Entities[i].name, spritesPath + '/' + Game.Entities[i].name + '.png');
        },
        create: function() {
            Game.scene.physics.startSystem(Phaser.Physics.ARCADE);
            Game.scene.add.image(Game.scene.world.centerX, Game.scene.world.centerY, 'terrain').anchor.set(0.5);

            for(var i in Game.Entities)
                Game.sprites[Game.Entities[i].name] = new Game.Entity(
                    Game.Entities[i].name,
                    Game.Entities[i].x,
                    Game.Entities[i].y,
                    Game.Entities[i].rules
                ).sprite;

            //self.interact = new Game.Interact(Game.sprites);
        },
        update: function() {
            //Game.scene.physics.arcade.collide(Game.sprites.betonniere, Game.sprites.brouette, self.collisionHandler, null, this);
            /*if(self.spriteSelected) {
                self.spriteSelected.body.velocity.x = self.spriteSelected.body.velocity.y = 0;
                if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.LEFT))
                    self.spriteSelected.body.velocity.x = -self.speed;
                else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.RIGHT))
                    self.spriteSelected.body.velocity.x = self.speed;
                else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.UP))
                    self.spriteSelected.body.velocity.y = -self.speed;
                else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.DOWN))
                    self.spriteSelected.body.velocity.y = self.speed;
            }*/
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

