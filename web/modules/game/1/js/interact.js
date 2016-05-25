/**
 * Created by Logan on 23/05/2016.
 */
var Game = Game || {};
Game.Interact = function(sprites) {
    this.sprites = sprites;
    this.policies = [{
        type: "distanceMin",
        entities: ["brouette", "betonniere"],
        distance: 200,
        isValid: false
    }];
};

Game.Interact.prototype.onDropEntity = function(sprite) {
    this.applyPolicies();
};

Game.Interact.prototype.applyPolicies = function() {

    for(var i in this.policies)
    {
        switch (this.policies[i].type) {
            case "distanceMin": {
                this.policies[i].isValid = (this.getDistance(
                    this.sprites[this.policies[i].entities[0]].x,
                    this.sprites[this.policies[i].entities[0]].y,
                    this.sprites[this.policies[i].entities[1]].x,
                    this.sprites[this.policies[i].entities[1]].y
                ) < this.policies[i].distance);
                break;
            }
        }
    }
};

Game.Interact.prototype.getDistance = function(ax, ay, bx, by) {
    var a = this.sprites.betonniere.x - this.sprites.brouette.x;
    var b = this.sprites.betonniere.y - this.sprites.brouette.y;
    return Math.sqrt( a*a + b*b );
};
