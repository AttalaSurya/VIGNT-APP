<?php

// middleware(['auth'])->group(function () {
route('/')->controller('Ikatta\controller\VigntIkattaController')->function('viewIndex')->method('GET');
route('/out')->controller('Ikatta\controller\VigntIkattaController')->function('viewOut')->method('GET');
route('/in')->controller('Ikatta\controller\VigntIkattaController')->function('viewIn')->method('GET');
route('/data-out')->controller('Ikatta\controller\VigntIkattaController')->function('getDataOut')->method('POST');
route('/data-in')->controller('Ikatta\controller\VigntIkattaController')->function('getDataIn')->method('POST');
route('/insert-out')->controller('Ikatta\controller\VigntIkattaController')->function('insertOut')->method('POST');
route('/insert-in')->controller('Ikatta\controller\VigntIkattaController')->function('insertIn')->method('POST');
route('/update-val-out')->controller('Ikatta\controller\VigntIkattaController')->function('updateValueOut')->method('POST');
route('/update-val-in')->controller('Ikatta\controller\VigntIkattaController')->function('updateValueIn')->method('POST');
route('/home')->controller('Ikatta\controller\VigntIkattaController')->function('viewSaldo')->method('GET');
route('/monthly')->controller('Ikatta\controller\VigntIkattaController')->function('viewMonthly')->method('GET');
route('/data-monthly')->controller('Ikatta\controller\VigntIkattaController')->function('getDataMonthly')->method('POST');
route('/out-label')->controller('Ikatta\controller\VigntIkattaController')->function('viewOutLabel')->method('GET');
route('/data-out-label')->controller('Ikatta\controller\VigntIkattaController')->function('getDataOutLabel')->method('POST');
// });
