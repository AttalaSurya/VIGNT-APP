<?php

route('/')->controller('Ikatta\controller\VigntIkattaController')->function('index')->method('GET');
route('/data/{id}')->controller('Ikatta\controller\VigntIkattaController')->function('data')->method('GET');
route('/example')->controller('Ikatta\controller\VigntIkattaController')->function('example')->method('GET');

routePrefix('xxx', function() {
    route('/')->controller('Ikatta\controller\VigntIkattaController')->function('index')->method('GET');
    route('/example')->controller('Ikatta\controller\VigntIkattaController')->function('example')->method('GET');
});

middleware(['auth'])->group(function () {
    routePrefix('xxx', function() {
        route('/')->controller('Ikatta\controller\VigntIkattaController')->function('index')->method('GET');
        route('/example')->controller('Ikatta\controller\VigntIkattaController')->function('example')->method('GET');
    });
});

route('/data/<id>/power/<power>')->controller('Ikatta\controller\VigntIkattaController')->function('data')->method('GET');
route('/vignt-table')->controller('Ikatta\controller\VigntIkattaController')->function('vigntTable')->method('GET');
route('/vigntz')->controller('Ikatta\controller\VigntIkattaController')->function('viewvignt')->method('GET');
route('/out')->controller('Ikatta\controller\VigntIkattaController')->function('viewOut')->method('GET');
route('/data')->controller('Ikatta\controller\VigntIkattaController')->function('getData')->method('POST');

?>