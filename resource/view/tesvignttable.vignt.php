
<body>
    <table id="Vignt">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script>

        vigntajax({
            url: "{! vignt_url('vignt-table') !}",
            method: 'POST',
            data: {
                q: 'example',
                page: 2
            },
            success: function(data) {
                console.log('data : ', data);
            },
            error: function(err) {
                console.error('Error occurred:', err);
            }
        });

    </script>
</body>
</html>
