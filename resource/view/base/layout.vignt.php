<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo baseconfig('appName') ?>
    </title>
    <style>
        /* .content {
            display: none;
        } */

        .loader {
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background-color: #16191e;
            position: absolute;
        }

        .loader>div {
            height: 100px;
            width: 100px;
            border: 15px solid #45474b;
            border-top-color: #ff8c00;
            position: absolute;
            margin: auto;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border-radius: 50%;
            animation: spin 1.2s infinite linear;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .vigntpop {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 9999;
        }

        .vigntpop-show {
            display: flex;
            opacity: 1;
        }

        .vigntpop-hide {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .vigntpop-content {
            background: white;
            border-radius: 10px;
            padding: 20px;
            max-width: 90%;
            width: 400px;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .vigntpop-show .vigntpop-content {
            transform: scale(1);
            opacity: 1;
        }

        .vigntpop-buttons {
            margin-top: 20px;
        }

        .vigntpop-buttons button {
            background: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .vigntpop-buttons button:hover {
            background: #0056b3;
        }

        .vigntpop-buttons button:nth-child(2) {
            background: #6c757d;
        }

        .vigntpop-buttons button:nth-child(2):hover {
            background: #5a6268;
        }


        #main-table-search {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        #main-table-search input {
            padding: 4px 8px;
            border: 1px solid #ccc;
            border-radius: 12px;
            margin-left: 10px;
            margin-right: 10px;
            font-size: 12px;
            width: 200px;
            max-width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 600px) {
            #main-table-search input {
                width: 100%;
            }
        }
    </style>
    <script>
        function vigntajax(r) {
            var e = r.url,
                t = r.method || "GET",
                n = r.data || null,
                o = r.success || function () { },
                s = r.error || function () { };
            if ("GET" === t && n) {
                var a = [];
                for (var i in n) n.hasOwnProperty(i) && a.push(encodeURIComponent(i) + "=" + encodeURIComponent(n[i]));
                e = e + (-1 === e.indexOf("?") ? "?" : "&") + a.join("&")
            }
            var u = new XMLHttpRequest;
            u.open(t, e, !0), u.setRequestHeader("Content-Type", "application/json"), u.onreadystatechange = function () {
                if (4 === u.readyState) {
                    if (u.status >= 200 && u.status < 300) try {
                        var r = JSON.parse(u.responseText);
                        o(r)
                    } catch (e) {
                        console.error("JSON parsing error:", e), s(e)
                    } else console.error("HTTP error! Status:", u.status, "Response:", u.responseText), s(Error("HTTP error! Status: " + u.status))
                }
            }, u.onerror = function () {
                console.error("Network error"), s(Error("Network error"))
            }, u.send("GET" === t || "HEAD" === t ? null : JSON.stringify(n))
        }

        (function (global) {
            var VigntJs = function (selector) {
                return new VigntJs.fn.init(selector);
            };

            VigntJs.fn = VigntJs.prototype = {
                constructor: VigntJs,
                init: function (selector) {
                    if (!selector) return this;
                    if (typeof selector === 'string') {
                        this.elements = document.querySelectorAll(selector);
                    } else if (selector.nodeType) {
                        this.elements = [selector];
                    }
                    return this;
                },
                each: function (callback) {
                    for (var i = 0; i < this.elements.length; i++) {
                        callback.call(this.elements[i], i, this.elements[i]);
                    }
                    return this;
                },
                get: function () {
                    if (this.elements[0]) {
                        return this.elements[0].value;
                    }
                    return null;
                },
                set: function (val) {
                    this.each(function () {
                        if (this.tagName.toLowerCase() === 'input' ||
                            this.tagName.toLowerCase() === 'textarea' ||
                            this.tagName.toLowerCase() === 'select') {
                            this.value = val;
                        } else {
                            this.innerHTML = val;
                        }
                    });
                    return this;
                },
                css: function (prop, value) {
                    this.each(function () {
                        this.style[prop] = value;
                    });
                    return this;
                },
                hide: function () {
                    this.each(function () {
                        this.style.display = 'none';
                    });
                    return this;
                },
                show: function () {
                    this.each(function () {
                        this.style.display = '';
                    });
                    return this;
                },
                click: function (callback) {
                    if (callback) {
                        this.each(function () {
                            this.addEventListener('click', callback);
                        });
                    } else {
                        this.each(function () {
                            this.click();
                        });
                    }
                    return this;
                },
                submit: function (callback) {
                    this.each(function () {
                        this.addEventListener('submit', function (event) {
                            event.preventDefault();
                            callback(event);
                        });
                    });
                    return this;
                }
            };

            VigntJs.fn.init.prototype = VigntJs.fn;

            global.VigntJs = global.vignt = VigntJs;
        })(window);


        class VigntTable {
            constructor(tableId, options = {}) {
                this.tableId = tableId;
                this.data = [];
                this.filteredData = [];
                this.currentPage = 1;
                this.rowsPerPage = options.rowsPerPage || 8;
                this.url = options.url || '';
                this.method = options.method || 'POST';
                this.columns = options.columns || [];
                this.sortable = options.sortable || [];
                this.searchable = options.searchable || false;
                this.paginationContainerId = `${tableId}-pagination-controls`;
                this.showNumbering = options.showNumbering || false;
                this.numberingColumnIndex = options.numberingColumnIndex || 0;
                this.processing = options.processing || false;
                this.onInit = options.onInit || function () { };
                this.afterRowCreated = options.afterRowCreated || function () { };

                this.createPaginationControls();
                if (this.searchable) {
                    this.createSearchInput();
                }

                if (this.processing) {
                    this.createProcessingModal();
                }

                this.onInit();
            }

            createProcessingModal() {
                const tableElement = document.querySelector(`#${this.tableId}`);
                const processingModal = document.createElement('div');
                processingModal.id = `${this.tableId}-processing-modal`;
                processingModal.style.position = 'absolute';
                processingModal.style.top = '0';
                processingModal.style.left = '0';
                processingModal.style.width = '100%';
                processingModal.style.height = '100%';
                processingModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
                processingModal.style.display = 'flex';
                processingModal.style.justifyContent = 'center';
                processingModal.style.alignItems = 'center';
                processingModal.style.zIndex = '1000';
                processingModal.style.color = 'white';
                processingModal.style.fontSize = '12px';
                processingModal.innerText = 'Processing...';
                processingModal.style.display = 'none';

                tableElement.style.position = 'relative';
                tableElement.appendChild(processingModal);
            }

            showProcessing() {
                if (this.processing) {
                    const modal = document.getElementById(`${this.tableId}-processing-modal`);
                    if (modal) {
                        modal.style.display = 'flex';
                    }
                }
            }

            hideProcessing() {
                if (this.processing) {
                    const modal = document.getElementById(`${this.tableId}-processing-modal`);
                    if (modal) {
                        modal.style.display = 'none';
                    }
                }
            }

            async fetchData() {
                this.showProcessing();
                try {
                    const response = await fetch(this.url, {
                        method: this.method,
                        headers: { 'Content-Type': 'application/json' },
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();
                    if (Array.isArray(data)) {
                        this.data = data;
                        this.filteredData = data;
                        this.updateTable();
                    } else {
                        console.error('Expected an array but got:', data);
                    }
                } catch (error) {
                    console.error('Error fetching data:', error);
                } finally {
                    this.hideProcessing();
                }
            }

            updateTable() {
                this.clear();

                const tableBody = document.querySelector(`#${this.tableId} tbody`);
                const startIndex = (this.currentPage - 1) * this.rowsPerPage;
                const endIndex = startIndex + this.rowsPerPage;
                const pageData = this.filteredData.slice(startIndex, endIndex);

                pageData.forEach((row, index) => {
                    const tr = document.createElement('tr');
                    const rowData = this.columns.map((col, colIndex) => {
                        const value = row[col.data] || '';
                        if (col.render) {
                            return `<td style="white-space: nowrap;">${col.render(value, row)}</td>`;
                        }
                        return `<td style="white-space: nowrap;">${value}</td>`;
                    });

                    if (this.showNumbering) {
                        rowData.splice(this.numberingColumnIndex, 0, `<td style="white-space: nowrap;">${startIndex + index + 1}</td>`);
                    }

                    tr.innerHTML = rowData.join('');
                    tableBody.appendChild(tr);

                    this.afterRowCreated(tr, row, startIndex + index + 1);
                });

                this.updatePaginationControls();
                this.setupHeaderSorting();
            }

            updatePaginationControls() {
                const totalPages = Math.ceil(this.filteredData.length / this.rowsPerPage);
                let paginationContainer = document.getElementById(this.paginationContainerId);

                if (!paginationContainer) {
                    paginationContainer = document.createElement('div');
                    paginationContainer.id = this.paginationContainerId;
                    paginationContainer.style.textAlign = 'center';
                    paginationContainer.style.marginTop = '10px';
                    paginationContainer.style.marginBottom = '10px';
                    document.querySelector(`#${this.tableId}`).insertAdjacentElement('afterend', paginationContainer);
                }

                paginationContainer.innerHTML = `
                    <i class="bx bx-chevron-left bx-sm align-middle" onclick="table.changePage(-1)"></i>
                    <span id="${this.paginationContainerId}-info" style="margin: 0 10px; font-size: 12px;"></span>
                    <i class="bx bx-chevron-right bx-sm align-middle" onclick="table.changePage(1)"></i>
                `;

                this.updatePageInfo();
            }

            updatePageInfo() {
                const totalPages = Math.ceil(this.filteredData.length / this.rowsPerPage);
                const pageInfo = document.getElementById(`${this.paginationContainerId}-info`);
                if (pageInfo) {
                    pageInfo.textContent = `Page ${this.currentPage} of ${totalPages}`;
                }
            }

            setPage(pageNumber) {
                this.currentPage = pageNumber;
                this.updateTable();
            }

            clear() {
                const tableBody = document.querySelector(`#${this.tableId} tbody`);
                if (tableBody) {
                    tableBody.innerHTML = '';
                }
            }

            changePage(delta) {
                const totalPages = Math.ceil(this.filteredData.length / this.rowsPerPage);
                const newPage = this.currentPage + delta;

                if (newPage >= 1 && newPage <= totalPages) {
                    this.currentPage = newPage;
                    this.updateTable();
                }
            }

            createPaginationControls() {
                const tableElement = document.querySelector(`#${this.tableId}`);
                if (!tableElement) {
                    return;
                }

                const paginationContainer = document.createElement('div');
                paginationContainer.id = this.paginationContainerId;
                paginationContainer.style.textAlign = 'center';
                paginationContainer.style.marginTop = '10px';
                paginationContainer.style.marginBottom = '10px';

                tableElement.insertAdjacentElement('afterend', paginationContainer);
            }

            createSearchInput() {
                const tableElement = document.querySelector(`#${this.tableId}`);
                if (!tableElement) {
                    return;
                }

                const searchContainer = document.createElement('div');
                searchContainer.id = `${this.tableId}-search`;
                searchContainer.style.position = 'relative';
                searchContainer.style.marginBottom = '10px';
                searchContainer.style.marginTop = '10px';
                searchContainer.style.width = '100%';
                searchContainer.style.display = 'flex';
                searchContainer.style.justifyContent = 'flex-end';

                searchContainer.innerHTML = `
                    <input type="text" id="${this.tableId}-search-input" placeholder="Search..." oninput="table.search(this.value)">
                `;

                tableElement.insertAdjacentElement('beforebegin', searchContainer);
            }

            search(query) {
                if (!query) {
                    this.filteredData = this.data;
                } else {
                    const lowerCaseQuery = query.toLowerCase();
                    this.filteredData = this.data.filter(row => {
                        return this.columns.some(col => {
                            const cellValue = row[col.data] || '';
                            return cellValue.toString().toLowerCase().includes(lowerCaseQuery);
                        });
                    });
                }
                this.currentPage = 1;
                this.updateTable();
            }

            sort(columnName) {
                const column = this.columns.find(col => col.name === columnName);
                if (!column || !this.sortable.includes(columnName)) return;

                const sortDirection = column.sortDirection === 'asc' ? 'desc' : 'asc';
                this.filteredData.sort((a, b) => {
                    const aValue = a[column.data];
                    const bValue = b[column.data];
                    if (aValue > bValue) return sortDirection === 'asc' ? 1 : -1;
                    if (aValue < bValue) return sortDirection === 'asc' ? -1 : 1;
                    return 0;
                });

                this.columns.forEach(col => col.sortDirection = undefined);
                column.sortDirection = sortDirection;

                this.updateTable();
            }

            setupHeaderSorting() {
                const headers = document.querySelectorAll(`#${this.tableId} thead th`);
                headers.forEach(header => {
                    const columnName = header.getAttribute('data-column');
                    if (this.sortable.includes(columnName)) {
                        header.style.cursor = 'pointer';
                        header.addEventListener('click', () => this.sort(columnName));
                    }
                });
            }
        }


        function formatCurrency(value) {
            if (typeof value === 'number' || !isNaN(Number(value))) {
                value = Number(value);
                return `Rp${value.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            }
            console.log(!isNaN(value), value);
            return `Rp0,00`;
        }

        function formatDate(dateString, format) {
            const date = new Date(dateString);

            if (isNaN(date)) {
                console.error('Invalid date string:', dateString);
                return dateString;
            }

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return format
                .replace('Y', year)
                .replace('m', month)
                .replace('d', day);
        }

        function vigntpop() {
            const popUp = document.createElement('div');
            popUp.className = 'vigntpop';

            const content = document.createElement('div');
            content.className = 'vigntpop-content';

            const textElement = document.createElement('p');
            textElement.className = 'vigntpop-text';

            const buttons = document.createElement('div');
            buttons.className = 'vigntpop-buttons';

            const confirmButton = document.createElement('button');
            confirmButton.textContent = 'Confirm';

            const cancelButton = document.createElement('button');
            cancelButton.textContent = 'Cancel';

            buttons.appendChild(confirmButton);
            buttons.appendChild(cancelButton);
            content.appendChild(textElement);
            content.appendChild(buttons);
            popUp.appendChild(content);
            document.body.appendChild(popUp);

            setTimeout(() => {
                popUp.classList.add('vigntpop-show');
            }, 10);

            return {
                confirmation: function (callback) {
                    confirmButton.addEventListener('click', function () {
                        callback();
                        popUp.classList.remove('vigntpop-show');
                        popUp.classList.add('vigntpop-hide');
                        setTimeout(() => {
                            popUp.remove();
                        }, 300);
                    });

                    return this;
                },
                text: function (message) {
                    textElement.textContent = message;
                    popUp.style.display = 'flex';
                    return this;
                },
                cancel: function (callback) {
                    cancelButton.addEventListener('click', function () {
                        if (typeof callback === 'function') {
                            callback();
                        }
                        popUp.classList.remove('vigntpop-show');
                        popUp.classList.add('vigntpop-hide');
                        setTimeout(() => {
                            popUp.remove();
                        }, 300);
                    });
                    return this;
                },
                close: function () {
                    popUp.classList.remove('vigntpop-show');
                    popUp.classList.add('vigntpop-hide');
                    setTimeout(() => {
                        popUp.remove();
                    }, 300);
                }
            };
        }

        function vigntnotif() {
            function createNotification(type, message, timeout) {
                const container = document.getElementById('notification-container');
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;

                const msg = document.createElement('div');
                msg.className = 'message';
                msg.textContent = message;

                const closeBtn = document.createElement('span');
                closeBtn.className = 'close-btn';
                closeBtn.textContent = '×';
                closeBtn.onclick = () => {
                    container.removeChild(notification);
                    if (timeoutId) clearTimeout(timeoutId);
                };

                notification.appendChild(msg);
                notification.appendChild(closeBtn);

                if (timeout) {
                    const progressContainer = document.createElement('div');
                    progressContainer.className = 'progress-container';

                    const progressBar = document.createElement('div');
                    progressBar.className = 'progress-bar';

                    progressContainer.appendChild(progressBar);
                    notification.appendChild(progressContainer);

                    container.appendChild(notification);

                    progressBar.style.transitionDuration = `${timeout}ms`;
                    setTimeout(() => {
                        progressBar.style.width = '100%';
                    }, 10);

                    var timeoutId = setTimeout(() => {
                        container.removeChild(notification);
                    }, timeout);
                } else {
                    container.appendChild(notification);
                }
            }

            return {
                success: (message) => ({
                    timeout: (duration) => {
                        createNotification('success', message, duration);
                        return this;
                    }
                }),
                error: (message) => ({
                    timeout: (duration) => {
                        createNotification('error', message, duration);
                        return this;
                    }
                }),
                warning: (message) => ({
                    timeout: (duration) => {
                        createNotification('warning', message, duration);
                        return this;
                    }
                }),
                info: (message) => ({
                    timeout: (duration) => {
                        createNotification('info', message, duration);
                        return this;
                    }
                }),
            };
        }

        function createLoadingOverlay() {
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.background = 'rgba(0, 0, 0, 0.5)';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.zIndex = '99999';
            overlay.style.pointerEvents = 'none';

            const spinner = document.createElement('div');
            spinner.className = 'spinner';
            spinner.style.border = '8px solid rgba(0, 0, 0, 0.1)';
            spinner.style.borderRadius = '50%';
            spinner.style.borderTop = '8px solid #fff';
            spinner.style.width = '50px';
            spinner.style.height = '50px';
            spinner.style.animation = 'spin 1s linear infinite';

            overlay.appendChild(spinner);
            document.body.appendChild(overlay);

            const style = document.createElement('style');
            style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
            document.head.appendChild(style);
        }

        function vigntLoadingStart() {
            if (!document.getElementById('loading-overlay')) {
                createLoadingOverlay();
            }
            document.getElementById('loading-overlay').style.display = 'flex';
            document.body.style.pointerEvents = 'none';
        }

        function vigntLoadingClose() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.style.display = 'none';
                document.body.style.pointerEvents = 'auto';
            }
        }


        document.addEventListener('DOMContentLoaded', () => {
            const style = document.createElement('style');
            style.textContent = `
                #notification-container {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    width: 300px;
                    z-index: 9999;
                }

                .notification {
                    display: flex;
                    align-items: center;
                    padding: 15px;
                    margin-bottom: 10px;
                    border-radius: 5px;
                    color: #fff;
                    font-family: Arial, sans-serif;
                    font-size: 14px;
                    position: relative;
                    overflow: hidden;
                }

                .notification.success {
                    background-color: #28a745;
                }

                .notification.error {
                    background-color: #dc3545;
                }

                .notification.warning {
                    background-color: #ffc107;
                }

                .notification.info {
                    background-color: #17a2b8;
                }

                .notification .message {
                    flex: 1;
                }

                .notification .close-btn {
                    margin-left: 10px;
                    cursor: pointer;
                    font-weight: bold;
                }

                .progress-container {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    height: 5px;
                    background-color: #e9ecef;
                    border-radius: 5px;
                }

                .progress-bar {
                    height: 100%;
                    background-color: #007bff;
                    width: 0;
                    transition: width linear;
                }
            `;
            document.head.appendChild(style);

            const container = document.createElement('div');
            container.id = 'notification-container';
            document.body.appendChild(container);
        });

        // window.addEventListener('load', function() {
        //     const loader = document.querySelector('.loader');
        //     const content = document.querySelector('.content');

        //     if (loader) {
        //         loader.style.transition = 'opacity 0.5s ease';
        //         loader.style.opacity = '0';
        //         setTimeout(() => {
        //             loader.style.display = 'none';
        //         }, 500);
        //     }

        //     if (content) {
        //         content.style.display = 'block';
        //         content.style.transition = 'opacity 0.5s ease';
        //         content.style.opacity = '0';
        //         setTimeout(() => {
        //             content.style.opacity = '1';
        //         }, 0);
        //     }
        // });


    </script>