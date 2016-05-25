var api = (function() {

  function sendScore(data) {

    // TODO: Reformater des données
    // data = {
    //   score: 0
    // };

    requestAsync({
      url: '/api/score/',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        data: data
      })
    }, function(status, res) {
      switch (status) {
        case 202:
          // Score envoyé
          var json = JSON.parse(res);

          // TODO: Parsing des données
          // TODO: Mettre à jour l'affichage coté client : jauge, griser le jeu, note

          break;
        default:
          console.error(res);
      }
      console.log(status, res);
    });
  }


  return {
    sendScore: sendScore
  };
})();
