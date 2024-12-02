<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card bg-blue-gradient p-1 mb-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">Total Pemasukan</h6>
            </div>
            <div class="card-body">
                <h5 class="m-0 text-white">{! $total_pemasukan !}</h6>
            </div>
        </div>
        <div class="card">
            <div class="d-flex justify-content-between">
                <!-- <h6 class="card-header fw-light">Total Pemasukan : 
                    <div style="margin-top:5px" id="total_pemasukan"> 
                        {! $total_pemasukan !}
                    </div>
                </h6> -->
                <button class="btn btn-secondary btn-sm m-4 create-new align-self-center" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">
                    <i class="bx bx-plus bx-sm me-sm-2"></i>Add New
                </button>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-hover" id="main-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Keterangan</th>
                            <th>Pemasukan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasEndLabel" class="offcanvas-title">Tambah Pemasukan</h5>
            <button type="button" class="btn-close text-reset" id="close_in" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                <label class="form-label" for="date">Tanggal</label>
                <input type="date" id="date" name="date" placeholder="mm/dd/yyyy" class="form-control" />
            </div>

            <div class="mb-4">
                <label class="form-label" for="note">Keterangan</label>
                <input type="note" id="note" name="note" class="form-control" placeholder="Keterangan pemasukan" />
            </div>

            <div class="mb-4">
                <label class="form-label" for="value">Pemasukan</label>
                <div class="input-group input-group-merge">
                    <span class="input-group-text">Rp</span>
                    <input type="number" id="value" name="value" class="form-control" placeholder="100"
                        aria-label="Amount (to the nearest dollar)" />
                    <span class="input-group-text">.00</span>
                </div>
            </div>

            <div class="mb-6">
                <button type="button" onclick="addIn()" class="btn btn-secondary">Add</button>
                <button type="button" class="btn btn-primary mx-2" data-bs-dismiss="offcanvas">Discard</button>
            </div>
        </div>
    </div>
    <div class="content-backdrop fade"></div>
</div>
<script>
    let allData = [];
    let currentPage = 1;
    const rowsPerPage = 8;

    const table = new VigntTable('main-table', {
        url: 'data-in',
        method: 'POST',
        rowsPerPage: 8,
        sortable: ['date'],
        searchable: false,    
        showNumbering: true,
        numberingColumnIndex: 0,
        processing: true,
        columns: [
            {
                name: 'note',
                data: 'note'
            },
            {
                name: 'value',
                data: 'value',
                render: (value) => formatCurrency(value)
            },
            {
                name: 'date',
                data: 'date',
                render: (value) => formatDate(value, 'd/m/Y')
            },
        ]
    });

    table.fetchData();

    function addIn() {
        vigntpop()
        .text('Apakah Anda yakin ingin menambahkan data?')
        .confirmation(function() {
            insertIn();
        })
        .cancel(function() {
            console.log('Cancelled!');
        });
    }

    function insertIn() {
        vigntLoadingStart();
        vigntajax({
            url: "insert-in",
            method: "POST",
            data: {
                date: vignt('#date').get(),
                note: vignt('#note').get(),
                value: vignt('#value').get(),
            },
            success: function(response) {
                if (response.success == 1) {
                    table.fetchData();
                    
                    vigntajax({
                        url: "update-val-in",
                        method: "POST",
                        success: function(response) {
                            if (response.success == 1) {
                                vignt('#total_pemasukan').set(response.data);
                            }
                            if (response.success != 1) {
                                vigntLoadingClose();
                                vigntnotif().error(response.message).timein(3000);
                            }
                        },
                        error: function(error) {
                            vigntLoadingClose();
                            console.error("Terjadi kesalahan saat update value:", error);
                        }
                    });

                    vigntLoadingClose();
                    vignt('#close_in').click();
                    vigntnotif().success(response.message).timein(3000);
                } else if (response.success != 1) {
                    vigntLoadingClose();
                    vigntnotif().error(response.message).timein(3000);
                }
            },
            error: function(error) {
                console.error("Terjadi kesalahan saat menyimpan data:", error);
            }
        });
    }

    
</script>
