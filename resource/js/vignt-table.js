(function(global) {
    function vignttable(tableId) {
        this.table = document.getElementById(tableId);
        this.ajaxUrl = '';
        this.searchInput = null;
        this.paginationDiv = null;
        this.currentPage = 1;
        this.rowsPerPage = 5;
        this.rows = [];
        this.totalPages = 1;
        this.isServerSide = false;
        this.fullData = []; 
    }

    vignttable.prototype.ajax = function(url) {
        this.ajaxUrl = url;
        return this;
    };

    vignttable.prototype.serverside = function(isServerSide) {
        this.isServerSide = isServerSide;
        return this;
    };

    vignttable.prototype.init = function() {
        const self = this;

        if (!this.table) {
            throw new Error(`Table with id ${tableId} not found`);
        }

        this.searchInput = document.createElement('input');
        this.searchInput.type = 'text';
        this.searchInput.placeholder = 'Search...';
        this.table.parentNode.insertBefore(this.searchInput, this.table);

        this.paginationDiv = document.createElement('div');
        this.table.parentNode.appendChild(this.paginationDiv);

        this.searchInput.addEventListener('input', function() {
            self.fetchData(self.searchInput.value);
        });

        this.fetchData();
    };

    vignttable.prototype.fetchData = function(query = '') {
        const self = this;

        if (this.isServerSide)
            {
            console.log(fetch(`${this.ajaxUrl}`).then(response => response.json()));
            fetch(`${this.ajaxUrl}?search=${encodeURIComponent(query)}&page=${this.currentPage}&rowsPerPage=${this.rowsPerPage}`)
                .then(response => response.json())
                .then(data => {
                    self.rows = data.rows;
                    self.totalPages = Math.ceil(data.totalRows / self.rowsPerPage);
                    self.renderPage(self.currentPage);
                    self.createPaginationControls();
                })
                .catch(error => console.error('Error fetching data:'));
        } else {
            const filteredRows = self.fullData.filter(row => row.name.toLowerCase().includes(query.toLowerCase()));
            self.totalPages = Math.ceil(filteredRows.length / self.rowsPerPage);
            self.rows = filteredRows;
            self.renderPage(self.currentPage);
            self.createPaginationControls();
        }
    };

    vignttable.prototype.renderPage = function(page) {
        const start = (page - 1) * this.rowsPerPage;
        const end = start + this.rowsPerPage;

        const tbody = this.table.querySelector('tbody');
        tbody.innerHTML = '';

        this.rows.slice(start, end).forEach(row => {
            const tr = document.createElement('tr');
            Object.values(row).forEach(cellData => {
                const td = document.createElement('td');
                td.textContent = cellData;
                tr.appendChild(td);
            });
            tbody.appendChild(tr);
        });
    };

    vignttable.prototype.createPaginationControls = function() {
        this.paginationDiv.innerHTML = '';

        for (let i = 1; i <= this.totalPages; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.addEventListener('click', () => {
                this.currentPage = i;
                this.fetchData(this.searchInput ? this.searchInput.value : '');
            });
            this.paginationDiv.appendChild(button);
        }
    };

    vignttable.prototype.reload = function() {
        this.fetchData(this.searchInput ? this.searchInput.value : '');
    };

    vignttable.prototype.clear = function() {
        this.searchInput.value = '';
        this.table.querySelector('tbody').innerHTML = '';
        this.paginationDiv.innerHTML = '';
    };

    global.vignttable = function(tableId) {
        const instance = new vignttable(tableId);
        return {
            ajax: function(url) {
                instance.ajax(url);
                return instance;
            },
            serverside: function(isServerSide) {
                instance.serverside(isServerSide);
                return instance;
            },
            init: function() {
                instance.init();
            },
            reload: function() {
                instance.reload();
            },
            clear: function() {
                instance.clear();
            },
            setData: function(data) {
                instance.fullData = data;
                instance.fetchData();
            }
        };
    };
})(window);
