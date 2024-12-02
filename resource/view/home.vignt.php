<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row mt-3">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Pemasukan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{! $pemasukan !}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bx bx-dollar-circle bx-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Pengeluaran</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{! $pengeluaran !}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bx bx-right-top-arrow-circle bx-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Saldo</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{! $saldo !}</div>
                            </div>
                            <div class="col-auto">
                                <i class="bx bx-wallet bx-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Rumah</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['rumah maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['rumah'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['rumah sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Mingguan</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['mingguan maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['mingguan'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['mingguan sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Listrik</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['listrik maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['listrik'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['listrik sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Laundry</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['laundry maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['laundry'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['laundry sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Alfa</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['alfa maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['alfa'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['alfa sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Cadangan</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['cadangan maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['cadangan'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['cadangan sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-blue-gradient shadow py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-uppercase mb-3 text-center">Gas</div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-up-arrow-alt text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Maksimal</small>
                                <h6 class="mb-0">{! $item['gas maksimal'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-shopping-bag text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Terpakai</small>
                                <h6 class="mb-0">{! $item['gas'] !}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Sisa</small>
                                <h6 class="mb-0">{! $item['gas sisa'] !}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
