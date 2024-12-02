<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <?php
        $a = 0;
        ?>
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
        !@foreach ($data as $row) @!
            <tr>
                <td>{! $a !}</td>
                <td>{! $row->nik !}</td>
                <td>{! $row->name !}</td>
            </tr>
        !@endforeach @!
    </tbody>
    
</table>
