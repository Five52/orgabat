(function() {
  var rubriques = document.querySelectorAll('.rubrique');

  for (var r = rubriques.length - 1; r >= 0; r--) {
    rubriques[r].onclick = clickHandler;
  }

  var iframe = document.querySelector('#gameModal iframe');

  $('#gameModal').on('hidden.bs.modal', function(e) {
    iframe.src = '';
  })

  function clickHandler(e) {
    e.stopPropagation();
    e.preventDefault();

    $('#gameModal').modal({
      keyboard: false
    });

    iframe.src = '/game/' + this.dataset.gameId;
  }

})();
