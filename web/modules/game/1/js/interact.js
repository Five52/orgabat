/**
 * Created by Logan on 23/05/2016.
 */
var Game = Game || {};
Game.Interact = function(sprites) {
    this.sprites = sprites;
};

Game.Interact.prototype.update = function() {
    Game.scene.physics.arcade.collide(Game.spriteSelected, Game.map.layer);
    Game.scene.physics.arcade.collide(Game.spriteSelected, Game.collideGroup);
    Game.scene.physics.arcade.collide(
        Game.spriteSelected, Game.vehiculeGroup, this.collisionHandler, this.processHandler, this
    );

    Game.spriteSelected.body.velocity.x = Game.spriteSelected.body.velocity.y = 0;
    if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.LEFT))
        Game.spriteSelected.body.velocity.x = -self.speed;
    else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.RIGHT))
        Game.spriteSelected.body.velocity.x = self.speed;
    if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.UP))
        Game.spriteSelected.body.velocity.y = -self.speed;
    else if (Game.scene.input.keyboard.isDown(Phaser.Keyboard.DOWN))
        Game.spriteSelected.body.velocity.y = self.speed;
}

Game.Interact.selectPlayed = function(sprite) {
    Game.spriteSelected = sprite;
    Game.Entity.setMovable(sprite, true);
    Game.scene.camera.follow(sprite);
};

Game.Interact.changePlayed = function(player, vehicule) {
    //we are the player, we can use a vehicule
    if( player.parent.name == "player" ) {
        Game.playerGroup.remove(player);
        vehicule.tint = 0xffff99;
        Game.Interact.selectPlayed(vehicule);
    }
};

Game.Interact.prototype.collisionHandler = function(player, vehicule) {
    Game.Interact.changePlayed(player, vehicule);
};

Game.Interact.prototype.processHandler = function(player, vehicule) {
    return true;
};