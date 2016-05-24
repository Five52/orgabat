/**
 * Created by Logan on 23/05/2016.
 */
var Game = function(id, spritesPath) {
    var self = this;
    this.id = id;
    this.sprites = {};
    this.result = 'Attrapez une image';
    this.game = new Phaser.Game(window.innerWidth, window.innerHeight, Phaser.AUTO, this.id, {
        preload: function() {
            self.game.load.image('terrain', spritesPath + '/terrain.jpg');
            self.game.load.image('betonniere', spritesPath + '/betonniere.png');
            self.game.load.image('brouette', spritesPath + '/brouette.jpg');
        },
        create: function() {
            self.game.add.image(self.game.world.centerX, self.game.world.centerY, 'terrain').anchor.set(0.5);
            self.sprites.betonniere = self.addDraggableItem(620, 464, 'betonniere');
            self.sprites.brouette = self.addDraggableItem(150, 464, 'brouette');
            self.interact = new Interact(self.sprites);
        },
        render: function() {
            self.game.debug.text(self.result, 10, 20);
        }
    });
};

Game.prototype.addDraggableItem = function(x, y, name) {
    var sprite = this.game.add.sprite(x, y, name);
    sprite.anchor.set(0.5);
    sprite.inputEnabled = true;
    sprite.input.enableDrag();
    sprite.events.onDragStart.add(this.onDragStart, this);
    sprite.events.onDragStop.add(this.onDragStop, this);
    return sprite;
};

Game.prototype.onDragStart = function(sprite, pointer) {
    this.game.canvas.style.cursor = "move";
    this.game.world.bringToTop(sprite);
    this.result = "Dragging " + sprite.key;
};

Game.prototype.onDragStop = function(sprite, pointer) {
    this.game.canvas.style.cursor = "default";
    this.result = "Distance: " + this.interact.onDropEntity(sprite);
};
