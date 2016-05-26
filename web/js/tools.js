(function(window) {
  /**
   * @param param.url
   * @param param.method
   * @param param.data
   * @param param.contentType
   * @param param.onProgress
   */
  window.requestAsync = function(param, callback) {
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
    if (typeof param.onProgress === 'function') {
      req.upload.onprogress = param.onProgress;
      req.onprogress = param.onProgress;
    }
    if (options.contentType) {
      req.setRequestHeader('Content-Type', options.contentType);
    }
    req.send(options.data);
    req.started_at = Date.now();

    return req;
  };
})(window);
