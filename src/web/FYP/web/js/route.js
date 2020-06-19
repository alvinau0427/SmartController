route('/', 'home', function() {});
route('/page1', 'template1', function() {});
route('/page2', 'template2', function() {});
route('*', 'error404', function() {});
