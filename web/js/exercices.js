(function() {
  document.querySelector('#return').onclick = function() {
    window.parent.closeExerciseModal();
  };

  var games = document.querySelectorAll('.game');

  function initGames() {
    for (var g = games.length - 1; g >= 0; g--) {
      games[g].onclick = null;

      if (!games[g].classList.contains('disabled')) {
        games[g].onclick = clickHandler;
      }
    }
  }
  initGames();

  function clickHandler() {
    window.parent.loadGame(this.dataset.gameId, this.querySelector('#game_label').textContent);
  }

})();
