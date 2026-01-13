<form action="/upload-csv" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="csv_file" required>
    <button>Upload CSV</button>
</form>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>