var api = (function() {
  /**
   * @param {String} param.url
   * @param {String} param.method
   * @param {Object} param.data
   * @param {String} param.contentType
   * @param {Function} callback
   */
  var requestAsync = function(param, callback) {
    var options = {
      method: 'GET'
    };
    if (typeof param === 'string') {
      options.url = param;
    } else {
      options.url = param.url ? param.url : '';
      options.method = param.method ? param.method : 'GET';
      options.data = param.data ? param.data : undefined;
      options.contentType = param.contentType ? param.contentType : undefined;
    }

    var req = new XMLHttpRequest();
    req.onload = function() {
      callback(this.status, this.responseText);
    };
    req.onerror = function() {
      callback(this.status);
    };

    req.open(options.method, options.url, true);
    if (options.contentType) {
      req.setRequestHeader('Content-Type', options.contentType);
    }
    req.send(options.data);

    return req;
  };

  /**
   * @param {Object} data
   * @param {Number} data.exerciseId
   * @param {Number} data.time
   * @param {Number} data.health
   * @param {Number} data.organization
   * @param {Number} data.business
   * @param {Function} callback
   * @return {XMLHttpRequest} The request
   */
  var sendScore = function(data, callback) {
    data = data || {};
    console.log(data);
    if (typeof data.exerciseId !== 'number' ||
      typeof data.time !== 'number' ||
      typeof data.health !== 'number' ||
      typeof data.organization !== 'number' ||
      typeof data.business !== 'number') {
      return null;
    }

    return requestAsync({
      url: '/api/score',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        data: data
      })
    }, function(status, res) {
      var posted = status === 202;
      
      if (posted) {
        window.parent.reloadCategoryModal();
      }

      if (callback) {
        callback(posted, res);
      }
    });
  };

  return {
    sendScore: sendScore
  };
})();
