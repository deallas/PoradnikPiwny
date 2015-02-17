function PPObserver() {
    var _observers = [];

    this.addObserver = function(observer) {
        _observers.push(observer);
    }

    this.notifyObservers = function() {
        for (var i=0; i<_observers.length; i++) {
            _observers[i]();
        }  
    }
}